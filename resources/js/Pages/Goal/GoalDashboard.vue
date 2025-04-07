<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <!-- Calendar View Controls -->
            <div class="mb-4 flex justify-between items-center">
              <div class="flex space-x-2">
                <button 
                  @click="calendarApi.changeView('dayGridMonth')"
                  class="px-3 py-1 rounded-md text-sm font-medium"
                  :class="currentView === 'dayGridMonth' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                >
                  Month
                </button>
                <button 
                  @click="calendarApi.changeView('timeGridWeek')"
                  class="px-3 py-1 rounded-md text-sm font-medium"
                  :class="currentView === 'timeGridWeek' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                >
                  Week
                </button>
                <button 
                  @click="calendarApi.changeView('timeGridDay')"
                  class="px-3 py-1 rounded-md text-sm font-medium"
                  :class="currentView === 'timeGridDay' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                >
                  Day
                </button>
              </div>
              <div class="flex items-center space-x-2">
                <button 
                  @click="calendarApi.prev()"
                  class="p-2 rounded-md hover:bg-gray-200"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                  </svg>
                </button>
                <button 
                  @click="calendarApi.next()"
                  class="p-2 rounded-md hover:bg-gray-200"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
                <button 
                  @click="calendarApi.today()"
                  class="px-3 py-1 rounded-md text-sm font-medium bg-gray-200 text-gray-700 hover:bg-gray-300"
                >
                  Today
                </button>
              </div>
            </div>

            <!-- Calendar Component -->
            <FullCalendar 
              ref="calendar"
              :options="calendarOptions"
              class="calendar-container"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      currentView: 'dayGridMonth',
      calendarApi: null,
      calendarOptions: {
        initialView: 'dayGridMonth',
        headerToolbar: false,
        height: 'auto',
        aspectRatio: 1.8,
        slotMinTime: '00:00:00',
        slotMaxTime: '24:00:00',
        allDaySlot: true,
        slotDuration: '00:30:00',
        slotLabelInterval: '01:00',
        expandRows: true,
        stickyHeaderDates: true,
        dayMaxEvents: true,
        weekends: true,
        nowIndicator: true,
        eventTimeFormat: {
          hour: '2-digit',
          minute: '2-digit',
          meridiem: false
        },
        eventDidMount: (info) => {
          // Add priority-based styling
          const priority = info.event.extendedProps.priority;
          const priorityColors = {
            high: {
              backgroundColor: '#ef4444', // red-500
              borderColor: '#dc2626', // red-600
              textColor: '#ffffff'
            },
            medium: {
              backgroundColor: '#f59e0b', // amber-500
              borderColor: '#d97706', // amber-600
              textColor: '#ffffff'
            },
            low: {
              backgroundColor: '#10b981', // emerald-500
              borderColor: '#059669', // emerald-600
              textColor: '#ffffff'
            }
          };

          if (priority && priorityColors[priority]) {
            const colors = priorityColors[priority];
            info.el.style.backgroundColor = colors.backgroundColor;
            info.el.style.borderColor = colors.borderColor;
            info.el.style.color = colors.textColor;
          }

          // Add priority indicator dot
          const dot = document.createElement('div');
          dot.className = 'priority-dot';
          dot.style.width = '8px';
          dot.style.height = '8px';
          dot.style.borderRadius = '50%';
          dot.style.marginRight = '4px';
          dot.style.display = 'inline-block';
          dot.style.backgroundColor = priorityColors[priority]?.backgroundColor || '#6b7280';
          info.el.querySelector('.fc-event-title').prepend(dot);
        },
        eventContent: (arg) => {
          return {
            html: `
              <div class="fc-event-main-content">
                <div class="fc-event-title">${arg.event.title}</div>
                <div class="fc-event-time">${arg.timeText}</div>
              </div>
            `
          };
        }
      },
    };
  },
  mounted() {
    this.calendarApi = this.$refs.calendar.getApi();
    this.calendarApi.on('viewDidMount', (arg) => {
      this.currentView = arg.view.type;
    });
  },
};
</script>

<style scoped>
.calendar-container {
  background: white;
  padding: 1rem;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* View-specific styles */
:deep(.fc-timegrid-slot) {
  height: 3em !important;
}

:deep(.fc-timegrid-axis) {
  padding: 0.5em !important;
}

:deep(.fc-timegrid-slot-label) {
  font-size: 0.875rem;
}

:deep(.fc-timegrid-now-indicator-line) {
  border-color: #3b82f6;
}

:deep(.fc-timegrid-now-indicator-arrow) {
  border-color: #3b82f6;
}

/* Event styles */
:deep(.fc-event) {
  border-radius: 4px;
  padding: 2px 4px;
  margin: 1px 0;
  cursor: pointer;
  transition: transform 0.1s ease;
}

:deep(.fc-event:hover) {
  transform: scale(1.02);
}

:deep(.fc-event-main-content) {
  padding: 2px 4px;
}

:deep(.fc-event-title) {
  font-weight: 500;
  display: flex;
  align-items: center;
}

:deep(.fc-event-time) {
  font-size: 0.875rem;
  opacity: 0.9;
}

/* Dark mode adjustments */
:deep(.dark) .fc-event {
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}
</style> 