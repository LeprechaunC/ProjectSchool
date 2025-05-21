<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Import the GoalDashboard component
import GoalDashboard from '@/Components/Goal/GoalDashboard.vue';
import DarkModeToggle from '@/Components/DarkModeToggle.vue'; // Import DarkModeToggle
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-white">
                Vadības panelis
            </h2>
        </template>

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 dark:text-white">
                    

                    <!-- Use the GoalDashboard component here -->
                    <GoalDashboard />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
