<template>
  <div class="flex flex-col h-full bg-white dark:bg-gray-800 rounded-xl shadow-lg">
    <!-- Chat Header -->
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 rounded-full overflow-hidden">
            <img v-if="team?.profile_picture" 
                 :src="`/storage/${team.profile_picture}`" 
                 alt="Team Profile" 
                 class="w-full h-full object-cover" />
            <div v-else class="w-full h-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
              <span class="text-lg font-semibold text-blue-800 dark:text-blue-200">
                {{ team?.name?.charAt(0) }}
              </span>
            </div>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ team?.name }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ team?.users?.length }} dalībnieki</p>
          </div>
        </div>
        <button 
          v-if="$emit"
          @click="$emit('close')"
          class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Chat Messages -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4" ref="messagesContainer">
      <div v-for="message in messages" :key="message.id" class="flex items-start space-x-3">
        <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0">
          <img v-if="message.user.profile_picture" 
               :src="`/storage/${message.user.profile_picture}`" 
               alt="Profile" 
               class="w-full h-full object-cover" />
          <div v-else class="w-full h-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
            <span class="text-sm font-medium text-blue-800 dark:text-blue-200">
              {{ message.user.name.charAt(0) }}
            </span>
          </div>
        </div>
        <div class="flex-1">
          <div class="flex items-center space-x-2">
            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ message.user.name }}</span>
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ formatTime(message.created_at) }}</span>
          </div>
          <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ message.content }}</p>
        </div>
      </div>
    </div>

    <!-- Message Input -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
      <form @submit.prevent="sendMessage" class="flex items-center space-x-3">
        <input
          v-model="newMessage"
          type="text"
          placeholder="Ierakstiet savu ziņojumu..."
          class="flex-1 px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
        />
        <button
          type="submit"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center min-w-[100px]"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
          </svg>
          Sūtīt
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, nextTick, watch, onUnmounted } from 'vue';
import axios from 'axios';

export default {
  props: {
    team: {
      type: Object,
      required: true
    }
  },
  setup(props) {
    const messages = ref([]);
    const newMessage = ref('');
    const messagesContainer = ref(null);
    let pollInterval = null;

    const fetchMessages = async () => {
      try {
        const response = await axios.get(`/api/teams/${props.team.id}/messages`);
        messages.value = response.data;
        scrollToBottom();
      } catch (error) {
        console.error('Error fetching messages:', error);
      }
    };

    const sendMessage = async () => {
      if (!newMessage.value.trim()) return;

      try {
        const response = await axios.post(`/api/teams/${props.team.id}/messages`, {
          content: newMessage.value
        });
        messages.value.push(response.data);
        newMessage.value = '';
        scrollToBottom();
      } catch (error) {
        console.error('Error sending message:', error);
      }
    };

    const scrollToBottom = async () => {
      await nextTick();
      if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
      }
    };

    const formatTime = (timestamp) => {
      const date = new Date(timestamp);
      return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    };

    const startPolling = () => {
      // Clear any existing interval
      if (pollInterval) {
        clearInterval(pollInterval);
      }
      // Start new polling
      pollInterval = setInterval(fetchMessages, 5000);
    };

    const stopPolling = () => {
      if (pollInterval) {
        clearInterval(pollInterval);
        pollInterval = null;
      }
    };

    // Watch for team changes
    watch(() => props.team.id, (newTeamId, oldTeamId) => {
      if (newTeamId !== oldTeamId) {
        // Reset messages when team changes
        messages.value = [];
        // Fetch new messages
        fetchMessages();
      }
    });

    onMounted(() => {
      fetchMessages();
      startPolling();
    });

    onUnmounted(() => {
      stopPolling();
    });

    return {
      messages,
      newMessage,
      messagesContainer,
      sendMessage,
      formatTime
    };
  }
};
</script>

<style scoped>
.messages-container {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.messages-container::-webkit-scrollbar {
  width: 4px;
}

.messages-container::-webkit-scrollbar-track {
  background: transparent;
}

.messages-container::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 2px;
}

.dark .messages-container::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.3);
}
</style> 