<template>
    <div class="container mx-auto p-4">
      <h1 class="text-2xl mb-4">Login</h1>
      <form @submit.prevent="login">
        <input v-model="form.email" type="email" placeholder="Email" class="border p-2 mb-2 w-full" />
        <input v-model="form.password" type="password" placeholder="Password" class="border p-2 mb-2 w-full" />
        <button type="submit" class="bg-blue-500 text-white p-2">Login</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  import api from '../api';
  
  const form = ref({ email: '', password: '' });
  const router = useRouter();
  
  const login = async () => {
    try {
      const response = await api.post('/login', form.value);
      localStorage.setItem('token', response.data.token);
      router.push('/dashboard');
    } catch (error) {
      console.error('Login failed', error);
    }
  };
  </script>