<template>
  <div v-if="isVisible" class="fullscreen-modal-overlay" @click.self="closeModal">
    <div class="fullscreen-modal">
      <button @click="closeModal" class="close-button">&times;</button>
      
      <h2 class="text-xl font-semibold mb-4">{{ goal.title }}</h2>
      
      <div v-if="isEditing">
        <div class="form-group mb-4">
          <label class="form-label">Title</label>
          <input v-model="goal.title" class="form-input" placeholder="Goal Title" />
        </div>
        
        <div class="form-group mb-4">
          <label class="form-label">Description</label>
          <textarea v-model="goal.description" class="form-textarea" placeholder="Goal Description"></textarea>
        </div>
        
        <div class="form-group mb-4">
          <label class="form-label">Priority</label>
          <div class="priority-selector">
            <button 
              v-for="priority in priorities" 
              :key="priority.value"
              @click="goal.priority = priority.value"
              class="priority-button"
              :class="{ 'priority-selected': goal.priority === priority.value }"
            >
              <span class="priority-icon">{{ priority.icon }}</span>
              <span class="priority-text">{{ priority.label }}</span>
            </button>
          </div>
        </div>
        
        <div class="form-group mb-4">
          <label class="form-label">Start Time</label>
          <input v-model="goal.start_time" type="datetime-local" class="form-input" />
        </div>
        
        <div class="form-group mb-4">
          <label class="form-label">End Time</label>
          <input v-model="goal.end_time" type="datetime-local" class="form-input" />
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
          <button @click="cancelEdit" class="btn btn-secondary">Cancel</button>
          <button @click="saveGoal" class="btn btn-primary">Save</button>
        </div>
      </div>
      
      <div v-else>
        <div class="goal-details">
          <div class="goal-detail-item">
            <span class="goal-detail-label">Description:</span>
            <p class="goal-detail-value">{{ goal.description || 'No description provided' }}</p>
          </div>
          
          <div class="goal-detail-item">
            <span class="goal-detail-label">Priority:</span>
            <div class="priority-badge" :class="getPriorityClass(goal.priority)">
              {{ getPriorityLabel(goal.priority) }}
            </div>
          </div>
          
          <div class="goal-detail-item">
            <span class="goal-detail-label">Start Time:</span>
            <p class="goal-detail-value">{{ formatDate(goal.start_time) }}</p>
          </div>
          
          <div class="goal-detail-item">
            <span class="goal-detail-label">End Time:</span>
            <p class="goal-detail-value">{{ formatDate(goal.end_time) }}</p>
          </div>
          
          <div class="goal-detail-item">
            <span class="goal-detail-label">Status:</span>
            <div class="flex items-center space-x-2">
              <div class="status-badge" :class="goal.done ? 'status-completed' : 'status-pending'">
                {{ goal.done ? 'Completed' : 'Pending' }}
              </div>
              <button 
                @click="toggleGoalStatus" 
                class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                :class="goal.done ? 'text-green-500' : 'text-red-500'"
              >
                {{ goal.done ? '✓' : '✖' }}
              </button>
            </div>
          </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
          <button @click="confirmDelete" class="btn btn-danger">Delete</button>
          <button @click="startEdit" class="btn btn-primary">Edit</button>
          <button @click="closeModal" class="btn btn-secondary">Back</button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Confirmation Modal -->
  <div v-if="showConfirmModal" class="confirmation-modal-overlay" @click.self="closeConfirmModal">
    <div class="confirmation-modal">
      <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
      <p class="mb-6">Are you sure you want to delete this goal? This action cannot be undone.</p>
      <div class="flex justify-end space-x-3">
        <button @click="closeConfirmModal" class="btn btn-secondary">Cancel</button>
        <button @click="deleteGoal" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
  
  <!-- Notification -->
  <div v-if="notification.show" class="notification" :class="notification.type">
    <div class="notification-content">
      <span class="notification-icon">{{ notification.icon }}</span>
      <span class="notification-message">{{ notification.message }}</span>
    </div>
    <button @click="closeNotification" class="notification-close">&times;</button>
  </div>
