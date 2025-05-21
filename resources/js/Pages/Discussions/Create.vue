<template>
  <Head title="Izveidot diskusiju" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Izveidot diskusiju</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <form @submit.prevent="submit">
              <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nosaukums</label>
                <input
                  type="text"
                  id="title"
                  v-model="form.title"
                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                  required
                />
              </div>

              <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Saturs</label>
                <textarea
                  id="content"
                  v-model="form.content"
                  rows="6"
                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                  required
                ></textarea>
              </div>

              <div class="mt-4">
                <InputLabel for="genre" value="Genre" />
                <div v-if="selectedGenre">
                  <div class="mt-1 block w-full border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-md shadow-sm px-4 py-2">
                    {{ selectedGenre.name }}
                  </div>
                  <input type="hidden" v-model="form.genre_id" />
                </div>
                <select
                  v-else
                  id="genre"
                  v-model="form.genre_id"
                  class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                  required
                >
                  <option v-for="genre in genres" :key="genre.id" :value="genre.id">
                    {{ genre.name }}
                  </option>
                </select>
              </div>

              <div class="flex justify-end">
                <button
                  type="submit"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                  :disabled="form.processing"
                >
                  Create Discussion
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  genres: Array,
  selectedGenre: Object,
});

const form = useForm({
  title: '',
  content: '',
  genre_id: props.selectedGenre ? props.selectedGenre.id : '',
});

const submit = () => {
  form.post(route('discussions.store'));
};
</script> 