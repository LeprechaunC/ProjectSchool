<template>
  
    <div v-if="isVisible" class="fullscreen-modal-overlay" @click.self="closeModal">
      <div class="fullscreen-modal">
        <button @click="closeModal" class="close-button">×</button>
  
        <h2 class="text-xl font-semibold mb-4">{{ selectedGoal?.title }}</h2>
  
        <!-- Editable Goal Fields for Editing Mode -->
        <div v-if="isEditing">
          <input v-model="selectedGoal.title" class="border p-2 mb-2 w-full" placeholder="Goal Title" />
          <textarea v-model="selectedGoal.description" class="border p-2 mb-2 w-full" placeholder="Goal Description"></textarea>
          <input v-model="selectedGoal.start_time" type="datetime-local" class="border p-2 mb-2 w-full" />
          <input v-model="selectedGoal.end_time" type="datetime-local" class="border p-2 mb-2 w-full" />
  
          <div class="mt-4 flex justify-end space-x-2">
            <button @click="saveGoal" class="px-4 py-2 bg-green-600 text-white rounded">Save</button>
            <button @click="cancelEdit" class="px-4 py-2 bg-gray-600 text-white rounded">Cancel</button>
          </div>
        </div>
  
        <!-- Non-editable Goal Details -->
        <div v-else>
          <p><strong>Description:</strong> {{ selectedGoal.description }}</p>
          <p><strong>Start Time:</strong> {{ formatDate(selectedGoal.start_time) }}</p>
          <p><strong>End Time:</strong> {{ formatDate(selectedGoal.end_time) }}</p>
  
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
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";

export default defineComponent({
  props: {
    isVisible: Boolean,
    selectedGoal: Object,
  },
  setup(props, { emit }) {
    const goal = ref({});
    const isEditing = ref(false);

    const fetchGoal = async () => {
      try {
        const response = await axios.get(`http://localhost:8000/api/goals/${props.selectedGoal.id}`);
        goal.value = response.data;
      } catch (error) {
        console.error("Error fetching goal details:", error);
      }
    };

    const formatDate = (date) => {
      const d = new Date(date);
      return `${d.getMonth() + 1}/${d.getDate()}/${d.getFullYear()} ${d.getHours()}:${d.getMinutes()}`;
    };

    onMounted(fetchGoal);

    const saveGoal = async () => {
      try {
        await axios.put(`http://localhost:8000/api/goals/${props.selectedGoal.id}/update`, goal.value);
        isEditing.value = false;
        alert("Goal updated successfully!");
        emit("updateGoal", goal.value);
      } catch (error) {
        console.error("Error updating goal:", error);
      }
    };

    const deleteGoal = async () => {
      if (confirm("Are you sure you want to delete this goal?")) {
        try {
          await axios.delete(`http://localhost:8000/api/goals/${props.selectedGoal.id}`);
          alert("Goal deleted successfully!");
          emit("delete", props.selectedGoal.id);
        } catch (error) {
          console.error("Error deleting goal:", error);
        }
      }
    };

    const closeModal = () => {
      emit("closeModal");
    };

    return { goal, isEditing, fetchGoal, saveGoal, deleteGoal, formatDate, closeModal };
  },
});
</script>
  

  
  <style scoped>
  .fullscreen-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }
  
  .fullscreen-modal {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    width: 80%;
    max-width: 800px;
    position: relative;
  }
  
  .close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 24px;
    background: none;
    border: none;
    color: gray;
    cursor: pointer;
  }
  </style>
  