</template>

<script>
import { defineComponent, ref, watch, onMounted } from "vue";
import axios from "axios";

const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
axios.defaults.headers.common['X-XSRF-TOKEN'] = token; // Set CSRF token globally

export default defineComponent({
  props: {
    isVisible: Boolean,
    selectedGoal: Object,
  },
  setup(props, { emit }) {
    const goal = ref({});
    const isEditing = ref(false);
    const showConfirmModal = ref(false);
    const notification = ref({
      show: false,
      message: '',
      type: 'success',
      icon: '✓'
    });
    
    const priorities = [
      { value: "high", label: "High", icon: "🔴" },
      { value: "medium", label: "Medium", icon: "🟡" },
      { value: "low", label: "Low", icon: "🟢" }
    ];

    // Watch for changes in selectedGoal prop
    watch(() => props.selectedGoal, (newGoal) => {
      goal.value = { ...newGoal };
    }, { immediate: true });

    // Format the date for display
    const formatDate = (date) => {
      if (!date) return 'Not set';
      const d = new Date(date);
      return `${d.getMonth() + 1}/${d.getDate()}/${d.getFullYear()} ${d.getHours()}:${d.getMinutes().toString().padStart(2, '0')}`;
    };
    
    // Get priority label
    const getPriorityLabel = (priority) => {
      const found = priorities.find(p => p.value === priority);
      return found ? found.label : 'Medium';
    };
    
    // Get priority class for styling
    const getPriorityClass = (priority) => {
      return `priority-${priority || 'medium'}`;
    };
    
    // Show notification
    const showNotification = (message, type = 'success') => {
      notification.value = {
        show: true,
        message,
        type,
        icon: type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ'
      };
      
      // Auto-hide notification after 3 seconds
      setTimeout(() => {
        closeNotification();
      }, 3000);
    };
    
    // Close notification
    const closeNotification = () => {
      notification.value.show = false;
    };

    // Start editing the goal
    const startEdit = () => {
      isEditing.value = true;
    };

    // Cancel editing and reset changes
    const cancelEdit = () => {
      isEditing.value = false;
      goal.value = { ...props.selectedGoal }; // Reset changes
    };

    // Save the goal (Create or Update)
    const saveGoal = async () => {
      try {
        if (goal.value.id) {
          console.log('Updating goal with ID:', goal.value.id);
          console.log('Goal data being sent:', goal.value);
          
          // Update existing goal
          const response = await axios.patch(`/api/goals/${goal.value.id}`, goal.value);
          console.log('Update response:', response.data);
          
          showNotification('Goal updated successfully!');
        } else {
          // Create new goal
          await axios.post("/api/goals", goal.value);
          showNotification('Goal created successfully!');
        }
        isEditing.value = false;
        emit("updateGoal", goal.value);
      } catch (error) {
        console.error("Error saving goal:", error);
        
        // More detailed error handling
        if (error.response) {
          // The request was made and the server responded with a status code
          // that falls out of the range of 2xx
          console.error("Error response data:", error.response.data);
          console.error("Error response status:", error.response.status);
          
          if (error.response.status === 404) {
            showNotification('Goal not found. It may have been deleted.', 'error');
          } else {
            showNotification(`Error: ${error.response.data.message || 'Failed to save goal'}`, 'error');
          }
        } else if (error.request) {
          // The request was made but no response was received
          console.error("Error request:", error.request);
          showNotification('Network error. Please check your connection.', 'error');
        } else {
          // Something happened in setting up the request that triggered an Error
          console.error("Error message:", error.message);
          showNotification(`Error: ${error.message}`, 'error');
        }
      }
    };

    // Show confirmation modal for delete
    const confirmDelete = () => {
      showConfirmModal.value = true;
    };
    
    // Close confirmation modal
    const closeConfirmModal = () => {
      showConfirmModal.value = false;
    };

    // Delete the goal
    const deleteGoal = async () => {
      try {
        await axios.delete(`/api/goals/${goal.value.id}`);
        showNotification('Goal deleted successfully!');
        emit("delete", goal.value.id);
        closeConfirmModal();
        closeModal();
      } catch (error) {
        console.error("Error deleting goal:", error);
        showNotification('Error deleting goal. Please try again.', 'error');
      }
    };

    // Close the modal
    const closeModal = () => {
      emit("closeModal");
    };

    const toggleGoalStatus = async () => {
      try {
        const newStatus = !goal.value.done;
        await axios.patch(`/api/goals/${goal.value.id}/done`, { done: newStatus });
        goal.value.done = newStatus;
        showNotification(`Goal marked as ${newStatus ? 'completed' : 'pending'}!`);
        emit("updateGoal", goal.value);
      } catch (error) {
        console.error("Error updating goal status:", error);
        showNotification("Failed to update goal status", "error");
      }
    };

    return { 
      goal, 
      isEditing, 
      priorities,
      showConfirmModal,
      notification,
      startEdit, 
      cancelEdit, 
      saveGoal, 
      confirmDelete,
      closeConfirmModal,
      deleteGoal, 
      formatDate, 
      closeModal,
      getPriorityLabel,
      getPriorityClass,
      showNotification,
      closeNotification,
      toggleGoalStatus
    };
  },
});
</script>

