<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Import the GoalDashboard component
import Goals from '@/Components/Goal/AllGoalsDashboard.vue';
</script>

<template>
  

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Goals
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        Welcome!
                    </div>
                  <Goals></Goals>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>