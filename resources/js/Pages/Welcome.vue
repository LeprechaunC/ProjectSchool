<template>
    <Head title="Welcome to GoalTracker" />
    
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">GoalTracker</span>
                </div>
                <div class="flex items-center space-x-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200"
                        >
                            Get Started
                        </Link>
                    </template>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-gray-900 dark:text-white mb-6">
                    Transform Your Goals into
                    <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Achievements</span>
                </h1>
                <p class="max-w-2xl mx-auto text-xl text-gray-600 dark:text-gray-300 mb-8">
                    The all-in-one platform for setting, tracking, and achieving your personal and professional goals.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200"
                    >
                        Start Your Journey
                    </Link>
                    <a href="#features" class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200">
                        Learn More
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
                    <div class="text-gray-600 dark:text-gray-300">Users achieve their goals faster</div>
                </div>
                <div class="text-center p-6">
                    <div class="text-4xl font-bold text-blue-600 dark:text-blue-400 mb-2">10K+</div>
                    <div class="text-gray-600 dark:text-gray-300">Active users worldwide</div>
                </div>
                <div class="text-center p-6">
                    <div class="text-4xl font-bold text-blue-600 dark:text-blue-400 mb-2">24/7</div>
                    <div class="text-gray-600 dark:text-gray-300">Goal tracking support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Everything You Need to Succeed</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300">Powerful features to help you achieve your goals</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Smart Goal Tracking</h3>
                    <p class="text-gray-600 dark:text-gray-300">Set and monitor your goals with intuitive progress tracking and analytics.</p>
                </div>
                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Time Management</h3>
                    <p class="text-gray-600 dark:text-gray-300">Efficiently manage your time with smart scheduling and deadline tracking.</p>
                </div>
                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Progress Analytics</h3>
                    <p class="text-gray-600 dark:text-gray-300">Get detailed insights into your progress with advanced analytics and reports.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">How GoalTracker Works</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300">Simple steps to achieve your goals</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">1</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Set Your Goals</h3>
                    <p class="text-gray-600 dark:text-gray-300">Define clear, achievable goals with our intuitive interface.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">2</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Track Progress</h3>
                    <p class="text-gray-600 dark:text-gray-300">Monitor your progress with real-time updates and analytics.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">3</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Achieve Success</h3>
                    <p class="text-gray-600 dark:text-gray-300">Celebrate your achievements and set new goals.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Ready to Start Your Journey?</h2>
            <p class="text-xl text-blue-100 mb-8">Join thousands of successful goal achievers today.</p>
            <Link
                v-if="canRegister"
                :href="route('register')"
                class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-blue-600 bg-white rounded-lg hover:bg-blue-50 transition-all duration-200"
            >
                Get Started Now
            </Link>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold text-white mb-4">GoalTracker</h3>
                    <p class="text-sm">Your personal goal tracking companion for success.</p>
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
                <p>&copy; {{ new Date().getFullYear() }} GoalTracker. All rights reserved.</p>
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
import { Head, Link } from '@inertiajs/vue3';

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