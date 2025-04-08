<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import AddProfilePicture from './Partials/AddProfilePicture.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Profile
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Profile Overview Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6 md:flex items-start">
                        <!-- Left column - Profile Picture -->
                        <div class="md:w-1/3 mb-6 md:mb-0 md:pr-8">
                            <AddProfilePicture class="profile-picture-container" />
                        </div>

                        <!-- Right column - Forms -->
                        <div class="md:w-2/3">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                                <UpdateProfileInformationForm
                                    :must-verify-email="mustVerifyEmail"
                                    :status="status"
                                    class="w-full"
                                />
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                                <UpdatePasswordForm class="w-full" />
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                                <DeleteUserForm class="w-full" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
/* Global styles for profile components */
.profile-section {
    @apply mb-6 last:mb-0;
}

.profile-section-title {
    @apply text-lg font-medium text-gray-900 dark:text-gray-100 mb-1;
}

.profile-section-description {
    @apply text-sm text-gray-600 dark:text-gray-400 mb-4;
}

.profile-picture-container {
    @apply flex flex-col items-center;
}

/* Form styles */
.form-section {
    @apply space-y-4;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .md\:w-1\/3, .md\:w-2\/3 {
        width: 100%;
    }
    
    .md\:pr-8 {
        padding-right: 0;
    }
}
</style>
