<template>
    <Head title="Laipni Lūdzam Mērķu Tīmeklī" />
    
    <!-- Navigation -->
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
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                        >
                            Vadības Panelis
                        </Link>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                            >
                                Ienākt
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200"
                            >
                                Sākt
                            </Link>
                        </template>
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
                <ResponsiveNavLink v-if="$page.props.auth.user" :href="route('dashboard')">
                    Vadības Panelis
                </ResponsiveNavLink>
                <template v-else>
                    <ResponsiveNavLink :href="route('login')">
                        Ienākt
                    </ResponsiveNavLink>
                    <ResponsiveNavLink v-if="canRegister" :href="route('register')">
                        Reģistrēties
                    </ResponsiveNavLink>
                </template>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-gray-900 dark:text-white mb-6">
                    Pārveidojiet Savus Mērķus
                    <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Sasniegumos</span>
                </h1>
                <p class="max-w-2xl mx-auto text-xl text-gray-600 dark:text-gray-300 mb-8">
                    Visaptveroša platforma jūsu personīgo un profesionālo mērķu noteikšanai, izsekošanai un sasniegšanai.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200"
                    >
                        Sāciet Savu Ceļojumu
                    </Link>
                    <a href="#features" class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200">
                        Uzzināt Vairāk
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="text-4xl font-bold text-blue-600 dark:text-blue-400 mb-2">85%</div>
                    <div class="text-gray-600 dark:text-gray-300">Lietotāji sasniedz savus mērķus ātrāk</div>
                </div>
                <div class="text-center p-6">
                    <div class="text-4xl font-bold text-blue-600 dark:text-blue-400 mb-2">10K+</div>
                    <div class="text-gray-600 dark:text-gray-300">Aktīvi lietotāji visā pasaulē</div>
                </div>
                <div class="text-center p-6">
                    <div class="text-4xl font-bold text-blue-600 dark:text-blue-400 mb-2">24/7</div>
                    <div class="text-gray-600 dark:text-gray-300">Mērķu izsekošanas atbalsts</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Viss, Kas Nepieciešams Jūsu Veiksmēm</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300">Jaudīgas funkcijas, kas palīdzēs sasniegt jūsu mērķus</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Gudra Mērķu Izsekošana</h3>
                    <p class="text-gray-600 dark:text-gray-300">Iestatiet un uzraugiet savus mērķus ar intuitīvu progresu un analītiku.</p>
                </div>
                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Laika Pārvaldība</h3>
                    <p class="text-gray-600 dark:text-gray-300">Efektīvi pārvaldiet savu laiku ar gudru plānošanu un termiņu izsekošanu.</p>
                </div>
                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Progresa Analītika</h3>
                    <p class="text-gray-600 dark:text-gray-300">Iegūstiet detalizētu ieskatu savā progresā ar uzlabotu analītiku un pārskatiem.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Kā Darbojas Mērķu Tīmeklis</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300">Vienkārši soļi jūsu mērķu sasniegšanai</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">1</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Iestatiet Savus Mērķus</h3>
                    <p class="text-gray-600 dark:text-gray-300">Definējiet skaidrus, sasniedzamus mērķus ar mūsu intuitīvo saskarni.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">2</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Izsekojiet Progresu</h3>
                    <p class="text-gray-600 dark:text-gray-300">Uzraugiet savu progresu ar reāllaika atjauninājumiem un analītiku.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">3</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Sasniedziet Veiksmi</h3>
                    <p class="text-gray-600 dark:text-gray-300">Sviniet savus sasniegumus un iestatiet jaunus mērķus.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Gatavi Sākt Savu Ceļojumu?</h2>
            <p class="text-xl text-blue-100 mb-8">Pievienojieties tūkstošiem veiksmīgu mērķu sasniegēju jau šodien.</p>
            <Link
                v-if="canRegister"
                :href="route('register')"
                class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-blue-600 bg-white rounded-lg hover:bg-blue-50 transition-all duration-200"
            >
                Sāciet Tagad
            </Link>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold text-white mb-4">Mērķu Tīmeklis</h3>
                    <p class="text-sm">Jūsu personīgais mērķu izsekošanas palīgs veiksmei.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Features</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Goal Setting</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Progress Tracking</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Analytics</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Reminders</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Resources</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Support</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-sm text-center">
                <p>&copy; {{ new Date().getFullYear() }} GoalWeb. All rights reserved.</p>
            </div>
        </div>
    </footer>
</template>

<style>
.bg-gradient-to-r {
    background-size: 200% 200%;
    animation: gradient 15s ease infinite;
}

@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}
</style>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import DarkModeToggle from '@/Components/DarkModeToggle.vue';

const showingNavigationDropdown = ref(false);

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>