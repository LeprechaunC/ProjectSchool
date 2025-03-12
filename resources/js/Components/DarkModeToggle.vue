<script setup>
import { ref, onMounted } from 'vue';

const isDarkMode = ref(localStorage.getItem('theme') === 'dark');

// Function to toggle dark mode
const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value;
  const theme = isDarkMode.value ? 'dark' : 'light';

  localStorage.setItem('theme', theme); // Save to localStorage
  document.documentElement.classList.toggle('dark', isDarkMode.value); // Apply class to <html>
};

// Apply theme on mount
onMounted(() => {
  if (isDarkMode.value) {
    document.documentElement.classList.add('dark');
  }
});
</script>

<template>
  <button
    @click="toggleDarkMode"
    class="p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500"
  >
    <span v-if="isDarkMode" class="text-yellow-400">🌙</span>
    <span v-else class="text-gray-800">☀️</span>
  </button>
</template>
