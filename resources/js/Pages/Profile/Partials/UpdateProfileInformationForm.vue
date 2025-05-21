<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

// Form handling for user info update
const form = useForm({
    name: user.name,
    email: user.email,
});

// File input handling for profile picture
const profilePicture = ref(null);
const errorMessage = ref('');
 
 
</script>

<template>
    <section class="profile-section">
        <header>
            <h2 class="profile-section-title">
                Profila Informācija
            </h2>

            <p class="profile-section-description">
                Atjauniniet sava konta profila informāciju un e-pasta adresi.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="form-section"
        >
            <div>
                <InputLabel for="name" value="Vārds" class="text-gray-700 dark:text-gray-300" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-500"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="E-pasts" class="text-gray-700 dark:text-gray-300" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-500"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/30 rounded-md">
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-yellow-600 dark:text-yellow-400 underline hover:text-yellow-900 dark:hover:text-yellow-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4 mt-6">
                <PrimaryButton 
                    :disabled="form.processing"
                    class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600"
                >
                    Saglabāt Izmaiņas
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out duration-300"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out duration-300"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-green-600 dark:text-green-400"
                    >
                        Profila informācija saglabāta.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
