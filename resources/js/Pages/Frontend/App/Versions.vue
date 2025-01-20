<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import {Head,Link} from '@inertiajs/vue3';
import 'vue3-carousel/dist/carousel.css'
import vue3StarRatings from "vue3-star-ratings";
import 'vue-easy-lightbox/external-css/vue-easy-lightbox.css'
import {ref} from "vue";
import Modal from "@/Components/Modal.vue";
const props = defineProps({
    app: Object

})
const pluck = (arr,key) => arr.map(i => i[key]);
const photos = pluck(props.app.screenshots,'photo')
const visibleRef = ref(false)
const indexRef = ref(0)
const showImg = (index) => {
    indexRef.value = index
    visibleRef.value = true
}
const versionMax = ref(5)
const versionMore = (max)=>{
    if (versionMax.value+5 < max){
        versionMax.value+=5;
    }else{
        versionMax.value= max
    }
}
const versionLess = (max)=>{
        versionMax.value=5;

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
                <span class="flex items-center"><Link class="text-cyan-500 font-semibold hover:text-indigo-600" :href="route('categories.view',app.category)">{{ app.category.name }}</Link> <ion-icon name="chevron-forward-outline" class="text-2xl text-cyan-500 font-extrabold"></ion-icon></span>
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
                    <span class="text-lg">Category: <a class="text-indigo-600 font-semibold"
                                                       :href="route('categories.view',app.category)">{{ app?.category?.name }}</a></span>
                    <span v-if="app?.developer" class="text-lg">Developer: <a class="text-indigo-600 font-semibold"
                                                                              :href="route('developers.view',app?.developer)">{{ app?.developer?.name }}</a></span>
                    <div class="flex space-x-3 items-center">
                        <span class="text-4xl">{{ app.rating }} </span>
                        <vue3-star-ratings v-model="app.rating" star-size="40"
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
            <div class="py-4 px-5" v-if="app.versions" >
                <h3 class="mb-4 text-2xl">Versions History of {{app.title}} </h3>
                <div class="divide-y py-5">
                <div v-for="version in app.versions.slice(0,versionMax)" class="" >

                        <div class="text-lg flex justify-between ">
                            <a :href="route('app.view.version',[app.slug,version])" class="py-2 text-gray-700 hover:text-indigo-700 hover:font-semibold">
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

                <div class="text-center pt-5 border-t" v-if="app.versions.length > 5">
                    <Button v-if="versionMax!==app.versions.length" class="px-32 py-2 bg-gray-50 border-2 border-green-500 hover:border-green-200 rounded-md hover:bg-green-500 hover:text-gray-50 transition-all duration-300" @click="versionMore(app.versions.length)">
                        Show More
                    </Button>
                    <Button v-else class="px-32 py-2 bg-gray-50 border-2 border-green-500 hover:border-green-200 rounded-md hover:bg-green-500 hover:text-gray-50 transition-all duration-300" @click="versionLess(app.versions.length)">
                        Show Less
                    </Button>
                </div>
                </div>

        </div>
        <!--Start Add Modal-->
        <Modal :show="isShow" class="w-full">
            <div class="px-5 pb-5 my-5">
                <div class="flex justify-between justify-items-center items-center py-2">
                    <DialogTitle
                        as="h3"
                        class="text-lg font-medium leading-6 text-gray-900"
                    >
                        Add New Category
                    </DialogTitle>
                    <div>
                        <button type="button"
                                class="text-5xl leading-6 text-gray-400 font-normal"
                                @click="closeModal" data-bs-dismiss="modal" aria-label="Close">
                            &times;
                        </button>
                    </div>

                </div>
                <hr>


                <div class="mt-4 float-end space-x-2">
                    <iframe
                        class="w-[920px] aspect-video"
                        :src="app.trailer">
                    </iframe>
                </div>
            </div>
        </Modal>
        <!--End Add Modal-->
    </FrontendLayout>
</template>
z
