<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-6">
    <div class="max-w-6xl mx-auto">
      <!-- Header Section -->
      <div class="mb-8">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Messages</h2>
            <p class="text-gray-600 dark:text-gray-400">Chat with your team members</p>
          </div>
          <button 
            @click="showNewChatModal = true"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            New Chat
          </button>
        </div>
      </div>

      <!-- Main Content -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Conversations List -->
        <div class="md:col-span-1 bg-white dark:bg-gray-800 rounded-xl shadow-md p-4">
          <div class="mb-4">
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Search conversations..."
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            />
          </div>
          <div class="divide-y divide-gray-200 dark:divide-gray-700">
            <button
              v-for="conversation in conversations"
              :key="conversation.user?.id"
              @click="selectConversation(conversation.user)"
              class="w-full p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-left"
              :class="{ 'bg-blue-50 dark:bg-blue-900': selectedUser?.id === conversation.user?.id }"
            >
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center flex-shrink-0">
                  <span class="text-sm font-semibold text-blue-800 dark:text-blue-200">
                    {{ conversation.user?.name?.charAt(0) || '?' }}
                  </span>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex justify-between items-start">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                      {{ conversation.user?.name || 'Unknown User' }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      {{ formatTime(conversation.last_message?.created_at) }}
                    </p>
                  </div>
                  <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                    {{ conversation.last_message?.content || 'Image message' }}
                  </p>
                </div>
                <div v-if="conversation.unread_count > 0" 
                  class="w-5 h-5 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center">
                  {{ conversation.unread_count }}
                </div>
              </div>
            </button>
          </div>
          
          <!-- No Conversations -->
          <div v-if="!loading && !error && conversations.length === 0" 
            class="p-8 text-center text-gray-500 dark:text-gray-400">
            No conversations yet
          </div>
        </div>

        <!-- Chat Area -->
        <div class="md:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
          <!-- Chat Header -->
          <div v-if="selectedUser" class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                <span class="text-lg font-medium text-blue-800 dark:text-blue-200">
                  {{ selectedUser.name.charAt(0) }}
                </span>
              </div>
              <div>
                <div class="font-medium text-gray-900 dark:text-white">
                  {{ selectedUser.name }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                  {{ messages.length }} messages
                </div>
              </div>
            </div>
            <button 
              @click="deleteConversation"
              class="p-2 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
              title="Delete conversation"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>
          
          <!-- No Chat Selected -->
          <div v-if="!selectedUser" class="h-full flex items-center justify-center p-8">
            <div class="text-center text-gray-500 dark:text-gray-400">
              <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
              <p class="text-lg font-medium">Select a conversation to start chatting</p>
            </div>
          </div>
          
          <!-- Messages -->
          <div v-if="selectedUser" class="h-[500px] overflow-y-auto p-4 space-y-4">
            <div v-for="message in messages" :key="message.id" 
              class="flex" 
              :class="{ 'justify-end': message.sender_id === currentUser.id }">
              <div class="max-w-[70%] rounded-lg px-4 py-2"
                :class="{
                  'bg-blue-600 text-white': message.sender_id === currentUser.id,
                  'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white': message.sender_id !== currentUser.id
                }">
                <!-- Message Image -->
                <img 
                  v-if="message.image_path" 
                  :src="'/storage/' + message.image_path"
                  class="max-w-full rounded-lg mb-2"
                  @click="openImagePreview('/storage/' + message.image_path)"
                />
                <p v-if="message.content" class="text-sm">{{ message.content }}</p>
                <div class="flex justify-between items-center mt-1">
                  <p class="text-xs" 
                    :class="{
                      'text-blue-100': message.sender_id === currentUser.id,
                      'text-gray-500 dark:text-gray-400': message.sender_id !== currentUser.id
                    }">
                    {{ new Date(message.created_at).toLocaleTimeString() }}
                  </p>
                  <button 
                    v-if="message.sender_id === currentUser.id"
                    @click="deleteMessage(message)"
                    class="text-xs ml-2 p-1 rounded-full hover:bg-red-500/20 transition-colors"
                    :class="{
                      'text-blue-100 hover:text-red-200': message.sender_id === currentUser.id,
                      'text-gray-500 dark:text-gray-400': message.sender_id !== currentUser.id
                    }"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Message Input -->
          <div v-if="selectedUser" class="p-4 border-t border-gray-200 dark:border-gray-700">
            <form @submit.prevent="sendMessage" class="space-y-2">
              <!-- Image Preview -->
              <div v-if="selectedImage" class="relative inline-block">
                <img :src="selectedImagePreview" class="max-h-32 rounded-lg" />
                <button 
                  @click.prevent="removeSelectedImage"
                  class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>

              <div class="flex space-x-2">
                <input
                  v-model="newMessage"
                  type="text"
                  placeholder="Type a message..."
                  class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  :disabled="sending"
                />
                <!-- Image Upload Button -->
                <label class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors cursor-pointer">
                  <input
                    type="file"
                    accept="image/*"
                    class="hidden"
                    @change="handleImageSelect"
                    :disabled="sending"
                  />
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </label>
                <button
                  type="submit"
                  class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50"
                  :disabled="(!newMessage.trim() && !selectedImage) || sending"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                  </svg>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- New Chat Modal -->
    <Modal :show="showNewChatModal" @close="closeNewChatModal">
      <div class="p-6">
        <div class="flex justify-between items-start mb-4">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">New Chat</h2>
          <button @click="closeNewChatModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div class="space-y-4">
          <div>
            <input
              type="text"
              v-model="userSearchQuery"
              placeholder="Search users..."
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            />
          </div>
          
          <div class="max-h-96 overflow-y-auto">
            <div v-if="loadingUsers" class="text-center py-4">
              <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
            </div>
            <div v-else-if="filteredUsers.length === 0" class="text-center py-4 text-gray-500 dark:text-gray-400">
              No users found
            </div>
            <div v-else class="space-y-2">
              <button
                v-for="user in filteredUsers"
                :key="user.id"
                @click="startChat(user)"
                class="w-full p-3 flex items-center space-x-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
              >
                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center flex-shrink-0">
                  <span class="text-sm font-semibold text-blue-800 dark:text-blue-200">
                    {{ user.name.charAt(0) }}
                  </span>
                </div>
                <div class="flex-1 text-left">
                  <div class="font-medium text-gray-900 dark:text-white">{{ user.name }}</div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</div>
                </div>
              </button>
            </div>
          </div>
        </div>
      </div>
    </Modal>

    <!-- Image Preview Modal -->
    <Modal :show="showImagePreview" @close="closeImagePreview">
      <div class="p-6">
        <div class="flex justify-between items-start mb-4">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white">Image Preview</h2>
          <button @click="closeImagePreview" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <img :src="previewImageUrl" class="max-w-full rounded-lg" />
      </div>
    </Modal>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted, computed, nextTick } from 'vue';
