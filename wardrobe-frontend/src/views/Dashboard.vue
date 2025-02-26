<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl mb-4">My Wardrobe</h1>
    <div class="mb-4">
      <input v-model="search" placeholder="Search items..." class="border p-2 mr-2" />
      <select v-model="category" class="border p-2">
        <option value="">All Categories</option>
        <option value="tops">Tops</option>
        <option value="bottoms">Bottoms</option>
        <option value="shoes">Shoes</option>
      </select>
    </div>
    <button @click="showForm = true" class="bg-green-500 text-white p-2 mb-4">Add Item</button>

    <div v-if="showForm" class="mb-4">
      <form @submit.prevent="addItem">
        <input v-model="newItem.name" placeholder="Name" class="border p-2 mb-2 w-full" />
        <select v-model="newItem.category" class="border p-2 mb-2 w-full">
          <option value="tops">Tops</option>
          <option value="bottoms">Bottoms</option>
          <option value="shoes">Shoes</option>
        </select>
        <input v-model="newItem.description" placeholder="Description" class="border p-2 mb-2 w-full" />
        <input type="file" @change="onFileChange" class="mb-2" />
        <button type="submit" class="bg-blue-500 text-white p-2">Save</button>
        <button @click="showForm = false" class="bg-gray-500 text-white p-2 ml-2">Cancel</button>
      </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div v-for="item in filteredItems" :key="item.id" class="border p-4">
        <img v-if="item.image" :src="`/storage/${item.image}`" class="w-full h-40 object-cover mb-2" />
        <h3>{{ item.name }}</h3>
        <p>{{ item.category }}</p>
        <p>{{ item.description }}</p>
        <button @click="editItem(item)" class="bg-yellow-500 text-white p-1 mr-2">Edit</button>
        <button @click="deleteItem(item.id)" class="bg-red-500 text-white p-1">Delete</button>
      </div>
      <div v-if="filteredItems.length === 0" class="text-center col-span-full">
        No items found. Add some to get started!
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '../api';

const items = ref([]);
const search = ref('');
const category = ref('');
const showForm = ref(false);
const newItem = ref({ name: '', category: '', description: '', image: null });

const filteredItems = computed(() => {
  return items.value.filter((item) => {
    const matchesSearch = item.name.toLowerCase().includes(search.value.toLowerCase());
    const matchesCategory = !category.value || item.category === category.value;
    return matchesSearch && matchesCategory;
  });
});

const fetchItems = async () => {
  try {
    console.log('Fetching items from /api/clothing-items...');
    const response = await api.get('/clothing-items');
    console.log('Raw response:', response); // Log the full response object
    console.log('Response data:', response.data); // Log just the data
    items.value = response.data; // Assign the data
    console.log('Items assigned:', items.value); // Confirm assignment
  } catch (error) {
    console.error('Fetch error:', {
      message: error.message,
      status: error.response?.status,
      data: error.response?.data,
    });
  }
};

const addItem = async () => {
  try {
    const formData = new FormData();
    Object.keys(newItem.value).forEach((key) => formData.append(key, newItem.value[key]));
    const response = await api.post('/clothing-items', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    console.log('Added item:', response.data);
    newItem.value = { name: '', category: '', description: '', image: null };
    showForm.value = false;
    await fetchItems();
  } catch (error) {
    console.error('Error adding item:', error.response?.data || error.message);
  }
};

const deleteItem = async (id) => {
  try {
    await api.delete(`/clothing-items/${id}`);
    await fetchItems();
  } catch (error) {
    console.error('Delete error:', error.response?.data || error.message);
  }
};

const editItem = (item) => {
  console.log('Editing item:', item);
  newItem.value = { ...item, image: null }; // Reset image since it’s a file
  showForm.value = true;
};

const onFileChange = (e) => {
  newItem.value.image = e.target.files[0];
};

onMounted(() => {
  fetchItems();
});
</script>