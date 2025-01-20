<script setup>
import vue3StarRatings from "vue3-star-ratings";
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    apps: Object,
    label: String,
})


// const loadData = () =>{
//     axios.get(`${props.apps.meta.path}?cursor=${props.apps.meta.next_cursor}`).then((response)=>{
//         props.apps.data = [...props.apps.data,...response.data.data]
//         props.apps.meta = response.data.meta
//     })
// }
</script>

<template>
    <div class="p-5 shadow-md ">

        <div
            class="flex justify-between p-2 border-x-8 border-y-2 bg-emerald-700 ring-2 outline-4 rounded-md font-semibold text-white mb-5">
            <h2 class="">{{ label }}</h2>
            <a href="#" class="">More</a>
        </div>


<div class="grid grid-cols-1 gap-4">
<div v-for="app in apps" >
    <div class="">
        <Link :href="route('app.view',app)">
            <div class="shadow-sm rounded p-1 hover:bg-gray-200 border ring-2 hover:border-amber-300 hover:ring-yellow-500 transition-all duration-200 hover:ring-4">
<div class="flex space-x-2">
    <div class="flex flex-col  items-center space-y-2">
        <img :src="app.icon" alt="" width="60" class="rounded object-cover ">

    </div>
    <div>
        <h5 class="text-sm text-center font-semibold">{{ app.title.substring(0, 12) }}</h5>
        <div class="text-sm text-gray-500">{{ app.developer?.name.substring(0, 12) }}</div>
        <vue3-star-ratings star-size="12" v-model="app.rating" disable-click
                           inactiveColor="#cbd5e1"/>
    </div>
</div>

            </div>
        </Link>
    </div>

</div>


</div>





    </div>
</template>

