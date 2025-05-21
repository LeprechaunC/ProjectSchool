<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import DarkModeToggle from '@/Components/DarkModeToggle.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <!-- Navigation Bar -->
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <!-- Left Side -->
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link href="/" class="flex items-center">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800 dark:text-white"
                                    />
                                    <span class="ml-2 text-xl font-bold text-gray-900 dark:text-white hidden sm:block">Mērķu Tīmeklis</span>
                                </Link>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Dark Mode Toggle -->
                            <div class="mr-3">
                                <DarkModeToggle />
                            </div>
                            
                            <!-- Auth Links -->
                            <div class="flex items-center space-x-4">
                                <Link 
                                    v-if="!route().current('login')" 
                                    :href="route('login')" 
                                    class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium text-sm transition-colors"
                                >
                                    Ienākt
                                </Link>
                                <Link 
                                    v-if="!route().current('register')" 
                                    :href="route('register')" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium text-sm transition-colors"
                                >
                                    Reģistrēties
                                </Link>
                                <Link 
                                    href="/" 
                                    class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium text-sm transition-colors"
                                >
                                    Atpakaļ uz Sākumu
                                </Link>
                            </div>
                        </div>

                        <!-- Mobile menu button -->
                        <div class="flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-900 dark:focus:text-white transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 pb-1 space-y-1">
                        <ResponsiveNavLink v-if="!route().current('login')" :href="route('login')">
                            Ienākt
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="!route().current('register')" :href="route('register')">
                            Reģistrēties
                        </ResponsiveNavLink>
                        <ResponsiveNavLink href="/">
                            Atpakaļ uz Sākumu
                        </ResponsiveNavLink>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Add any specific styles you need for the auth layout here */
</style> 