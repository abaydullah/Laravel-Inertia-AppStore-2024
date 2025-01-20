<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm, Link} from '@inertiajs/vue3';
import Checkbox from "@/Components/Checkbox.vue"
import 'vue-select/dist/vue-select.css';
import VSelect from 'vue-select'
import {ref, watch} from "vue";
import vue3StarRatings from "vue3-star-ratings";
const props = defineProps({
    apps: Object,
    app: Object,
    status: Number,
    sort: String,
    search: String,
    type: Number,
    categories: Object,
    developers: Object,
    developer: Object,
    category: Object,
    errors: Object
})

const form = useForm({})


watch(() => form.name, (name) => {
    form.slug = name.toLowerCase()
        .replace(/[^\w ]+/g, "")
        .replace(/ +/g, "-");
})

const loading = ref(false)

const search = ref(props.search)
const filterApps = () => {
    let searchQuery = search.value
    // loading.value = true
    router.post(route('admin.scrape.index'), {searchQuery}, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onSuccess: (data) => {
            loading.value = false
        },
    })
}
const category = ref(props.category)
const developer = ref(props.developer)
const filterAppsByCategory = () => {
    let categoryQuery = category.value
    // loading.value = true
    router.post(route('admin.scrape.index'), {categoryQuery}, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onSuccess: (data) => {
            loading.value = false
        },
    })
}
const filterAppsByDeveloper = () => {
    let developerQuery = developer.value
    // loading.value = true
    router.post(route('admin.scrape.index'), {developerQuery}, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onSuccess: (data) => {
            loading.value = false
        },
    })
}

const checkedApps = ref([]);
const checkedApp = (app) => {
    if (app.checked) {
        checkedApps.value.push(app)
    } else {

        checkedApps.value.splice(checkedApps.value.indexOf(app), 1);
    }
}
const checkAll = ref(false)
const checkedAll = () => {
    checkAll.value = true
    props.apps.filter(app => {
        app.checked = true
        checkedApps.value.push(app)
    })

}
const uncheckedAll = () => {
    checkAll.value = false
    props.apps.filter(app => {
        app.checked = false
        checkedApps.value.splice(checkedApps.value.indexOf(app), 1);
    })

}
const saveAll = () => {
    let data = checkedApps.value;
    router.post(route('admin.scrape.store'), {data}, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onSuccess: (data) => {
            //
        },
    })
}
</script>

