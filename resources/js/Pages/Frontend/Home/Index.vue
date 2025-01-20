<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head } from '@inertiajs/vue3';
import 'vue3-carousel/dist/carousel.css'
import Latest from "@/Pages/Frontend/Home/Partials/Latest.vue";
import Carousel from "@/Pages/Frontend/Home/Partials/Carousel.vue";
const props = defineProps({
    latest_apps:Object,
    latest_games:Object,
    popular_apps:Object,
    hot_apps:Object,

})


</script>

<template>
    <Head>
        <title>Home</title>
   </Head>

    <FrontendLayout>
        <div
            class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
        >

            <div class="p-6 text-gray-900">

                <div>
                    <carousel :apps="popular_apps"/>
                    <Latest :apps="latest_apps" label="Latest Apps"/>
                    <Latest :apps="latest_games" label="Latest Games"/>
                    <Latest :apps="popular_apps" label="Popular Apps"/>
                    <Latest :apps="hot_apps" label="Hot Apps"/>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>
