<template>
  <div class="sidebar-container" :class="{ 'sidebar-open': isOpen }">
    <!-- Toggle Button -->
    <button 
      class="toggle-button" 
      @click="toggleSidebar"
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
          <h3 class="text-xl font-bold text-gray-900 dark:text-white">Filters</h3>
          <span class="text-sm text-gray-500 dark:text-gray-400">{{ filtersCount }} active</span>
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
              :class="{ 'priority-selected': filters.priorities.includes(priority.value) }"
            >
              <span class="priority-icon">{{ priority.icon }}</span>
              <span class="priority-text">{{ priority.label }}</span>
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
                v-model="filters.dateRange.start" 
                class="date-input"
              />
            </div>
            <div class="date-input-group">
              <label class="date-label">To</label>
              <input 
                type="date" 
                v-model="filters.dateRange.end" 
                class="date-input"
              />
            </div>
          </div>
        </div>
 
        <!-- Filter Actions -->
        <div class="mt-8 flex flex-col gap-3">
          <button 
            @click="applyFilters"
            class="apply-filters-button"
            :class="{ 'apply-button-highlight': hasChanges }"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            Apply Filters
          </button>
          
          <button 
            @click="resetFilters"
            class="reset-filters-button"
            :disabled="!filtersCount"
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
      isOpen: false,
      // Main filter state
      filters: {
        priorities: [],
        dateRange: { start: "", end: "" },
        labels: []
      },
      // Applied filters (last emitted filters)
      appliedFilters: {
        priorities: [],
        dateRange: { start: "", end: "" },
        labels: []
      },
      newLabel: "",
      priorities: [
        { value: "high", label: "High", icon: "🔴" },
        { value: "medium", label: "Medium", icon: "🟡" },
        { value: "low", label: "Low", icon: "🟢" }
      ]
    };
  },
  created() {
    // Load sidebar state
    const savedSidebarState = localStorage.getItem('sidebarState');
    if (savedSidebarState !== null) {
      this.isOpen = JSON.parse(savedSidebarState);
    }
    
    // Load saved filters
    this.loadSavedFilters();
  },
  computed: {
    filtersCount() {
      let count = 0;
      if (this.filters.priorities.length > 0) count++;
      if (this.filters.dateRange.start || this.filters.dateRange.end) count++;
      if (this.filters.labels.length > 0) count++;
      return count;
    },
    hasChanges() {
      // Check if current filters are different from applied filters
      return JSON.stringify(this.filters) !== JSON.stringify(this.appliedFilters);
    }
  },
  methods: {
    toggleSidebar() {
      this.isOpen = !this.isOpen;
      localStorage.setItem('sidebarState', JSON.stringify(this.isOpen));
    },
    
    togglePriority(value) {
      const index = this.filters.priorities.indexOf(value);
      if (index === -1) {
        this.filters.priorities.push(value);
      } else {
        this.filters.priorities.splice(index, 1);
      }
    },
    
    addLabel() {
      if (this.newLabel.trim() && !this.filters.labels.includes(this.newLabel.trim())) {
        this.filters.labels.push(this.newLabel.trim());
        this.newLabel = "";
      }
    },
    
    removeLabel(index) {
      this.filters.labels.splice(index, 1);
    },
    
    applyFilters() {
      // Create deep copies to prevent reference issues
      this.appliedFilters = JSON.parse(JSON.stringify(this.filters));
      
      // Save filters to localStorage
      this.saveFilters();
      
      // Emit filter event to parent
      this.$emit("filter-applied", this.appliedFilters);
    },
    
    resetFilters() {
      // Reset all filter states
      this.filters = {
        priorities: [],
        dateRange: { start: "", end: "" },
        labels: []
      };
      
      // Apply reset filters
      this.appliedFilters = JSON.parse(JSON.stringify(this.filters));
      
      // Clear saved filters
      localStorage.removeItem('savedFilters');
      
      // Emit reset event
      this.$emit('filter-applied', this.appliedFilters);
    },
    
    saveFilters() {
      localStorage.setItem('savedFilters', JSON.stringify(this.filters));
    },
    
    loadSavedFilters() {
      const savedFilters = localStorage.getItem('savedFilters');
      if (savedFilters) {
        try {
          const parsed = JSON.parse(savedFilters);
          // Validate and merge with defaults
          this.filters = {
            priorities: Array.isArray(parsed.priorities) ? parsed.priorities : [],
            dateRange: {
              start: parsed.dateRange?.start || "",
              end: parsed.dateRange?.end || ""
            },
            labels: Array.isArray(parsed.labels) ? parsed.labels : []
          };
          
          // Set applied filters to match loaded filters
          this.appliedFilters = JSON.parse(JSON.stringify(this.filters));
          
          // Apply filters on load if any exist
          if (this.filtersCount > 0) {
            this.$nextTick(() => {
              this.applyFilters();
            });
          }
        } catch (e) {
          console.error('Error loading saved filters:', e);
          localStorage.removeItem('savedFilters');
        }
      }
    }
  }
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
  border-right: 1px solid #e5e7eb;
}

.sidebar-open {
  transform: translateX(0);
}

.toggle-button {
  position: absolute;
  right: -120px;
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
  z-index: 40;
  width: 120px;
  justify-content: center;
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

.priority-options {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.priority-button {
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

.apply-button-highlight {
  background: #2563eb;
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0%, 100% {
    box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5);
  }
  50% {
    box-shadow: 0 0 0 6px rgba(59, 130, 246, 0);
  }
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

.reset-filters-button:hover:not(:disabled) {
  background: #f9fafb;
  border-color: #d1d5db;
}

.reset-filters-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
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

:deep(.dark) .priority-button {
  background: #374151;
  border-color: #4b5563;
  color: #e5e7eb;
}

:deep(.dark) .priority-button:hover {
  background: #4b5563;
  border-color: #6b7280;
}

:deep(.dark) .priority-selected {
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

:deep(.dark) .reset-filters-button:hover:not(:disabled) {
  background: #4b5563;
  border-color: #6b7280;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .sidebar-container {
    width: 100%;
    max-width: 300px;
  }
  
  .toggle-button {
    right: 0;
    top: 0;
    border-radius: 0;
    padding: 10px;
  }
  
  .sidebar-open {
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
  }
}
</style>
  