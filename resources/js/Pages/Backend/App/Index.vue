<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm,Link} from '@inertiajs/vue3';
import Modal from "@/Components/Modal.vue";
import {Switch} from '@headlessui/vue'
import 'vue-select/dist/vue-select.css';
import VSelect from 'vue-select'
import {ref, watch} from "vue";
import Pagination from "@/Components/Pagination.vue";
import {debounce} from "lodash";

const types = [{'id': 'app', 'name': 'App'}, {'id': 'game', 'name': 'Game'}]
const typeOptions = [{'id': '', 'name': 'All'}, {'id': 'app', 'name': 'App'}, {'id': 'game', 'name': 'Game'}]
const statusOptions = [{'id': '', 'name': 'All'}, {'id': '1', 'name': 'Published'}, {'id': '0', 'name': 'Unpublished'}]
const sortOptions = [{
    'id': 'date_created_desc',
    'name': 'Latest'
}, {
    'id': 'date_created_asc',
    'name': 'Oldest'
},{
    'id': 'date_desc',
    'name': 'Latest Updated'
}, {
    'id': 'date_asc',
    'name': 'Oldest Updated'
}, {'id': 'rating_desc', 'name': 'Rating Max'}, {
    'id': 'rating_asc',
    'name': 'Rating Min'
}, {'id': 'asc', 'name': 'Name A-Z'}, {'id': 'desc', 'name': 'Name Z-A'}, {
    'id': 'download_desc',
    'name': 'Download Max'
}, {'id': 'download_asc', 'name': 'Download Min'}]
import vue3StarRatings from "vue3-star-ratings";

const props = defineProps({
    apps: Object,
    app: Array,
    status: Number,
    sort: String,
    search: String,
    type: Number,
    errors: Object
})
const scrapApp = ref(null)
const form = useForm({
    name: '',
    google_name: '',
    google_url: '',
    slug: '',
    type: 'app',
    status: true
})
const editForm = useForm({
    _method: 'PUT',
    id: '',
    name: '',
    google_name: '',
    google_url: '',
    slug: '',
    type: 'app',
    status: true
})

watch(() => form.name, (name) => {
    form.slug = name.toLowerCase()
        .replace(/[^\w ]+/g, "")
        .replace(/ +/g, "-");
})
//add modal
const isShow = ref(false)
const loading = ref(false)

const openModal = () => {
    isShow.value = true
}
const closeModal = () => {
    isShow.value = false
    scrapApp.value = null;
}
const submit = () => {
    form.post(route('admin.categories.store'), {
        preserveScroll: true,
        onSuccess: () => {
            isShow.value = false
        }
    })
}
// update modal
const isShowUpdate = ref(false)
const openUpdateModal = (app) => {
    isShowUpdate.value = true
    editForm.id = app.id;
    editForm.name = app.name;
    editForm.google_name = app.google_name;
    editForm.google_url = app.google_url;
    editForm.slug = app.slug;
    editForm.type = app.type;
    editForm.status = app.status;
}
const closeUpdateModal = () => {
    isShowUpdate.value = false
}
const update = () => {
    editForm.post(route('admin.categories.update', editForm.id), {
        preserveScroll: true,
        onSuccess: () => {
            isShowUpdate.value = false
        }
    })
}
// Delete modal
const isShowDelete = ref(false)
const openDeleteModal = (app) => {
    isShowDelete.value = true
    editForm.id = app.id;
    editForm.name = app.name;
    editForm.google_name = app.google_name;
    editForm.google_url = app.google_url;
    editForm.slug = app.slug;
    editForm.type = app.type;
    editForm.status = app.status;
}
const closeDeleteModal = () => {
    isShowDelete.value = false
}
const syncApp = (app) => {
    loading.value = true
    router.post(route('admin.apps.sync'), {app}, {
        preserveState : true,
        preserveScroll: true,
        onSuccess: () => loading.value = false
    })
}
const app_id = ref(null)
const scrape = () => {
    loading.value = true
    router.get(route('admin.apps.index'), {app_id:app_id.value}, {
        preserveState : true,
        preserveScroll: true,
        onSuccess: (data) => {
            scrapApp.value = props.app
            loading.value = false
                console.log(data)
        },
    })
}
const scrapeSave = () => {
    let app = props.app
    loading.value = true
    router.post(route('admin.apps.scrape'), {app}, {
        preserveState : true,
        preserveScroll: true,
        onSuccess: (data) => {
            loading.value = false
            isShow.value = false
            app_id.value = null
            scrapApp.value = null;
        },
    })
}