<style scoped>
/* Fullscreen Modal Styles */
.fullscreen-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  cursor: pointer; /* Add cursor pointer to indicate clickable area */
}

.fullscreen-modal {
  background-color: #fff;
  padding: 24px;
  border-radius: 12px;
  width: 90%;
  max-width: 600px;
  position: relative;
  overflow-y: auto;
  z-index: 10000;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  cursor: default; /* Reset cursor for modal content */
}

/* Confirmation Modal Styles */
.confirmation-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 10001;
}

.confirmation-modal {
  background-color: #fff;
  padding: 24px;
  border-radius: 12px;
  width: 90%;
  max-width: 400px;
  position: relative;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Notification Styles */
.notification {
  position: fixed;
  bottom: 24px;
  right: 24px;
  padding: 16px 20px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-width: 300px;
  max-width: 400px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  z-index: 10002;
  animation: slideIn 0.3s ease-out;
}

.notification-content {
  display: flex;
  align-items: center;
}

.notification-icon {
  margin-right: 12px;
  font-size: 18px;
}

.notification-message {
  font-size: 14px;
  font-weight: 500;
}

.notification-close {
  background: none;
  border: none;
  font-size: 18px;
  color: inherit;
  opacity: 0.7;
  cursor: pointer;
  margin-left: 12px;
  padding: 0;
}

.notification-close:hover {
  opacity: 1;
}

.notification.success {
  background-color: #d1fae5;
  color: #065f46;
  border-left: 4px solid #10b981;
}

.notification.error {
  background-color: #fee2e2;
  color: #b91c1c;
  border-left: 4px solid #ef4444;
}

.notification.info {
  background-color: #dbeafe;
  color: #1e40af;
  border-left: 4px solid #3b82f6;
}

@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

/* Close Button */
.close-button {
  position: absolute;
  top: 16px;
  right: 16px;
  background: none;
  border: none;
  font-size: 24px;
  color: #6b7280;
  cursor: pointer;
  transition: color 0.2s;
}

.close-button:hover {
  color: #374151;
}

/* Form Styles */
.form-group {
  margin-bottom: 1rem;
}

.form-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 0.5rem;
}

.form-input,
.form-textarea {
  width: 100%;
  padding: 0.625rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  transition: all 0.2s;
}

.form-input:focus,
.form-textarea:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
  outline: none;
}

