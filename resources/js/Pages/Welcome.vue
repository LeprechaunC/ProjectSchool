<template>
    <Head title="Welcome" />
    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-center">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <div class="p-8">  
                            <Link
                                :href="route('login')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Register
                            </Link>
                        </div>
                        </template>
                    </nav>


                  

                    <section class="bg-white dark:bg-gray-900">
    <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
        <div class="mr-auto place-self-center lg:col-span-7 text-center lg:text-left">
            <h1 class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white">
                Achieve Your Goals with GoalTracker
            </h1>
            <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">
                Set daily, weekly, and monthly goals, and track your progress towards success.
            </p>
            <div class="flex justify-center lg:justify-start gap-4">
                <a href="#" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">
                    Get started
                </a>
                <a href="#" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                    Learn More
                </a> 
            </div>
        </div>
        <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
            <img src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/hero/phone-mockup.png" alt="mockup">
        </div>                
    </div>
</section>

    
    <div class="entireContainer bg-[#171717] p-4 text-white"> 

    <section class="mx-auto my-16 flex flex-col lg:flex-row items-center justify-between max-w-7xl px-6">
        <div class="w-full mb-8 lg:w-1/2 lg:pr-8 lg:mb-0">
            <h1 class="text-3xl font-bold mb-4">Stay Focused and Organized!</h1>
            <p class="text-lg">
                Create clear and actionable goals for every aspect of your life. Whether it's personal or professional, 
                we provide tools to define, track, and achieve your objectives effectively.
            </p>
        </div>
        <div class="w-full lg:w-1/2 lg:pl-8">
            <img src="/welcomeimage1.png" alt="Goal dashboard screenshot" class="w-full rounded-xl shadow-xl ring-1 ring-gray-400/10" />        </div>
    </section>

    <section class="mx-auto my-16 flex flex-col-reverse items-center justify-between max-w-7xl px-6 mb-40 lg:flex-row">
        <div class="w-full mt-8 lg:w-1/2 lg:pr-8 lg:mt-0">
            <img src="/welcomeimage2.png" alt="Goal planning options" class="w-full rounded-xl shadow-xl ring-1 ring-gray-400/10" />
        </div>
        <div class="w-full lg:w-1/2 lg:pl-8">
            <h1 class="text-3xl font-bold mb-4">Advanced Goal Planning</h1>
            <p class="text-lg">
                Break down your goals into manageable steps and track your progress. Set deadlines, prioritize tasks, 
                and receive reminders to keep yourself on track.
            </p>
        </div>
    </section>
</div>
</template>
<style>
 

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

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>