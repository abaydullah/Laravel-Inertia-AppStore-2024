<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import {Head,Link} from '@inertiajs/vue3';
import Card from "@/Pages/Frontend/App/Partials/Card.vue";

const props = defineProps({
    apps: Object,

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
                <div class="flex">
                    <span class="flex items-center"><Link class="text-cyan-500 font-semibold hover:text-indigo-600" :href="route('home')">Home</Link> <ion-icon name="chevron-forward-outline" class="text-2xl text-cyan-500 font-extrabold"></ion-icon></span>
                    <span class="flex items-center"><span class="font-semibold">Apps</span> </span>


                </div>
               <div>
                   <Card :apps="apps"/>
               </div>
            </div>
        </div>
    </FrontendLayout>
</template>
