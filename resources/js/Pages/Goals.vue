<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Import the GoalDashboard component
import Goals from '@/Components/Goal/AllGoalsDashboard.vue';

// Get the team_id from the page props
const props = defineProps({
  team_id: {
    type: [Number, String, null],
    default: null
  }
});
</script>

<template>
  

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Mērķu panelis
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    
                  <Goals :team_id="team_id"></Goals>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>