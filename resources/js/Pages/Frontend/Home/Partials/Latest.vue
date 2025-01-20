<script setup>
import vue3StarRatings from "vue3-star-ratings";
import { Link } from '@inertiajs/vue3';
defineProps({
    apps :Object,
    label :String
})
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';

// Carousel configuration
const config = {
    itemsToShow: 1,
    snapAlign: 'center',

    // 'breakpointMode' determines how the carousel breakpoints are calculated
    // Acceptable values: 'viewport' (default) | 'carousel'
    // 'viewport' - breakpoints are based on the viewport width
    // 'carousel' - breakpoints are based on the carousel width
    breakpointMode: 'carousel',

    // Breakpoints are mobile-first
    // Any settings not specified will fall back to the carousel's default settings
    breakpoints: {
        // 300px and up
        280: {
            itemsToShow: 2,
            snapAlign: 'center',
        },
        // 400px and up
        340: {
            itemsToShow: 3,
            snapAlign: 'start',
        },
        // 400px and up
        450: {
            itemsToShow: 4,
            snapAlign: 'start',
        },
        // 400px and up
        620: {
            itemsToShow: 5,
            snapAlign: 'start',
        },
        // 600px and up
        720: {
            itemsToShow: 6,
            snapAlign: 'start',
        },
        1050: {
            itemsToShow: 7,
            snapAlign: 'start',
        },
        // 500px and up
        1200: {
            itemsToShow: 8,
            snapAlign: 'start',
        },
        // 500px and up
        1300: {
            itemsToShow: 9,
            snapAlign: 'start',
        },
    },
};
</script>

<template>
<div class="py-5 shadow-2xl">

<div class="flex justify-between p-2 border-x-8 border-y-2 bg-emerald-700 ring-2 outline-4 rounded-md font-semibold text-white mb-5">
    <h2 class="">{{ label }}</h2>
    <a href="#" class="">More</a>
</div>
    <div class="" style="resize: horizontal; overflow: auto">
        <Carousel v-bind="config">
            <Slide v-for="(app,key) in apps" :key="key">
                <div class="carousel__item">
                    <div>
                        <Link :href="route('app.view',app)">  <div class="shadow-sm rounded p-1 hover:bg-gray-200 border ring-2 hover:border-amber-300 hover:ring-yellow-500 transition-all duration-200 hover:ring-4 ">
                            <img :src="app.icon" alt="" width="120" class="rounded object-cover ">
                            <h5 class="text-sm text-center">{{ app.title.substring(0, 12) }}</h5>
                            <div class="flex items-center justify-between py-2 px-2">
                 <span>
                     {{app.rating}}
                 </span>
                                <vue3-star-ratings star-size="15" v-model="app.rating" disable-click
                                           inactiveColor="#cbd5e1"/>
                            </div>

                        </div>
                        </Link>
                    </div>

                </div>
            </Slide>
            <template #addons>
                <Navigation />
            </template>
        </Carousel>
    </div>

</div>
</template>

<style scoped>

</style>
