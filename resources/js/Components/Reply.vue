<template>
  <div>
    <div class="flex justify-between items-start">
      <div>
        <div class="flex items-center space-x-2">
          <span class="font-semibold">{{ reply.user.name }}</span>
          <span class="text-sm text-gray-500 dark:text-gray-400">{{ formatDate(reply.created_at) }}</span>
        </div>
        <p class="mt-2">{{ reply.content }}</p>
      </div>
      <form @submit.prevent="likeReply" class="inline">
        <button type="submit" class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
          </svg>
          <span>{{ reply.likes_count }}</span>
        </button>
      </form>
    </div>

    <!-- Nested Replies -->
    <div v-if="reply.replies && reply.replies.length > 0" class="ml-8 mt-4 space-y-4">
      <div v-for="nestedReply in reply.replies" :key="nestedReply.id" class="border-l-2 pl-4">
        <Reply
          :reply="nestedReply"
          :discussion="discussion"
          @reply="handleNestedReply"
        />
      </div>
    </div>

    <!-- Reply to Reply Form -->
    <div class="ml-8 mt-4">
      <form @submit.prevent="submitNestedReply">
        <div class="mb-2">
          <textarea
            v-model="nestedReplyForm.content"
            rows="2"
            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
            placeholder="Reply to this comment..."
            required
          ></textarea>
        </div>
        <button type="submit" class="text-sm bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold py-1 px-3 rounded">
          Reply
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { formatDistanceToNow } from 'date-fns';

const props = defineProps({
  reply: Object,
  discussion: Object,
});

const emit = defineEmits(['reply']);

const nestedReplyForm = useForm({
  content: '',
});

const likeForm = useForm({});

const formatDate = (date) => {
  return formatDistanceToNow(new Date(date), { addSuffix: true });
};

const submitNestedReply = () => {
  emit('reply', props.reply.id, nestedReplyForm.content);
  nestedReplyForm.reset();
};

const handleNestedReply = (parentId, content) => {
  emit('reply', parentId, content);
};

const likeReply = () => {
  likeForm.post(route('replies.like', props.reply.id), {
    preserveScroll: true,
  });
};
</script> 