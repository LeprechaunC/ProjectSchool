<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Import the GoalDashboard component
import Teams from '@/Components/Teams/TeamsDashboard.vue';
</script>

<template>
    <Head title="Teams" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Teams Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                   
                    <Teams />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Basic layout adjustments */
.py-12 {
    padding-top: 3rem;
    padding-bottom: 3rem;
}

.mx-auto {
    margin-left: auto;
    margin-right: auto;
}

/* Styling for the teams section */
h2 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 1rem;
}

/* Styling the container */
.max-w-7xl {
    max-width: 72rem;
}

.bg-white {
    background-color: #ffffff;
}

.shadow-sm {
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.sm\:rounded-lg {
    border-radius: 0.5rem;
}

/* Card styling */
.overflow-hidden {
    overflow: hidden;
    border-radius: 0.5rem;
}

.p-6 {
    padding: 1.5rem;
}

.text-gray-900 {
    color: #1a202c;
}

/* Styling for the 'You're logged in!' text */
.text-gray-900 {
    color: #1a202c;
    font-size: 1.125rem;
}

/* Responsive styling for larger screens */
@media (min-width: 768px) {
    .py-12 {
        padding-top: 4rem;
        padding-bottom: 4rem;
    }
}
</style>
    