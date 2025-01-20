<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <FrontendLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <div
            class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
        >
            <div class="p-6 text-gray-900">
                You're logged in!
            </div>
        </div>
    </FrontendLayout>
</template>