import axios from 'axios';
import Modal from '@/Components/Modal.vue';

export default {
  components: {
    Modal
  },
  setup() {
    const conversations = ref([]);
    const selectedUser = ref(null);
    const messages = ref([]);
    const newMessage = ref('');
    const loading = ref(true);
    const error = ref(null);
    const searchQuery = ref('');
    const showNewChatModal = ref(false);
    const userSearchQuery = ref('');
    const users = ref([]);
    const loadingUsers = ref(false);
    const currentUser = ref(null);
    const sending = ref(false);
    let pollInterval = null;
    const selectedImage = ref(null);
    const selectedImagePreview = ref(null);
    const showImagePreview = ref(false);
    const previewImageUrl = ref(null);

    const fetchUsers = async () => {
      loadingUsers.value = true;
      try {
        const response = await axios.get('/api/users');
        users.value = response.data;
      } catch (err) {
        console.error('Error fetching users:', err);
      } finally {
        loadingUsers.value = false;
      }
    };

    const filteredUsers = computed(() => {
      if (!userSearchQuery.value) return users.value;
      const query = userSearchQuery.value.toLowerCase();
      return users.value.filter(user => 
        user.name.toLowerCase().includes(query) || 
        user.email.toLowerCase().includes(query)
      );
    });

    const startChat = (user) => {
      selectedUser.value = user;
      showNewChatModal.value = false;
      fetchMessages(user.id);
    };

    const closeNewChatModal = () => {
      showNewChatModal.value = false;
      userSearchQuery.value = '';
    };

    const fetchCurrentUser = async () => {
      try {
        const response = await axios.get('/api/user');
        currentUser.value = response.data;
      } catch (err) {
        error.value = 'Failed to load user data';
      }
    };

    const fetchConversations = async () => {
      try {
        const response = await axios.get('/api/messages');
        conversations.value = response.data;
      } catch (err) {
        error.value = 'Failed to load conversations';
      } finally {
        loading.value = false;
      }
    };

    const selectConversation = async (user) => {
      selectedUser.value = user;
      messages.value = [];
      await fetchMessages();
    };

    const fetchMessages = async () => {
      if (!selectedUser.value) return;
      
      try {
        const response = await axios.get(`/api/messages/${selectedUser.value.id}`);
        messages.value = response.data;
        scrollToBottom();
      } catch (err) {
        error.value = 'Failed to load messages';
      }
    };

    const handleImageSelect = (event) => {
      const file = event.target.files[0];
      if (file) {
        selectedImage.value = file;
        selectedImagePreview.value = URL.createObjectURL(file);
      }
    };

    const removeSelectedImage = () => {
      selectedImage.value = null;
      selectedImagePreview.value = null;
    };

    const openImagePreview = (url) => {
      previewImageUrl.value = url;
      showImagePreview.value = true;
    };

    const closeImagePreview = () => {
      showImagePreview.value = false;
      previewImageUrl.value = null;
    };

    const sendMessage = async () => {
      if ((!newMessage.value.trim() && !selectedImage.value) || !selectedUser.value) return;
      
      sending.value = true;
      try {
        const formData = new FormData();
        if (newMessage.value.trim()) {
          formData.append('content', newMessage.value);
        }
        if (selectedImage.value) {
          formData.append('image', selectedImage.value);
        }

        const response = await axios.post(`/api/messages/${selectedUser.value.id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        messages.value.push(response.data);
        newMessage.value = '';
        removeSelectedImage();
        scrollToBottom();
      } catch (err) {
        error.value = 'Failed to send message';
      } finally {
        sending.value = false;
      }
    };

    const scrollToBottom = () => {
      nextTick(() => {
        const container = document.querySelector('.overflow-y-auto');
        if (container) {
          container.scrollTop = container.scrollHeight;
        }
      });
    };

    const startPolling = () => {
      pollInterval = setInterval(async () => {
        if (selectedUser.value) {
          await fetchMessages();
        }
        await fetchConversations();
      }, 5000);
    };

    const stopPolling = () => {
      if (pollInterval) {
        clearInterval(pollInterval);
      }
    };

    const formatTime = (timestamp) => {
      const date = new Date(timestamp);
      return date.toLocaleTimeString();
    };

    const deleteMessage = async (message) => {
      if (!confirm('Are you sure you want to delete this message?')) return;
      
      try {
        await axios.delete(`/messages/${message.id}`);
        messages.value = messages.value.filter(m => m.id !== message.id);
      } catch (err) {
        error.value = 'Failed to delete message';
      }
    };

    const deleteConversation = () => {
      console.log('Delete conversation clicked');
      if (!confirm('Are you sure you want to delete this conversation? This will only remove it from your view.')) {
        console.log('Delete cancelled');
        return;
      }
      
      console.log('Deleting conversation with user:', selectedUser.value?.id);
      // Remove the conversation from the list
      conversations.value = conversations.value.filter(c => c.user?.id !== selectedUser.value.id);
      
      // Clear the messages
      messages.value = [];
      
      // Clear the selected user
      selectedUser.value = null;
      console.log('Conversation deleted');
    };

    onMounted(() => {
      fetchCurrentUser();
      fetchConversations();
      fetchUsers();
      startPolling();
    });

    return {
      conversations,
      selectedUser,
      messages,
      newMessage,
      loading,
      error,
      searchQuery,
      showNewChatModal,
      userSearchQuery,
      users,
      loadingUsers,
      filteredUsers,
      currentUser,
      sending,
      startChat,
      closeNewChatModal,
      fetchCurrentUser,
      fetchConversations,
      selectConversation,
      fetchMessages,
      sendMessage,
      scrollToBottom,
      startPolling,
      stopPolling,
      selectedImage,
      selectedImagePreview,
      showImagePreview,
      previewImageUrl,
      handleImageSelect,
      removeSelectedImage,
      openImagePreview,
      closeImagePreview,
      formatTime,
      deleteMessage,
      deleteConversation
    };
  }
};
</script>

<style scoped>
.overflow-y-auto::-webkit-scrollbar {
  width: 4px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

.dark .overflow-y-auto::-webkit-scrollbar-track {
  background: #374151;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb {
  background: #4b5563;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #6b7280;
}
</style> 