const deleteCategory = () => {
    editForm.delete(route('admin.apps.destroy', editForm.id), {
        preserveScroll: true,
        onSuccess: () => {
            isShowDelete.value = false
        }
    })
}

const search = ref(props.search)
const type = ref(props.type)
const status = ref(props.status)
const sort = ref(props.sort)

watch([search, type, status, sort], debounce(function ([searchData, appType, statusType, sortBy]) {

    router.get(route('admin.apps.index', {search: searchData, type: appType, status: statusType, sort: sortBy}), {}, {
        preserveState: true,
         replace: true,
        preserveScroll: true,
        onSuccess: () => {

        }
    })
}), 300)
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
            class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
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
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">
                        <div
                            class="flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-900">
                            <div class="flex space-x-3">

                                <div>
                                    <label for="table-search" class="">Search</label>
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
                                <div class="">
                                    <label for="type" class="">Type</label>
                                    <v-select :options="typeOptions" :reduce="type => type.id" label="name"
                                              class="style-chooser w-48 " v-model="type"></v-select>
                                    <span class="text-lg text-red-600 font-semibold" v-if="errors.type">{{
                                            errors.type
                                        }}</span>
                                </div>
                                <div class="">
                                    <label for="type" class="">Status</label>
                                    <v-select :options="statusOptions" :reduce="status => status.id" label="name"
                                              class="style-chooser w-48 " v-model="status"></v-select>
                                    <span class="text-lg text-red-600 font-semibold" v-if="errors.status">{{
                                            errors.status
                                        }}</span>
                                </div>
                                <div class="">
                                    <label for="type" class="">Sort By</label>
                                    <v-select :options="sortOptions" :reduce="sort => sort.id" label="name"
                                              class="style-chooser w-60 " v-model="sort"></v-select>
                                    <span class="text-lg text-red-600 font-semibold" v-if="errors.sort">{{
                                            errors.sort
                                        }}</span>
                                </div>
                            </div>
                            <div>

                                <button
                                    type="button"
                                    @click="openModal"
                                    class="rounded-md bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                                >
                                    Add App
                                </button>
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
                                    <th scope="col" class="px-6 py-3" style="width: 30%;">
                                        Apps
                                    </th>
                                    <th scope="col" class="px-6 py-3" style="width: 10%;">
                                        Category
                                    </th>
                                    <th scope="col" class="px-6 py-3" style="width: 10%;">
                                        Developer
                                    </th>
                                    <th scope="col" class="px-6 py-3" style="width: 5%;">
                                        Type
                                    </th>
                                    <th scope="col" class="px-6 py-3" style="width: 10%;">
                                        Download
                                    </th>
                                    <th scope="col" class="px-6 py-3" style="width: 10%;">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3" style="width: 20%;">
                                        Action
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                                    v-for="(app,index) in apps.data" :key="index">
                                    <td class="p-4 text-lg font-semibold">
                                        {{ index + 1 }}
                                    </td>

                                    <th scope="row" class="p-2">
                                        <div class="flex space-x-3">
                                            <img :src="app.icon" alt="" width="70">
                                            <div class="flex flex-col">

                                                <span>
                                                    {{ app.title }}
                                                </span>
                                                <span class="text-xs">App ID: {{ app.app_id }}</span>
                                                <span class="text-xs">Slug: {{ app.slug }}</span>
                                                <div class="flex space-x-3 items-center">
                                                    <span class="text-lg">{{ app.rating }} </span>
                                                    <vue3-star-ratings v-model="app.rating" disableClick="false"
                                                                       inactiveColor="#cbd5e1"/>
                                                </div>
                                            </div>
                                        </div>

                                    </th>
                                    <td class="px-2 py-2">
                                        {{ app.category?.name }}
                                    </td>
                                    <td class="px-2 py-2">
                                        {{ app.developer?.name }}
                                    </td>
                                    <td class="px-2 py-2 capitalize">
                                        {{ app.type }}
                                    </td>
                                    <td class="px-2 py-2 capitalize">
                                        {{ app.downloads }}
                                    </td>
                                    <td class="px-2 py-2">
                  <span v-if="app.status"
                        class="bg-green-600 text-white py-1 px-2 rounded-md font-normal">Published</span>
                                        <span v-else class="bg-red-600 text-white py-1 px-2 rounded-md font-normal">Unpublished</span>
                                    </td>
                                    <td class="px-2 py-2 space-x-5">
                                        <Link :href="route('admin.apps.show',app)" class="text-blue-600">
                                            <ion-icon name="eye-outline"
                                                        class="pt-2 " size="large"></ion-icon></Link>
                                        <a href="#" class="font-medium  dark:text-blue-500 hover:underline"
                                           v-on:click="openUpdateModal(app)">
                                            <ion-icon name="create"
                                                      class="pt-2 " size="large"></ion-icon>
                                        </a>
                                        <button class="font-medium dark:text-blue-500 hover:underline "
                                                v-on:click="syncApp(app)">
                                            <ion-icon class="pt-2 text-green-600" name="sync-outline"
                                                      size="large"></ion-icon>
                                        </button>
                                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                           v-on:click="openDeleteModal(app)">
                                            <ion-icon class="pt-2 text-red-500" name="trash" size="large"></ion-icon>
                                        </a>
                                    </td>
                                </tr>

                                </tbody>
                            </table>

                            <Pagination :meta="apps.meta"></Pagination>
                        </div>

                        <div v-else
                             class="text-center text-4xl font-extrabold bg-gradient-to-t from-green-300 to-green-800 bg-clip-text text-transparent leading-normal">
                            Data Not Found
                        </div>
                    </div>


                </div>
                <!--Start Add Modal-->
                <Modal :show="isShow" :maxHeight="{'h-full':  scrapApp}">
                    <div class="px-10 pb-10 my-10" >
                        <div class="flex justify-between justify-items-center items-center py-2">
                            <DialogTitle
                                as="h3"
                                class="text-lg font-medium leading-6 text-gray-900"
                            >
                                Add New App
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
<div class="flex w-full items-center space-x-3">

                        <div class="mt-2 w-10/12">
                            <label for="app_id" class="text-lg font-semibold sr-only">App ID</label>
                            <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                                   id="app_id" v-model="app_id"
                                   placeholder="Enter Your App ID">
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="errors.app_id">{{ errors.app_id }}</span>
                        </div>
    <div class="">
        <button
            type="button"
            class="inline-flex justify-center rounded-md border border-transparent bg-blue-500 px-4 py-2 text-sm font-semibold hover:bg-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 text-white"
            @click="scrape()"
        >
            Scrape
        </button>
    </div>
