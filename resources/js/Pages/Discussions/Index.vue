<template>
  <Head title="Diskusijas" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Diskusijas</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Genre Navigation -->
        <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div v-for="genre in genres" :key="genre.id" class="flex items-center p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <div class="flex-shrink-0 mr-4">
                  <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <Link :href="route('discussions.genre', genre.slug)" class="text-lg font-bold text-blue-700 dark:text-blue-300 hover:underline">
                    {{ genre.name }}
                  </Link>
                  <div class="text-sm text-gray-500 dark:text-gray-300 mt-1">{{ genre.description }}</div>
                  <div class="mt-2 text-xs text-gray-400">
                    {{ genre.discussions_count }} diskusijas
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Create Discussion Button -->
        <div class="mb-6">
          <Link
            :href="route('discussions.create')"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
          >
            Izveidot diskusiju
          </Link>
        </div>

        <!-- Discussions List -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div v-if="discussions.data.length === 0" class="text-center text-gray-500 dark:text-gray-400">
              Diskusiju nav atrasts.
            </div>
            <div v-else class="space-y-6">
              <div v-for="discussion in filteredDiscussions" :key="discussion.id" class="border-b dark:border-gray-700 pb-6 last:border-0 last:pb-0">
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <Link :href="route('discussions.show', discussion.id)" class="text-lg font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                      {{ discussion.title }}
                    </Link>
                    <div class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
                      <span>Ievietoja {{ discussion.user.name }}</span>
                      <span class="mx-2">•</span>
                      <span>{{ formatDate(discussion.created_at) }}</span>
                      <span v-if="discussion.team" class="mx-2">•</span>
                      <span v-if="discussion.team" class="text-blue-600 dark:text-blue-400">
                        {{ discussion.team.name }}
                      </span>
                      <span class="mx-2">•</span>
                      <span class="text-blue-600 dark:text-blue-400">
                        {{ discussion.genre.name }}
                      </span>
                    </div>
                  </div>
                  <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      {{ discussion.replies_count }} atbildes
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      {{ discussion.likes_count }} patīk
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
              <Pagination :links="discussions.links" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { formatDistanceToNow } from 'date-fns';

const props = defineProps({
  discussions: Object,
  genres: Array,
});

const selectedGenre = ref(null);

const filteredDiscussions = computed(() => {
  if (!selectedGenre.value) {
    return props.discussions.data;
  }
  return props.discussions.data.filter(discussion => discussion.genre_id === selectedGenre.value);
});

const formatDate = (date) => {
  return formatDistanceToNow(new Date(date), { addSuffix: true });
};
</script> 