<template>
  <div class="sidebar-container" :class="{ 'sidebar-open': isOpen }">
    <!-- Toggle Button -->
    <button 
      class="toggle-button" 
      @click="isOpen = !isOpen"
      :class="{ 'toggle-open': isOpen }"
    >
      <svg 
        class="w-5 h-5 transition-transform duration-200" 
        :class="{ 'rotate-180': isOpen }"
        fill="none" 
        stroke="currentColor" 
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
      <span class="ml-2">{{ isOpen ? 'Close Filters' : 'Open Filters' }}</span>
    </button>

    <!-- Sidebar Content -->
    <div class="sidebar-content">
      <div class="p-5">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-gray-900 dark:text-white">Filters & Labels</h3>
          <span class="text-sm text-gray-500 dark:text-gray-400">{{ selectedFiltersCount }} active</span>
        </div>

        <!-- Priority Filter -->
        <div class="filter-section">
          <label class="filter-label">Priority Level</label>
          <div class="priority-options">
            <button 
              v-for="priority in priorities" 
              :key="priority.value"
              @click="togglePriority(priority.value)"
              class="priority-button"
              :class="{ 'priority-selected': selectedPriority === priority.value }"
            >
              <span class="priority-icon">{{ priority.icon }}</span>
              <span class="priority-text">{{ priority.label }}</span>
            </button>
          </div>
        </div>

        <!-- Type Filter -->
        <div class="filter-section">
          <label class="filter-label">Goal Type</label>
          <div class="type-options">
            <button 
              v-for="type in types" 
              :key="type.value"
              @click="toggleType(type.value)"
              class="type-button"
              :class="{ 'type-selected': selectedType === type.value }"
            >
              <span class="type-icon">{{ type.icon }}</span>
              <span class="type-text">{{ type.label }}</span>
            </button>
          </div>
        </div>

        <!-- Date Range Filter -->
        <div class="filter-section">
          <label class="filter-label">Date Range</label>
          <div class="date-range">
            <div class="date-input-group">
              <label class="date-label">From</label>
              <input 
                type="date" 
                v-model="dateRange.start" 
                class="date-input"
              />
            </div>
            <div class="date-input-group">
              <label class="date-label">To</label>
              <input 
                type="date" 
                v-model="dateRange.end" 
                class="date-input"
              />
            </div>
          </div>
        </div>

        <!-- Labels Section -->
        <div class="filter-section">
          <div class="flex items-center justify-between mb-2">
            <label class="filter-label">Labels</label>
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ selectedLabels.length }} selected</span>
          </div>
          <div class="labels-container">
            <div class="labels-input-group">
              <input 
                type="text" 
                v-model="newLabel" 
                @keyup.enter="addLabel"
                placeholder="Add new label..."
                class="label-input"
              />
              <button 
                @click="addLabel"
                class="add-label-button"
                :disabled="!newLabel.trim()"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
              </button>
            </div>
            <div class="selected-labels" v-if="selectedLabels.length > 0">
              <div 
                v-for="(label, index) in selectedLabels" 
                :key="index"
                class="label-tag"
              >
                <span>{{ label }}</span>
                <button 
                  @click="removeLabel(index)"
                  class="remove-label-button"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
            <div v-else class="text-sm text-gray-500 dark:text-gray-400 italic mt-2">
              No labels added yet
            </div>
          </div>
        </div>

        <!-- Apply Filters Button -->
        <div class="mt-8 flex flex-col gap-3">
          <button 
            @click="applyFilters"
            class="apply-filters-button"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            Apply Filters
          </button>
          
          <button 
            @click="resetFilters"
            class="reset-filters-button"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Reset Filters
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isOpen: true,
      selectedPriority: "",
      selectedType: "",
      dateRange: {
        start: "",
        end: ""
      },
      selectedLabels: [],
      newLabel: "",
      priorities: [
        { value: "high", label: "High", icon: "🔴" },
        { value: "medium", label: "Medium", icon: "🟡" },
        { value: "low", label: "Low", icon: "🟢" }
      ],
      types: [
        { value: "project", label: "Project", icon: "🚀" },
        { value: "task", label: "Task", icon: "✅" },
        { value: "challenge", label: "Challenge", icon: "🏆" }
      ]
    };
  },
  computed: {
    selectedFiltersCount() {
      let count = 0;
      if (this.selectedPriority) count++;
      if (this.selectedType) count++;
      if (this.dateRange.start || this.dateRange.end) count++;
      if (this.selectedLabels.length > 0) count++;
      return count;
    }
  },
  methods: {
    togglePriority(value) {
      this.selectedPriority = this.selectedPriority === value ? "" : value;
    },
    toggleType(value) {
      this.selectedType = this.selectedType === value ? "" : value;
    },
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
        dateRange: this.dateRange,
        labels: this.selectedLabels,
      });
    },
    resetFilters() {
      this.selectedPriority = "";
      this.selectedType = "";
      this.dateRange = { start: "", end: "" };
      this.selectedLabels = [];
      this.applyFilters();
    }
  },
};
</script>

