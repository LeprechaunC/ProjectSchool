<template>
  <Head :title="discussion.title" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Diskusija</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <!-- Discussion Header -->
            <div class="mb-8">
              <div class="flex justify-between items-start">
                <div>
                  <h1 v-if="!isEditing" class="text-3xl font-bold">{{ discussion.title }}</h1>
                  <input
                    v-else
                    v-model="editForm.title"
                    type="text"
                    class="w-full text-3xl font-bold bg-transparent border-b border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-0"
                  />
                  <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <span>Ievietoja {{ discussion.user.name }}</span>
                    <span v-if="discussion.team" class="mx-2">•</span>
                    <span v-if="discussion.team">komandā {{ discussion.team.name }}</span>
                    <span class="mx-2">•</span>
                    <span>{{ formatDate(discussion.created_at) }}</span>
                  </div>
                </div>
                <div v-if="canEdit" class="flex space-x-2">
                  <button
                    v-if="!isEditing"
                    @click="startEditing"
                    class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                  <button
                    v-if="isEditing"
                    @click="saveEdit"
                    class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-500"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                  </button>
                  <button
                    v-if="isEditing"
                    @click="cancelEdit"
                    class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-500"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                  <button
                    @click="confirmDelete"
                    class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-500"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Discussion Content -->
            <div class="prose dark:prose-invert max-w-none mb-8">
              <div v-if="!isEditing">{{ discussion.content }}</div>
              <textarea
                v-else
                v-model="editForm.content"
                rows="6"
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
              ></textarea>
            </div>

            <!-- Like Button -->
            <div class="flex items-center space-x-4 mb-8">
              <form @submit.prevent="likeDiscussion" class="inline">
                <button type="submit" class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                  </svg>
                  <span>{{ discussion.likes_count }} Patīk</span>
                </button>
              </form>
            </div>

            <!-- Reply Form -->
            <div class="mb-8">
              <h3 class="text-lg font-semibold mb-4">Pievienot atbildi</h3>
              <form @submit.prevent="submitReply">
                <div class="mb-4">
                  <textarea
                    v-model="replyForm.content"
                    rows="4"
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required
                  ></textarea>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                  Publicēt atbildi
                </button>
              </form>
            </div>

            <!-- Replies -->
            <div class="space-y-6">
              <h3 class="text-lg font-semibold">{{ discussion.replies_count }} Atbildes</h3>
              <div v-for="reply in discussion.replies.filter(r => !r.parent_id)" :key="reply.id" class="border rounded-lg p-4">
                <Reply
                  :reply="reply"
                  :discussion="discussion"
                  @reply="submitNestedReply"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Reply from '@/Components/Reply.vue';
import { formatDistanceToNow } from 'date-fns';

const props = defineProps({
  discussion: Object,
  auth: Object,
});

const isEditing = ref(false);
const editForm = useForm({
  title: '',
  content: '',
});

const canEdit = computed(() => {
  return props.auth.user.id === props.discussion.user_id || props.auth.user.role === 'admin';
});

const replyForm = useForm({
  content: '',
  parent_id: null,
});

const likeForm = useForm({});

const formatDate = (date) => {
  return formatDistanceToNow(new Date(date), { addSuffix: true });
};

const startEditing = () => {
  editForm.title = props.discussion.title;
  editForm.content = props.discussion.content;
  isEditing.value = true;
};

const saveEdit = () => {
  editForm.put(route('discussions.update', props.discussion.id), {
    preserveScroll: true,
    onSuccess: () => {
      isEditing.value = false;
    },
  });
};

const cancelEdit = () => {
  isEditing.value = false;
};

const confirmDelete = () => {
  if (confirm('Are you sure you want to delete this discussion?')) {
    deleteForm.delete(route('discussions.destroy', props.discussion.id), {
      preserveScroll: true,
      onSuccess: () => {
        window.location.href = route('discussions.index');
      },
    });
  }
};

const deleteForm = useForm({});

const submitReply = () => {
  replyForm.post(route('discussions.reply', props.discussion.id), {
    preserveScroll: true,
    onSuccess: () => {
      replyForm.reset();
    },
  });
};

const submitNestedReply = (parentId, content) => {
  replyForm.parent_id = parentId;
  replyForm.content = content;
  submitReply();
};

const likeDiscussion = () => {
  likeForm.post(route('discussions.like', props.discussion.id), {
    preserveScroll: true,
  });
};
</script> 