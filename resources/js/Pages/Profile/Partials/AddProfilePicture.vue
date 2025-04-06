<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Picture</h2>
            <p class="mt-1 text-sm text-gray-600">
                Update your profile picture
            </p>
        </header>

        <form @submit.prevent="updateProfilePicture" class="mt-6 space-y-6">
            <div>
                <input
                    ref="imageInput"
                    type="file"
                    class="hidden"
                    @change="handleImageSelect"
                    accept="image/*"
                />
                
                <!-- Preview current or selected image -->
                <div class="mb-4">
                    <img
                        v-if="imagePreview || (userData && userData.profile_picture)"
                        :src="imagePreview || `/storage/${userData?.profile_picture}`"
                        class="w-32 h-32 rounded-full object-cover"
                        alt="Profile Picture"
                    />
                    <div
                        v-else
                        class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center"
                    >
                        <span class="text-gray-400">No Image</span>
                    </div>
                </div>

                <!-- Upload button -->
                <button
                    type="button"
                    @click="$refs.imageInput.click()"
                    class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700"
                >
                    Select New Image
                </button>
            </div>

            <div v-if="selectedFile" class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

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