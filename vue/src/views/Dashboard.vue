<template>
    <div class="min-h-full">
      <!-- Navigation Bar -->
      <Disclosure as="nav" class="bg-gray-800" v-slot="{ open }">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
             
              <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-4">
                  <a v-for="item in navigation" :key="item.name" :href="item.href" :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'rounded-md px-3 py-2 text-sm font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</a>
                </div>
              </div>
            </div>
            <div class="hidden md:block">
              <div class="ml-4 flex items-center md:ml-6">
                <button type="button" class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden">
                  <span class="absolute -inset-1.5" />
                  <span class="sr-only">View notifications</span>
                  <BellIcon class="size-6" aria-hidden="true" />
                </button>
                <Menu as="div" class="relative ml-3">
                  <div>
                    <MenuButton class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden">
                      <span class="absolute -inset-1.5" />
                      <span class="sr-only">Open user menu</span>
                      <img class="size-8 rounded-full" :src="user.imageUrl" alt="" />
                    </MenuButton>
                  </div>
                  <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                  <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 ring-1 shadow-lg ring-black/5 focus:outline-hidden">
                    <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                      <a 
                        v-if="item.name !== 'Sign out'"
                        :href="item.href" 
                        :class="[active ? 'bg-gray-100 outline-hidden' : '', 'block px-4 py-2 text-sm text-gray-700']"
                      >
                        {{ item.name }}
                      </a>
                      <button
                        v-else
                        @click="signOut"
                        :class="[active ? 'bg-gray-100 outline-hidden' : '', 'block w-full text-left px-4 py-2 text-sm text-gray-700']"
                      >
                        {{ item.name }}
                      </button>
                    </MenuItem>
                  </MenuItems>
                </transition>
                </Menu>
              </div>
            </div>
            <div class="-mr-2 flex md:hidden">
              <DisclosureButton class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden">
                <span class="absolute -inset-0.5" />
                <span class="sr-only">Open main menu</span>
                <Bars3Icon v-if="!open" class="block size-6" aria-hidden="true" />
                <XMarkIcon v-else class="block size-6" aria-hidden="true" />
              </DisclosureButton>
            </div>
          </div>
        </div>
  
        <DisclosurePanel class="md:hidden">
          <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
            <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="item.href" :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'block rounded-md px-3 py-2 text-base font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</DisclosureButton>
          </div>
          <div class="border-t border-gray-700 pt-4 pb-3">
            <div class="flex items-center px-5">
              <div class="shrink-0">
                <img class="size-10 rounded-full" :src="user.imageUrl" alt="" />
              </div>
              <div class="ml-3">
                <div class="text-base/5 font-medium text-white">{{ user.name }}</div>
                <div class="text-sm font-medium text-gray-400">{{ user.email }}</div>
              </div>
              <button type="button" class="relative ml-auto shrink-0 rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden">
                <span class="absolute -inset-1.5" />
                <span class="sr-only">View notifications</span>
                <BellIcon class="size-6" aria-hidden="true" />
              </button>
            </div>
            <div class="mt-3 space-y-1 px-2">
              <DisclosureButton v-for="item in userNavigation" :key="item.name" as="a" :href="item.href" class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">{{ item.name }}</DisclosureButton>
            </div>
          </div>
        </DisclosurePanel>
      </Disclosure>
  
      <!-- Header -->
      <header class="bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold tracking-tight text-gray-900">My Wardrobe</h1>
        </div>
      </header>
  
      <!-- Main Content -->
      <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <input
              v-model="search"
              placeholder="Search items..."
              class="flex-1 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
            />
            <select
              v-model="category"
              class="rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
            >
              <option value="">All Categories</option>
              <option value="tops">Tops</option>
              <option value="bottoms">Bottoms</option>
              <option value="shoes">Shoes</option>
            </select>
          </div>
          <button
            @click="showForm = true"
            class="flex w-full sm:w-auto justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500"
          >
            Add Item
          </button>
  
          <div v-if="showForm" class="mt-6 p-6 bg-white rounded-md shadow-sm">
            <form @submit.prevent="addItem" class="space-y-6">
              <div>
                <label for="name" class="block text-sm/6 font-medium text-gray-900">Name</label>
                <input
                  v-model="newItem.name"
                  placeholder="Item Name"
                  class="mt-2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                />
              </div>
              <div>
                <label for="category" class="block text-sm/6 font-medium text-gray-900">Category</label>
                <select
                  v-model="newItem.category"
                  class="mt-2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                >
                  <option value="tops">Tops</option>
                  <option value="bottoms">Bottoms</option>
                  <option value="shoes">Shoes</option>
                </select>
              </div>
              <div>
                <label for="description" class="block text-sm/6 font-medium text-gray-900">Description</label>
                <input
                  v-model="newItem.description"
                  placeholder="Description"
                  class="mt-2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                />
              </div>
              <div>
        <label for="image" class="block text-sm/6 font-medium text-gray-900">Image</label>
        <div class="mt-2">
          <input 
            type="file" 
            ref="fileInput"
            @change="onFileChange" 
            accept="image/*"
            class="block w-full text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
          />
            </div>
        
      </div>
              <div class="flex gap-3">
                <button
                  type="submit"
                  class="flex w-full sm:w-auto justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500"
                >
                  Save
                </button>
                <button
                  @click="showForm = false"
                  class="flex w-full sm:w-auto justify-center rounded-md bg-gray-500 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-gray-600"
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
              class="rounded-md bg-white p-4 shadow-sm hover:shadow-md transition duration-200"
            >
            <img v-if="item.image" :src="`http://localhost:8000/storage/${item.image}`" @error="console.log('Image failed to load:', item.image)" />  <h3 class="text-lg font-semibold text-gray-900">{{ item.name }}</h3>
              <p class="text-sm text-gray-500 capitalize">{{ item.category }}</p>
              <p class="text-gray-600 mt-1 truncate">{{ item.description }}</p>
              <div class="mt-3 flex gap-2">
                <button
                  @click="editItem(item)"
                  class="flex w-full sm:w-auto justify-center rounded-md bg-yellow-600 px-3 py-1 text-sm/6 font-semibold text-white shadow-xs hover:bg-yellow-700"
                >
                  Edit
                </button>
                <button
                  @click="deleteItem(item.id)"
                  class="flex w-full sm:w-auto justify-center rounded-md bg-red-600 px-3 py-1 text-sm/6 font-semibold text-white shadow-xs hover:bg-red-700"
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
      </main>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
  import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline';
  import api from '../api';
  import { useRouter } from 'vue-router';
  import wardrobe1 from '../assets/wardrobe.jpg';

  const router = useRouter();
  const user = {
    name: 'Tom Cook',
    email: 'tom@example.com',
    imageUrl: 'https://static.vecteezy.com/system/resources/thumbnails/004/607/791/small_2x/man-face-emotive-icon-smiling-male-character-in-blue-shirt-flat-illustration-isolated-on-white-happy-human-psychological-portrait-positive-emotions-user-avatar-for-app-web-design-vector.jpg',
  };
  const navigation = [
    { name: 'Dashboard', href: '#', current: true },
    { name: 'New Cloths', href: '#', },
  ];
  const userNavigation = [
    { name: 'Your Profile', href: '#' },
    { name: 'Settings', href: '#' },
    { name: 'Sign out', href: '#' },
  ];
  
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
    const response = await api.get('/clothing-items');
    console.log('API Response:', response.data);
    items.value = Array.isArray(response.data) ? response.data : [];
  } catch (error) {
    console.error('Fetch error:', error);
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
  const signOut = async () => {
  console.log('Sign out clicked');
  try {
    const token = localStorage.getItem('token');
    console.log('Token before sign out:', token);
    const response = await api.post('/logout');
    console.log('API response:', response.data);
    localStorage.removeItem('token');
    console.log('Token removed');
    items.value = [];
    console.log('Items cleared');
    router.push('/login');
    console.log('Redirecting to /login');
  } catch (error) {
    console.error('Sign out error:', error.response?.data || error.message);
  }
};
  
  onMounted(() => {
    fetchItems();
  });
  </script>