</div>

                       <div v-if="scrapApp && app_id">
                          <div class="flex justify-between py-5">
                              <div class="mt-2 flex space-x-4 justify-items-center items-center">
                                  <label for="name" class="text-lg font-semibold">Publish Status</label>
                                  <Switch v-model="form.status" as="template" v-slot="{ checked }">
                                      <button
                                          class="relative inline-flex h-6 w-11 items-center rounded-full"
                                          :class="checked ? 'bg-blue-600' : 'bg-red-500'"
                                      >
                                          <span class="sr-only">Enable</span>
                                          <span
                                              :class="checked ? 'translate-x-6' : 'translate-x-1'"
                                              class="inline-block h-4 w-4 transform rounded-full bg-white transition"
                                          />
                                      </button>
                                  </Switch>
                                  <span class="text-lg text-red-600 font-semibold" v-if="errors.status">{{
                                          errors.status
                                      }}</span>
                              </div>
                              <div class="text-lg font-semibold text-red-600" v-if="scrapApp.status"> {{ scrapApp.status }}</div>
                              <button v-if="!scrapApp.status"
                                  type="button"
                                  class="inline-flex justify-center rounded-md border border-transparent bg-green-500 px-4 py-2 text-sm font-semibold hover:bg-green-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 text-white"
                                  @click="scrapeSave"
                              >
                                  Save
                              </button>
                          </div>
                           <div class="mt-2 flex space-x-0 justify-between border">
                               <div class="flex space-x-3">
                                   <div class="rounded-md">
                                       <img :src="scrapApp.icon" alt="" width="120" class="object-cover rounded-sm shadow-md">
                                       <div class="text-center capitalize text-3xl shadow-md bg-emerald-500 text-white">{{ scrapApp.type }}</div>
                                   </div>

                                   <div class="flex flex-col">

                                               <span  class="flex items-center">
                                                    <span class="text-lg">Title:</span>
                                                   <input type="text" class="px-2 py-0 rounded-md w-72 border-0"
                                                          v-model="scrapApp.title">
                                               </span>
                                       <span class="text-lg">App ID: {{ scrapApp.app_id }}</span>
                                       <span class="text-lg">Category: <a class="text-indigo-600 font-semibold" :href="scrapApp.category_url">{{scrapApp.category_name}}</a></span>
                                       <span class="text-lg">Developer: <a class="text-indigo-600 font-semibold" :href="scrapApp.developer_url">{{scrapApp.developer_name}}</a></span>
                                       <div class="flex space-x-3 items-center">
                                           <span class="text-lg">{{ scrapApp.rating }} </span>
                                           <vue3-star-ratings v-model="scrapApp.rating"
                                                              inactiveColor="#cbd5e1"/>
                                       </div>

                                   </div>
                               </div>
                               <div class="flex space-x-3">

                                   <div class="flex flex-col ">

                                                <span>
                                                    Updated On: {{ scrapApp.updated }}
                                                </span>
                                       <span>
                                                    Downloads: {{ scrapApp.downloads }}
                                                </span>
                                       <span>
                                                    Review: {{ scrapApp.review }}
                                                </span>
                                       <span>
                                                    Paid: {{ scrapApp.paid }}
                                                </span>

                                   </div>
                                   <div v-if="scrapApp.trailer" >
                                       <iframe class="rounded-md shadow-md" width="220" height="140"
                                               :src="scrapApp.trailer">
                                       </iframe>
                                   </div>

                               </div>
                           </div>
                           <div v-html="scrapApp.whats_new">

                           </div>
                          <div class="border mt-5 ">
                              <div class="flex space-x-3 overflow-x-scroll bg-emerald-500" >
                                  <img v-for="screenshot in scrapApp.screenshots" :src="screenshot" alt="" width="120" class="shadow-2xl rounded-sm border m-2 ">
                              </div>
                          </div>
                           <div class="mt-2">
                               <label for="app_id" class="text-lg font-semibold">Meta Description</label>
                               <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                                      id="app_id" v-model="scrapApp.meta_description"
                                      placeholder="Enter Your App ID">
                               <span class="text-lg text-red-600 font-semibold"
                                     v-if="errors.meta_description">{{ errors.meta_description }}</span>
                           </div>
                          <div class="mt-2">
                              <h2 class="text-2xl font-semibold border">Description</h2>
                              <p v-html="scrapApp.description" class="text-sm">

                              </p>
                          </div>


                       </div>


                    </div>
                </Modal>
                <!--End Add Modal-->

                <!--Start Update Modal-->
                <Modal :show="isShowUpdate">
                    <div class="p-10 my-10">
                        <div class="flex justify-between justify-items-center items-center py-2">
                            <DialogTitle
                                as="h3"
                                class="text-lg font-medium leading-6 text-gray-900"
                            >
                                Edit App
                            </DialogTitle>
                            <div>
                                <button type="button"
                                        class="text-5xl leading-6 text-gray-400 font-normal"
                                        @click="closeUpdateModal" data-bs-dismiss="modal" aria-label="Close">
                                    &times;
                                </button>
                            </div>

                        </div>
                        <hr>

                        <div class="mt-2">
                            <label for="name" class="text-lg font-semibold">Name</label>
                            <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                                   id="name" v-model="editForm.name"
                                   placeholder="Enter Your Category Name">
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="errors.name">{{ errors.name }}</span>
                        </div>

                        <div class="mt-2">
                            <label for="slug" class="text-lg font-semibold">Slug</label>
                            <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                                   id="slug" v-model="editForm.slug"
                                   placeholder="Enter Your Category Name">
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="errors.slug">{{ errors.slug }}</span>
                        </div>
                        <div class="mt-2">
                            <label for="google_name" class="text-lg font-semibold">Google Name</label>
                            <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                                   id="google_name" v-model="editForm.google_name"
                                   placeholder="Enter Your Category Google Name">
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="errors.google_name">{{ errors.google_name }}</span>
                        </div>
                        <div class="mt-2">
                            <label for="google_url" class="text-lg font-semibold">Google Url</label>
                            <input type="url" class="form-input px-4 py-3 rounded-md w-full my-2"
                                   id="google_url" v-model="editForm.google_url"
                                   placeholder="Enter Your Category Google URL">
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="errors.google_url">{{ errors.google_url }}</span>
                        </div>


                        <div class="mt-2">
                            <label for="type" class="text-lg font-semibold">Type</label>
                            <v-select :options="types" :reduce="type => type.id" label="name"
                                      class="style-chooser1 mt-2" v-model="editForm.type"></v-select>
                            <span class="text-lg text-red-600 font-semibold" v-if="errors.type">{{
                                    errors.type
                                }}</span>
                        </div>


                        <div class="mt-2 flex space-x-4 justify-items-center items-center">
                            <label for="name" class="text-lg font-semibold">Publish Status</label>
                            <Switch v-model="editForm.status" as="template" v-slot="{ checked }">
                                <button
                                    class="relative inline-flex h-6 w-11 items-center rounded-full"
                                    :class="checked ? 'bg-blue-600' : 'bg-red-500'"
                                >
                                    <span class="sr-only">Enable</span>
                                    <span
                                        :class="checked ? 'translate-x-6' : 'translate-x-1'"
                                        class="inline-block h-4 w-4 transform rounded-full bg-white transition"
                                    />
                                </button>
                            </Switch>
                            <span class="text-lg text-red-600 font-semibold" v-if="errors.status">{{
                                    errors.status
                                }}</span>
                        </div>
                        <div class="mt-4 float-end space-x-2">
                            <button
                                type="button"
                                class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                @click="update"
                            >
                                Submit
                            </button>
                            <button type="button"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                    @click="closeUpdateModal">Cancel
                            </button>
                        </div>
                    </div>
                </Modal>
                <!--End Update Modal-->
                <!--Start Delete Modal-->
                <Modal :show="isShowDelete">
                    <div class="pb-10 m-10 border-x border-t rounded-md">
                        <div class="flex justify-between justify-items-center items-center p-1  mb-2 rounded-md ">
                            <DialogTitle
                                as="h3"
                                class="text-lg font-medium leading-6 text-gray-900"
                            >
                                Delete App
                            </DialogTitle>
                            <div>
                                <button type="button"
                                        class="text-5xl leading-6 text-gray-400 font-normal"
                                        @click="closeDeleteModal" data-bs-dismiss="modal" aria-label="Close">
                                    &times;
                                </button>
                            </div>

                        </div>
                        <hr>

                        <div class="bg-red-100 p-5 text-red-700 space-y-2 rounded-md">
                            <h2 class="flex space-x-2">
                                <ion-icon name="alert-circle-outline" class="text-3xl"></ion-icon>
                                <span class="text-2xl">Delete App</span></h2>
                            <p class="px-10">You are sure you want to delete it?</p>
                        </div>


                        <div class="mt-4 space-x-2 float-end">
                            <button
                                type="button"
                                class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                @click="deleteCategory"
                            >
                                Delete
                            </button>
                            <button type="button"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                    @click="closeDeleteModal">Cancel
                            </button>
                        </div>
                    </div>
                </Modal>
                <!--End Delete Modal-->
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
    --vs-dropdown-min-width: 160px;
    --vs-dropdown-max-height: 1250px;
}
</style>
