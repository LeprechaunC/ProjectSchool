<template>
    <div class="toolbar-container" :class="{ open: isOpen }">
      <!-- Toggle Button -->
      <button class="toggle-btn" @click="isOpen = !isOpen">
        <span v-if="isOpen">➖ Close</span>
        <span v-else>➕ Filters</span>
      </button>
  
      <!-- Toolbar Content -->
      <div v-if="isOpen" class="toolbar-content">
        <h3 class="text-lg font-semibold mb-2">Filters & Labels</h3>
  
        <!-- Priority Filter -->
        <div class="filter-group">
          <label class="filter-label">Priority</label>
          <select v-model="selectedPriority" class="filter-input">
            <option value="">All</option>
            <option value="high">🔴 High</option>
            <option value="medium">🟡 Medium</option>
            <option value="low">🟢 Low</option>
          </select>
        </div>
  
        <!-- Type Filter -->
        <div class="filter-group">
          <label class="filter-label">Goal Type</label>
          <select v-model="selectedType" class="filter-input">
            <option value="">All</option>
            <option value="project">🚀 Project</option>
            <option value="task">✅ Task</option>
            <option value="challenge">🏆 Challenge</option>
          </select>
        </div>
  
        <!-- Due Date Filter -->
        <div class="filter-group">
          <label class="filter-label">Due Date</label>
          <input type="date" v-model="selectedDate" class="filter-input" />
        </div>
  
     
       
        </div>
  
        <!-- Apply Filters Button -->
        <button @click="applyFilters" class="apply-btn">✅ Apply Filters</button>
      </div>
 
  </template>
  
  <script>
  export default {
    data() {
      return {
        isOpen: false, // Sidebar starts open
        selectedPriority: "",
        selectedType: "",
        selectedDate: "",
        selectedLabels: [],
        newLabel: "",
      };
    },
    methods: {
      addLabel() {
        if (this.newLabel.trim() && !this.selectedLabels.includes(this.newLabel)) {
          this.selectedLabels.push(this.newLabel.trim());
          this.newLabel = "";
        }
      },
      removeLabel(index) {
        this.selectedLabels.splice(index, 1);
      },
      applyFilters() {
        this.$emit("filter-applied", {
          priority: this.selectedPriority,
          type: this.selectedType,
          dueDate: this.selectedDate,
          labels: this.selectedLabels,
        });
      },
    },
  };
  </script>
  
  <style scoped>
  /* Sidebar Styling */
  .toolbar-container {
  position: fixed;
  top: 50px;
  left: 0; /* Change from right: 0 to left: 0 */
  width: 250px;
  background: #f9fafb;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); /* Shadow on the right side */
  padding: 15px;
  transition: transform 0.3s ease-in-out;
  transform: translateX(-100%); /* Hide to the left */
}
.toolbar-container.open {
  transform: translateX(0);
}

/* Adjust Toggle Button */
.toggle-btn {
  position: absolute;
  right: -80px; /* Move toggle to the right of the sidebar */
  top: 10px;
  background: #4f46e5;
  color: white;
  padding: 5px 10px;
  border-radius: 0 5px 5px 0; /* Adjust for left sidebar */
  cursor: pointer;
}

  
  /* Form Elements */
  .filter-group {
    margin-bottom: 15px;
  }
  .filter-label {
    font-size: 14px;
    font-weight: bold;
  }
  .filter-input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
  }
  
  /* Labels Styling */
  .labels {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-top: 5px;
  }
  .label-tag {
    background: #e0e7ff;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 12px;
    display: flex;
    align-items: center;
  }
  .label-tag button {
    margin-left: 5px;
    background: transparent;
    border: none;
    cursor: pointer;
  }
  
  /* Buttons */
  .add-label-btn {
    background: #6366f1;
    color: white;
    padding: 5px;
    width: 100%;
    border-radius: 5px;
    cursor: pointer;
  }
  .apply-btn {
    background: #22c55e;
    color: white;
    padding: 8px;
    width: 100%;
    border-radius: 5px;
    cursor: pointer;
  }
  </style>
  
  