<template>
    <Head title="Apps"/>

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Apps
            </h2>
        </template>

        <div
            class=" bg-white shadow-sm sm:rounded-lg"
        >

            <div
                class="fixed top-0  flex items-center justify-center h-screen z-40 opacity-60 inset-1 w-full bg-slate-900"
                v-if="loading">
                <ion-icon name="sync"
                          class="absolute top-1/2 right-[40%] animate-spin text-9xl text-red-100 font-extrabold"></ion-icon>
            </div>


            <div class="p-6 text-gray-900">
                <div id="content" class="">
                    <Header title="main.app-list"></Header>
                    <div class="relative shadow-md sm:rounded-lg mt-5">
                        <div
                            class="flex items-center justify-between flex-wrap md:flex-row space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-900">
                            <div class="flex space-x-3">
                                <div>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                            </svg>
                                        </div>
                                        <input v-model="search" type="text" id="table-search-users"
                                               class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                               placeholder="Search for App">
                                    </div>
                                </div>
                                <div>

                                    <button
                                        type="button"
                                        @click.prevent="filterApps"
                                        class="rounded-md bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                                    >
                                        Search
                                    </button>
                                </div>


                            </div>
                            <div class="flex space-x-3">
                                <div class="">
                                    <v-select :options="categories" :reduce="category => category" label="name"
                                              class="style-chooser w-64 " v-model="category"></v-select>
                                    <span class="text-lg text-red-600 font-semibold" v-if="errors.type">{{
                                            errors.type
                                        }}</span>
                                </div>
                                <div>

                                    <button
                                        type="button"
                                        @click.prevent="filterAppsByCategory"
                                        class="rounded-md bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                                    >
                                        Search
                                    </button>
                                </div>


                            </div>
                            <div class="flex space-x-3">
                                <div class="">
                                    <v-select :options="developers" :reduce="developer => developer" label="name"
                                              class="style-chooser w-64 " v-model="developer"></v-select>
                                    <span class="text-lg text-red-600 font-semibold" v-if="errors.developer">{{
                                            errors.developer
                                        }}</span>
                                </div>
                                <div>

                                    <button
                                        type="button"
                                        @click.prevent="filterAppsByDeveloper"
                                        class="rounded-md bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                                    >
                                        Search
                                    </button>
                                </div>


                            </div>
                        </div>
                        <div v-if="apps">
                            <table
                                class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border"
                                style="width: 100%">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border">
                                <tr>
                                    <th scope="col" class="p-4" style="width: 5%;">
                                        ID
                                    </th>
                                    <th scope="col" class="p-4" style="width: 10%;">
                                        <button v-if="!checkAll"
                                                type="button"
                                                @click="checkedAll"
                                                class="rounded-md bg-blue-700 px-2 py-1 text-sm font-medium text-white hover:bg-blue-700/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                                        >
                                            All Checked
                                        </button>

                                        <button v-else
                                                type="button"
                                                @click="uncheckedAll"
                                                class="rounded-md bg-blue-700 px-2 py-1 text-sm font-medium text-white hover:bg-blue-700/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                                        >
                                            All Unchecked
                                        </button>
                                        <span
                                            class="text-base capitalize mt-1 block text-center">{{ checkedApps.length ?? 0 }} of {{
                                                props.apps.length
                                            }}</span>

                                    </th>
                                    <th scope="col" class="px-6 py-3" style="width: 10%;">
                                        Icon
                                    </th>
                                    <th scope="col" class="px-6 py-3" style="width: 20%;">
                                        Title
                                    </th>
                                    <th scope="col" class="px-6 py-3" style="width: 10%;">
                                        Rating
                                    </th>
                                    <th scope="col" class="px-6 py-3" style="width: 20%;">
                                        App ID
                                    </th>
                                    <th scope="col" class="px-6 py-3" style="width: 10%;">
                                        Type
                                    </th>

                                    <th scope="col" class="px-6 py-3" style="width: 15%;">
                                        <button
                                            type="button"
                                            @click="saveAll"
                                            class="rounded-md bg-blue-700 px-2 py-1 text-sm font-medium text-white hover:bg-blue-700/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                                        >
                                            Save All
                                        </button>

                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                                    v-for="(app,index) in apps" :key="index">
                                    <td class="p-4 text-lg font-semibold">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="p-4 text-lg font-semibold">
                                        <Checkbox v-model="app.checked" :checked="app.checked"
                                                  @change="checkedApp(app)"/>
                                    </td>
                                    <td class="p-4 text-lg font-semibold">
                                        <img :src="app.icon" alt="" width="70">
                                    </td>
                                    <td class="p-4 text-lg font-semibold">
                                      <span>
                                                    {{ app.title }}
                                                </span>
                                    </td>
                                    <td class="p-4 text-lg font-semibold">
                                        <div class="flex space-x-3 items-center">
                                            <span class="text-lg">{{ app.rating }} </span>
                                            <vue3-star-ratings v-model="app.rating" disableClick="false"
                                                               inactiveColor="#cbd5e1"/>
                                        </div>
                                    </td>
                                    <td class="p-4 text-lg font-semibold">
                                        <span class="text-xs">{{ app.app_id }}</span>
                                    </td>


                                    <td class="px-2 py-2 capitalize">
                                        {{ app.type }}
                                    </td>


                                    <td class="px-2 py-2 space-x-5">

                                    </td>
                                </tr>

                                </tbody>
                            </table>


                        </div>

                        <div v-else
                             class="text-center text-4xl font-extrabold bg-gradient-to-t from-green-300 to-green-800 bg-clip-text text-transparent leading-normal">
                            Data Not Found
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style>

.style-chooser .vs__search::placeholder,
.style-chooser .vs__dropdown-toggle,
.style-chooser .vs__dropdown-menu {

    padding: 5px 8px;
}

.style-chooser1 .vs__search::placeholder,
.style-chooser1 .vs__dropdown-toggle,
.style-chooser1 .vs__dropdown-menu {

    padding: 7px 8px;
    z-index: 10;
}

:root {
    --dp-input-padding: 12px 30px 12px 12px;
    --vs-dropdown-bg: #fff;
    --vs-dropdown-color: inherit;
    --vs-dropdown-z-index: 1000;
    --vs-dropdown-min-width: 200px;
    --vs-dropdown-max-height: 250px;
}
</style>
