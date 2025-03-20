<template>
  <div v-if="isVisible" class="fullscreen-modal-overlay" @click.self="closeModal">
    <div class="fullscreen-modal">
      <button @click="closeModal" class="close-button">&times;</button>
      
      <h2 class="text-xl font-semibold mb-4">{{ goal.title }}</h2>
      
      <div v-if="isEditing">
        <input v-model="goal.title" class="border p-2 mb-2 w-full" placeholder="Goal Title" />
        <textarea v-model="goal.description" class="border p-2 mb-2 w-full" placeholder="Goal Description"></textarea>
        <input v-model="goal.start_time" type="datetime-local" class="border p-2 mb-2 w-full" />
        <input v-model="goal.end_time" type="datetime-local" class="border p-2 mb-2 w-full" />
        
        <div class="mt-4 flex justify-end space-x-2">
          <button @click="saveGoal" class="px-4 py-2 bg-green-600 text-white rounded">Save</button>
          <button @click="cancelEdit" class="px-4 py-2 bg-gray-600 text-white rounded">Cancel</button>
        </div>
      </div>
      
      <div v-else>
        <p><strong>Description:</strong> {{ goal.description }}</p>
        <p><strong>Start Time:</strong> {{ formatDate(goal.start_time) }}</p>
        <p><strong>End Time:</strong> {{ formatDate(goal.end_time) }}</p>
        
        <div class="mt-4 flex justify-end space-x-2">
          <button @click="startEdit" class="px-4 py-2 bg-blue-600 text-white rounded">Edit</button>
          <button @click="deleteGoal" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
          <button @click="closeModal" class="px-4 py-2 bg-gray-600 text-white rounded">Back</button>
        </div>
      </div>
    </div>
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

    // Watch for changes in selectedGoal prop
    watch(() => props.selectedGoal, (newGoal) => {
      goal.value = { ...newGoal };
    }, { immediate: true });

    // Format the date for display
    const formatDate = (date) => {
      const d = new Date(date);
      return `${d.getMonth() + 1}/${d.getDate()}/${d.getFullYear()} ${d.getHours()}:${d.getMinutes()}`;
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
          // Update existing goal
          await axios.patch(`/api/goals/${goal.value.id}`, goal.value);
          alert("Goal updated successfully!");
        } else {
          // Create new goal
          await axios.post("/api/goals", goal.value);
          alert("Goal created successfully!");
        }
        isEditing.value = false;
        emit("updateGoal", goal.value);
      } catch (error) {
        console.error("Error saving goal:", error);
      }
    };

    // Delete the goal
    const deleteGoal = async () => {
      if (confirm("Are you sure you want to delete this goal?")) {
        try {
          await axios.delete(`/api/goals/${goal.value.id}`);
          alert("Goal deleted successfully!");
          emit("delete", goal.value.id);
          closeModal();
        } catch (error) {
          console.error("Error deleting goal:", error);
        }
      }
    };

    // Close the modal
    const closeModal = () => {
      emit("closeModal");
    };

    return { goal, isEditing, startEdit, cancelEdit, saveGoal, deleteGoal, formatDate, closeModal };
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
  background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
  display: flex;
  justify-content: center;
  align-items: center; /* Center content vertically */
  z-index: 9999; /* Ensure it's on top of other content */
}

.fullscreen-modal {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  width: 80%;
  max-width: 600px;
  position: relative;
  overflow-y: auto;
  z-index: 10000;
}

/* Close Button */
.close-button {
  position: absolute;
  top: 10px;
  right: 10px;
  background: none;
  border: none;
  font-size: 24px;
  color: #333;
  cursor: pointer;
}


</style>