.form-textarea {
  min-height: 100px;
  resize: vertical;
}

/* Priority Selector */
.priority-selector {
  display: flex;
  gap: 0.5rem;
}

.priority-button {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.625rem;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  background: white;
  transition: all 0.2s;
  cursor: pointer;
  font-weight: 500;
}

.priority-button:hover {
  background: #f9fafb;
  border-color: #d1d5db;
}

.priority-selected {
  background: #eff6ff;
  border-color: #3b82f6;
  color: #1e40af;
}

.priority-icon {
  margin-right: 0.5rem;
  font-size: 1.125rem;
}

/* Goal Details */
.goal-details {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.goal-detail-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.goal-detail-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: #6b7280;
}

.goal-detail-value {
  font-size: 1rem;
  color: #374151;
}

/* Priority Badge */
.priority-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 500;
  width: fit-content;
}

.priority-high {
  background-color: #fee2e2;
  color: #b91c1c;
}

.priority-medium {
  background-color: #fef3c7;
  color: #b45309;
}

.priority-low {
  background-color: #d1fae5;
  color: #065f46;
}

/* Status Badge */
.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 500;
  width: fit-content;
}

.status-completed {
  background-color: #d1fae5;
  color: #065f46;
}

.status-pending {
  background-color: #fef3c7;
  color: #b45309;
}

/* Buttons */
.btn {
  padding: 0.625rem 1.25rem;
  border-radius: 0.375rem;
  font-weight: 500;
  transition: all 0.2s;
  cursor: pointer;
}

.btn-primary {
  background-color: #3b82f6;
  color: white;
  border: none;
}

.btn-primary:hover {
  background-color: #2563eb;
}

.btn-secondary {
  background-color: white;
  color: #4b5563;
  border: 1px solid #e5e7eb;
}

.btn-secondary:hover {
  background-color: #f9fafb;
  border-color: #d1d5db;
}

.btn-danger {
  background-color: #ef4444;
  color: white;
  border: none;
}

.btn-danger:hover {
  background-color: #dc2626;
}

/* Dark mode styles */
:deep(.dark) .fullscreen-modal,
:deep(.dark) .confirmation-modal {
  background-color: #1f2937;
  color: #f3f4f6;
}

:deep(.dark) .close-button {
  color: #9ca3af;
}

:deep(.dark) .close-button:hover {
  color: #f3f4f6;
}

:deep(.dark) .form-label {
  color: #e5e7eb;
}

:deep(.dark) .form-input,
:deep(.dark) .form-textarea {
  background-color: #374151;
  border-color: #4b5563;
  color: #f3f4f6;
}

:deep(.dark) .form-input:focus,
:deep(.dark) .form-textarea:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}

:deep(.dark) .priority-button {
  background-color: #374151;
  border-color: #4b5563;
  color: #e5e7eb;
}

:deep(.dark) .priority-button:hover {
  background-color: #4b5563;
  border-color: #6b7280;
}

:deep(.dark) .priority-selected {
  background-color: #1e40af;
  border-color: #3b82f6;
  color: #e5e7eb;
}

:deep(.dark) .goal-detail-label {
  color: #9ca3af;
}

:deep(.dark) .goal-detail-value {
  color: #f3f4f6;
}

:deep(.dark) .btn-secondary {
  background-color: #374151;
  border-color: #4b5563;
  color: #e5e7eb;
}

:deep(.dark) .btn-secondary:hover {
  background-color: #4b5563;
  border-color: #6b7280;
}

:deep(.dark) .notification.success {
  background-color: #064e3b;
  color: #d1fae5;
  border-left-color: #10b981;
}

:deep(.dark) .notification.error {
  background-color: #7f1d1d;
  color: #fee2e2;
  border-left-color: #ef4444;
}

:deep(.dark) .notification.info {
  background-color: #1e3a8a;
  color: #dbeafe;
  border-left-color: #3b82f6;
}
</style>
