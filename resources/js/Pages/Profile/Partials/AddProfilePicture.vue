<template>
    <section class="profile-section">
        <header>
            <h2 class="profile-section-title">Profila Attēls</h2>
            <p class="profile-section-description">
                Atjauniniet savu profila foto, lai palīdzētu citiem jūs atpazīt
            </p>
        </header>

        <form @submit.prevent="updateProfilePicture" class="mt-6">
            <div class="flex flex-col items-center">
                <!-- Profile Avatar Container -->
                <div class="relative mb-6 group">
                    <div 
                        class="w-40 h-40 rounded-full overflow-hidden border-4 border-white dark:border-gray-700 shadow-lg transition-all duration-300 hover:shadow-xl"
                    >
                        <!-- Profile Image -->
                        <img
                            v-if="imagePreview || (userData && userData.profile_picture)"
                            :src="imagePreview || `/storage/${userData?.profile_picture}`"
                            class="w-full h-full object-cover"
                            alt="Profile Picture"
                        />
                        <!-- Fallback if no image -->
                        <div
                            v-else
                            class="w-full h-full bg-gradient-to-br from-blue-400 to-indigo-600 dark:from-blue-600 dark:to-indigo-800 flex items-center justify-center"
                        >
                            <span class="text-3xl font-bold text-white">
                                {{ userData?.name?.charAt(0).toUpperCase() || '?' }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Hover overlay with camera icon -->
                    <div 
                        @click="$refs.imageInput.click()"
                        class="absolute inset-0 rounded-full bg-black bg-opacity-0 flex items-center justify-center transition-all duration-300 cursor-pointer group-hover:bg-opacity-40"
                    >
                        <div class="rounded-full p-2 bg-white dark:bg-gray-800 shadow-md opacity-0 transform scale-75 transition-all duration-300 group-hover:opacity-100 group-hover:scale-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Hidden file input -->
                <input
                    ref="imageInput"
                    type="file"
                    class="hidden"
                    @change="handleImageSelect"
                    accept="image/*"
                />
                
                <!-- Visible button for file selection -->
                <button
                    type="button"
                    @click="$refs.imageInput.click()"
                    class="px-4 py-2 mt-2 bg-indigo-600 dark:bg-indigo-800 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 flex items-center"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                    Change Photo
                </button>
            </div>

            <!-- Save button (only shown when an image is selected) -->
            <div v-if="selectedFile" class="flex justify-center mt-6">
                <button
                    type="submit"
                    class="px-5 py-2 bg-green-600 dark:bg-green-700 text-white rounded-md hover:bg-green-700 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Save Photo
                    </div>
                </button>
            </div>

            <!-- Success message -->
            <Transition
                enter-active-class="transition ease-in-out duration-300"
                enter-from-class="opacity-0 transform translate-y-2"
                leave-active-class="transition ease-in-out duration-300"
                leave-to-class="opacity-0 transform translate-y-2"
            >
                <p v-if="form.recentlySuccessful" class="text-sm text-green-600 dark:text-green-400 text-center mt-4">
                    Profila attēls veiksmīgi atjaunināts.
                </p>
            </Transition>
        </form>
    </section>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

// Get user data from Inertia page props
const userData = computed(() => usePage().props.auth.user);

const imageInput = ref(null);
const selectedFile = ref(null);
const imagePreview = ref(null);

const form = useForm({
    profile_picture: null,
});

const handleImageSelect = (event) => {
    const file = event.target.files[0];
    if (file) {
        selectedFile.value = file;
        // Create preview URL
        imagePreview.value = URL.createObjectURL(file);
        form.profile_picture = file;
    }
};

const updateProfilePicture = () => {
    if (selectedFile.value) {
        form.post(route('profile.picture.update'), {
            preserveScroll: true,
            onSuccess: () => {
                selectedFile.value = null;
                // Optional: clear the file input
                if (imageInput.value) {
                    imageInput.value.value = '';
                }
            },
        });
    }
};
</script>