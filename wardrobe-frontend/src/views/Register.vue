<template>
    <div class="container mx-auto p-4">
      <h1 class="text-2xl mb-4">Register</h1>
      <form @submit.prevent="register">
        <input v-model="form.name" type="text" placeholder="Name" class="border p-2 mb-2 w-full" />
        <input v-model="form.email" type="email" placeholder="Email" class="border p-2 mb-2 w-full" />
        <input v-model="form.password" type="password" placeholder="Password" class="border p-2 mb-2 w-full" />
        <button type="submit" class="bg-blue-500 text-white p-2">Register</button>
      </form>
      <p class="mt-10 text-center text-sm/6 text-gray-500">
        Not a member?
        <router-link :to="{ name: 'Login' }" class="font-semibold text-indigo-600 hover:text-indigo-500">Login to your Account</router-link>
      </p>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  import api from '../api';
  
  const form = ref({ name: '', email: '', password: '' });
  const router = useRouter();
  
  const register = async () => {
  console.log('Form data:', form.value);
  try {
    const response = await api.post('/register', form.value);
    console.log('API response:', response.data);
    localStorage.setItem('token', response.data.token);
    console.log('Stored token:', localStorage.getItem('token'));
    router.push('/dashboard');
  } catch (error) {
    console.error('Registration failed', error.response?.data || error.message);
  }
};
  </script>