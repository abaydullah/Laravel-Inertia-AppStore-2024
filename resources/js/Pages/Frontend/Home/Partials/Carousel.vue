<script setup>

import {Carousel, Navigation, Pagination, Slide} from "vue3-carousel";

defineProps({
    apps: Object,
    breakpoints: {
        // 700px and up
        700: {
            itemsToShow: 3.5,
            snapAlign: 'center',
        },
        // 1024 and up
        1024: {
            itemsToShow: 5,
            snapAlign: 'start',
        },
    },
})
const config = {
    itemsToShow: 3.95,
    wrapAround: true,
    transition: 500,
}
</script>

<template>
    <div class="w-full" style="resize: horizontal; overflow: auto">
        <Carousel v-bind="config" :autoplay="2000" :wrap-around="true">
            <slide v-for="app in apps" :key="app">
                <a v-if="app?.screenshots[0]?.photo" href="#"> <div class="carousel__item"><img :src="app?.screenshots[0]?.photo" alt=""
                                                              class="w-full h-full object-cover "></div></a>
            </slide>

            <template #addons>
                <Navigation/>
                <Pagination/>
            </template>
        </Carousel>


    </div>
</template>

<style scoped>
.carousel__slide {
    padding: 5px;
}

.carousel__viewport {
    perspective: 2000px;
}

.carousel__track {
    transform-style: preserve-3d;
}

.carousel__slide--sliding {
    transition: 0.5s;
}

.carousel__slide {
    opacity: 0.9;
    transform: rotateY(-20deg) scale(0.9);
}

.carousel__slide--active ~ .carousel__slide {
    transform: rotateY(20deg) scale(0.9);
}

.carousel__slide--prev {
    opacity: 1;
    transform: rotateY(-10deg) scale(0.95);
    font-size: 200px;
}
.carousel__icon{
    width: 500px!important;
}

.carousel__slide.carousel__slide--next {
    opacity: 1;
    transform: rotateY(10deg) scale(0.95);
}

.carousel__slide--active {
    opacity: 1;
    transform: rotateY(0) scale(1);
}
</style>

