<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import {Head,Link} from '@inertiajs/vue3';
import 'vue3-carousel/dist/carousel.css'
import vue3StarRatings from "vue3-star-ratings";
import VueEasyLightbox from 'vue-easy-lightbox/dist/external-css/vue-easy-lightbox.esm.min.js'
import 'vue-easy-lightbox/external-css/vue-easy-lightbox.css'
import {ref} from "vue";
import Related from "@/Pages/Frontend/App/Partials/Related.vue";
import Review from "@/Pages/Frontend/App/Partials/Review.vue";
const props = defineProps({
    app: Object,
    related_apps : Object,
    developer_apps : Object,

})

const rating = ref(props.app.review_count.data.rating>0?props.app.review_count.data.rating:props.app.rating)
const pluck = (arr,key) => arr.map(i => i[key]);
const photos = pluck(props.app.screenshots,'photo')
const visibleRef = ref(false)
const indexRef = ref(0)
const showImg = (index) => {
    indexRef.value = index
    visibleRef.value = true
}
const onHide = () => visibleRef.value = false
const description = ref('max-h-[160px] overflow-hidden')
const showText = ()=> {
    if (description.value === 'max-h-full'){
        description.value = 'max-h-[160px] overflow-hidden'
    }else{
        description.value = 'max-h-full'
    }

}
const isShow = ref(false)

const openModal =  () => {
    isShow.value = true
}
const closeModal =  () => {
    isShow.value = false
}
</script>

<template>
    <Head>
        <title>Home</title>
    </Head>

    <FrontendLayout>
        <div
        class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
    >

        <div class="p-6 text-gray-900 md:bg-no-repeat bg-right md:bg-contain rounded-md" :style="`background-image: url('${app.cover_image}')`">
            <div class="flex">
                <span class="flex items-center"><Link class="text-cyan-500 font-semibold hover:text-indigo-600" :href="route('home')">Home</Link> <ion-icon name="chevron-forward-outline" class="text-2xl text-cyan-500 font-extrabold"></ion-icon></span>
                <span class="flex items-center"><Link class="text-cyan-500 font-semibold hover:text-indigo-600" :href="route('app.index')">Apps</Link> <ion-icon name="chevron-forward-outline" class="text-2xl text-cyan-500 font-extrabold"></ion-icon></span>
                <span class="flex items-center"><Link v-if="app.category" class="text-cyan-500 font-semibold hover:text-indigo-600" :href="route('categories.view',app.category)">{{ app.category?.name }}</Link> <ion-icon v-if="app.category" name="chevron-forward-outline" class="text-2xl text-cyan-500 font-extrabold"></ion-icon></span>
                <span class="flex items-center">{{ app.title }}</span>
            </div>
            <div class="flex space-x-3 mt-5" >

                <div class="rounded-md">
                    <img :src="app.icon" alt="" width="240" class="object-cover rounded-md">
                    <div class="text-center capitalize text-5xl shadow-md bg-emerald-500 text-white rounded-md">{{
                            app.type
                        }}
                    </div>
                </div>

                <div class="flex flex-col space-y-2">

                    <h1 class="text-5xl">
                        {{ app.title }}
                    </h1>
                    <span class="text-lg">Category: <a v-if="app.category" class="text-indigo-600 font-semibold"
                                                       :href="route('categories.view',app?.category)">{{ app?.category?.name }}</a></span>
                    <span v-if="app?.developer" class="text-lg">Developer: <a class="text-indigo-600 font-semibold"
                                                                              :href="route('developers.view',app?.developer)">{{ app?.developer?.name }}</a></span>
                    <div class="flex space-x-3 items-center">
                        <span class="text-4xl">{{ rating }} </span>
                        <vue3-star-ratings v-model="rating" star-size="40"
                                           inactiveColor="#cbd5e1" :disableClick="true"/>
                    </div>
                    <span class="text-lg mt-5 flex space-x-2">
                             <span class="text-lg">Google Play: </span>
                            <a class="text-indigo-600 font-semibold"
                               :href="app.url"><ion-icon name="logo-google-playstore" class=" text-2xl text-cyan-500 font-extrabold"></ion-icon></a>
                        </span>
                    <span class="text-lg mt-5">
                             <span class="text-lg"> Updated On: </span>
                            {{ app.updated_at }}
                        </span>
                    <div>
                        <button v-if="app.trailer" class="text-white bg-gray-800 text-lg py-2 px-4 rounded-lg justify-center items-center flex" @click="openModal"><ion-icon name="play-outline" class="text-white text-xl pr-1"></ion-icon> <span>Trailer</span></button>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div
            class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-2"
        >
                <div class="py-4 px-5" v-if="app.description">
                    <h3 class="text-3xl py-2">About {{app.title}}</h3>
                    <p class="text-lg " :class="description" v-html="app.description"></p>
                    <button @click="showText" class="text-indigo-500 font-semibold mt-5">{{description === 'max-h-full'? 'Show Less' : 'Show More'}}</button>
                </div>

        </div>
        <div
            class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-2"
        >
            <div class="py-4 px-5" v-if="app.whats_new">
                <h3>What's New</h3>
                <p class="text-lg" v-html="app.whats_new"></p>
            </div>

        </div>
        <div
            class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-2"
        >
            <div class="mt-5" v-if="photos.length">
                <div class="flex gap-4 overflow-x-scroll w-full bg-blue-50">
                    <div v-for="(src, index) in photos" :key="index" class="pic" @click="() => showImg(index)">
                        <img :src="src" class="mx-2 min-w-[240px] border rounded-md"/>
                    </div>
                </div>
                <vue-easy-lightbox :visible="visibleRef" :imgs="photos" :index="indexRef" @hide="onHide" class=""></vue-easy-lightbox>
            </div>

        </div>

        <div
            class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-2"
        >
            <div class="py-4 px-5" v-if="app.versions.length">
                <h3 class="mb-4 text-2xl">Versions History of {{app.title}} </h3>
                <div class="divide-y py-5">
                <div v-for="version in app.versions.slice(0,3)" class="">

                        <div class="text-lg flex justify-between ">
                            <a href="" class="py-2 text-gray-700 hover:text-indigo-700 hover:font-semibold">
                                <h3>v{{version.version}} ({{version.version_code}})</h3>
                                <div class="space-x-6 text-gray-400">
                                    <span>{{version.file_size}}</span>
                                    <span>{{version.date_format}}</span>
                                </div>
                            </a>
                            <div class="flex items-center justify-items-center">
                                <Link :href="route('app.view.download',[app,version])" class="px-3 py-1 bg-green-500 text-white flex items-center space-x-2 hover:bg-green-400 rounded-md"><ion-icon name="arrow-down-circle-outline"></ion-icon> <span>Download</span></Link>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center pt-5 border-t" v-if="app.versions.length>3">
                    <Link class="px-32 py-2 bg-gray-50 border-2 border-green-500 hover:border-green-200 rounded-md hover:bg-green-500 hover:text-gray-50 transition-all duration-300" :href="route('app.view.versions',app.slug)">
                        All Versions
                    </Link>
                </div>
                </div>

        </div>
        <div
            class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-2"
        >
            <div class="mt-5">
               <Related v-if="related_apps.length>0" :apps="related_apps" label="Related Apps"/>
               <Related v-if="developer_apps.length>0" :apps="developer_apps" label="Developer App"/>
                </div>

            </div>

        <Review :app="app" label="Developer App"/>

    </FrontendLayout>
</template>
z
