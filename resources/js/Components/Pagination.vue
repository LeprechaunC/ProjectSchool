<template>
    <div v-if="links.length > 3" class="flex flex-wrap -mb-1">
        <template v-for="(link, key) in links" :key="key">
            <div v-if="link.url === null" 
                class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                v-html="link.label" />
            <Link v-else
                class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-gray-100 dark:hover:bg-gray-700 focus:border-indigo-500 focus:text-indigo-500 dark:focus:border-indigo-600 dark:focus:text-indigo-600"
                :class="{ 'bg-blue-50 dark:bg-blue-900': link.active }"
                :href="link.url"
                v-html="link.label" />
        </template>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script> 