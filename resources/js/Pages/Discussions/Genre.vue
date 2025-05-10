<template>
  <Head :title="genre.name + ' - Discussions'" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ genre.name }}</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="flex items-center justify-between">
              <div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ genre.name }}</div>
                <div class="text-gray-500 dark:text-gray-400 mt-1">{{ genre.description }}</div>
                <div class="mt-2 text-xs text-gray-400">{{ genre.discussions_count }} discussions</div>
              </div>
              <Link
                :href="route('discussions.create', { genre: genre.slug })"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
              >
                Create Discussion
              </Link>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div v-if="discussions.data.length === 0" class="text-center text-gray-500 dark:text-gray-400">
              No discussions found in this genre.
            </div>
            <div v-else class="space-y-6">
              <div v-for="discussion in discussions.data" :key="discussion.id" class="border-b dark:border-gray-700 pb-6 last:border-0 last:pb-0">
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <Link :href="route('discussions.show', discussion.id)" class="text-lg font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                      {{ discussion.title }}
                    </Link>
                    <div class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
                      <span>Posted by {{ discussion.user.name }}</span>
                      <span class="mx-2">•</span>
                      <span>{{ formatDate(discussion.created_at) }}</span>
                      <span v-if="discussion.team" class="mx-2">•</span>
                      <span v-if="discussion.team" class="text-blue-600 dark:text-blue-400">
                        {{ discussion.team.name }}
                      </span>
                    </div>
                  </div>
                  <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      {{ discussion.replies_count }} replies
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      {{ discussion.likes_count }} likes
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { formatDistanceToNow } from 'date-fns';

const props = defineProps({
  genre: Object,
  discussions: Object,
});

const formatDate = (date) => {
  return formatDistanceToNow(new Date(date), { addSuffix: true });
};
</script> 