<style scoped>
.sidebar-container {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  background: white;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  transition: transform 0.3s ease-in-out;
  z-index: 50;
  width: 300px;
  transform: translateX(-100%);
}

.sidebar-open {
  transform: translateX(0);
}

.toggle-button {
  position: absolute;
  right: -48px;
  top: 20px;
  background: #3b82f6;
  color: white;
  padding: 8px 12px;
  border-radius: 0 8px 8px 0;
  display: flex;
  align-items: center;
  font-weight: 500;
  transition: all 0.2s;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.toggle-button:hover {
  background: #2563eb;
}

.toggle-open {
  background: #2563eb;
}

.sidebar-content {
  height: 100%;
  overflow-y: auto;
  background: white;
  border-right: 1px solid #e5e7eb;
}

.filter-section {
  margin-bottom: 1.75rem;
  padding-bottom: 1.75rem;
  border-bottom: 1px solid #f3f4f6;
}

.filter-section:last-child {
  border-bottom: none;
}

.filter-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 0.75rem;
}

.priority-options,
.type-options {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.priority-button,
.type-button {
  display: flex;
  align-items: center;
  padding: 0.625rem;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  background: white;
  transition: all 0.2s;
  cursor: pointer;
  font-weight: 500;
}

.priority-button:hover,
.type-button:hover {
  background: #f9fafb;
  border-color: #d1d5db;
}

.priority-selected,
.type-selected {
  background: #eff6ff;
  border-color: #3b82f6;
  color: #1e40af;
}

.priority-icon,
.type-icon {
  margin-right: 0.75rem;
  font-size: 1.125rem;
}

.date-range {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.date-input-group {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.date-label {
  font-size: 0.75rem;
  font-weight: 500;
  color: #6b7280;
}

.date-input {
  padding: 0.625rem;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  transition: all 0.2s;
}

.date-input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
  outline: none;
}

.labels-container {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.labels-input-group {
  display: flex;
  gap: 0.5rem;
}

.label-input {
  flex: 1;
  padding: 0.625rem;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  transition: all 0.2s;
}

.label-input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
  outline: none;
}

.add-label-button {
  padding: 0.625rem;
  background: #3b82f6;
  color: white;
  border-radius: 0.375rem;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.add-label-button:hover:not(:disabled) {
  background: #2563eb;
}

.add-label-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.selected-labels {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  min-height: 2rem;
}

.label-tag {
  display: flex;
  align-items: center;
  gap: 0.375rem;
  padding: 0.375rem 0.625rem;
  background: #eff6ff;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  color: #1e40af;
  font-weight: 500;
}

.remove-label-button {
  color: #3b82f6;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.remove-label-button:hover {
  color: #1e40af;
}

.apply-filters-button {
  width: 100%;
  padding: 0.75rem;
  background: #3b82f6;
  color: white;
  border-radius: 0.375rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.apply-filters-button:hover {
  background: #2563eb;
}

.reset-filters-button {
  width: 100%;
  padding: 0.75rem;
  background: white;
  color: #4b5563;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  font-weight: 500;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.reset-filters-button:hover {
  background: #f9fafb;
  border-color: #d1d5db;
}

/* Dark mode styles */
:deep(.dark) .sidebar-content {
  background: #1f2937;
  border-right-color: #374151;
}

:deep(.dark) .filter-section {
  border-bottom-color: #374151;
}

:deep(.dark) .filter-label {
  color: #e5e7eb;
}

:deep(.dark) .priority-button,
:deep(.dark) .type-button {
  background: #374151;
  border-color: #4b5563;
  color: #e5e7eb;
}

:deep(.dark) .priority-button:hover,
:deep(.dark) .type-button:hover {
  background: #4b5563;
  border-color: #6b7280;
}

:deep(.dark) .priority-selected,
:deep(.dark) .type-selected {
  background: #1e40af;
  border-color: #3b82f6;
  color: #e5e7eb;
}

:deep(.dark) .date-label {
  color: #9ca3af;
}

:deep(.dark) .date-input {
  background: #374151;
  border-color: #4b5563;
  color: #e5e7eb;
}

:deep(.dark) .date-input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}

:deep(.dark) .label-input {
  background: #374151;
  border-color: #4b5563;
  color: #e5e7eb;
}

:deep(.dark) .label-input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}

:deep(.dark) .label-tag {
  background: #1e40af;
  color: #e5e7eb;
}

:deep(.dark) .remove-label-button {
  color: #93c5fd;
}

:deep(.dark) .remove-label-button:hover {
  color: #e5e7eb;
}

:deep(.dark) .reset-filters-button {
  background: #374151;
  border-color: #4b5563;
  color: #e5e7eb;
}

:deep(.dark) .reset-filters-button:hover {
  background: #4b5563;
  border-color: #6b7280;
}
</style>
  
  