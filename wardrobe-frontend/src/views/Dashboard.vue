<template>
  <div class="container mx-auto p-6 max-w-5xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">My Wardrobe</h1>
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
      <input
        v-model="search"
        placeholder="Search items..."
        class="flex-1 border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
      />
      <select
        v-model="category"
        class="border border-gray-300 rounded-lg p-3 shadow-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-400"
      >
        <option value="">All Categories</option>
        <option value="tops">Tops</option>
        <option value="bottoms">Bottoms</option>
        <option value="shoes">Shoes</option>
      </select>
    </div>
    <button
      @click="showForm = true"
      class="bg-green-600 text-white py-2 px-4 rounded-lg shadow-md hover:bg-green-700 transition duration-200"
    >
      Add Item
    </button>

    <div v-if="showForm" class="mt-6 p-6 bg-gray-50 rounded-lg shadow-md">
      <form @submit.prevent="addItem" class="space-y-4">
        <input
          v-model="newItem.name"
          placeholder="Name"
          class="w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <select
          v-model="newItem.category"
          class="w-full border border-gray-300 rounded-lg p-3 shadow-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
          <option value="tops">Tops</option>
          <option value="bottoms">Bottoms</option>
          <option value="shoes">Shoes</option>
        </select>
        <input
          v-model="newItem.description"
          placeholder="Description"
          class="w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <input type="file" @change="onFileChange" class="w-full text-gray-600" />
        <div class="flex gap-3">
          <button
            type="submit"
            class="bg-blue-600 text-white py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 transition duration-200"
          >
            Save
          </button>
          <button
            @click="showForm = false"
            class="bg-gray-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-600 transition duration-200"
          >
            Cancel
          </button>
        </div>
      </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
      <div
        v-for="item in filteredItems"
        :key="item.id"
        class="border border-gray-200 rounded-lg p-4 shadow-md hover:shadow-lg transition duration-200 bg-white"
      >
        <img
          v-if="item.image"
          :src="`/storage/${item.image}`"
          class="w-full h-48 object-cover rounded-md mb-3"
        />
        <h3 class="text-lg font-semibold text-gray-800">{{ item.name }}</h3>
        <p class="text-sm text-gray-500 capitalize">{{ item.category }}</p>
        <p class="text-gray-600 mt-1 truncate">{{ item.description }}</p>
        <div class="mt-3 flex gap-2">
          <button
            @click="editItem(item)"
            class="bg-yellow-500 text-white py-1 px-3 rounded-md hover:bg-yellow-600 transition duration-200"
          >
            Edit
          </button>
          <button
            @click="deleteItem(item.id)"
            class="bg-red-500 text-white py-1 px-3 rounded-md hover:bg-red-600 transition duration-200"
          >
            Delete
          </button>
        </div>
      </div>
      <div v-if="filteredItems.length === 0" class="text-center col-span-full py-10 text-gray-500">
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
    console.log('Response data:', response.data);
    items.value = Array.isArray(response.data) ? response.data : [];
    console.log('Items assigned:', items.value);
  } catch (error) {
    console.error('Fetch error:', {
      message: error.message,
      status: error.response?.status,
      data: error.response?.data,
    });
    items.value = [];
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
    items.value.push(response.data);
    newItem.value = { name: '', category: '', description: '', image: null };
    showForm.value = false;
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
  newItem.value = { ...item, image: null };
  showForm.value = true;
};

const onFileChange = (e) => {
  newItem.value.image = e.target.files[0];
};

onMounted(() => {
  fetchItems();
});
</script>