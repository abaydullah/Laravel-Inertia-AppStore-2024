<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm, usePage} from '@inertiajs/vue3';
import Modal from "@/Components/Modal.vue";
import {Switch} from '@headlessui/vue'
import 'vue-select/dist/vue-select.css';
import {computed, reactive, ref,watch} from "vue";
import {createUpload} from "@mux/upchunk";
import vue3StarRatings from "vue3-star-ratings";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    app: Object,
    status: Number,
    sort: String,
    search: String,
    type: Number,
    errors: Object
})

const form = useForm({
    version: '',
    version_code: '',
    screen_dpi: '',
    sha1: '',
    sha256: '',
    md5: '',
    minimum_android: '',
    minimum_android_level: '',
    target_android: '',
    target_android_level: '',
    architecture: '',
    permissions: '',
    languages: '',
    signature: '',
    file: '',
    file_size: '',
    file_type: '',
    date: '',
    whats_new: '',
})
let editForm = useForm({
    _method: 'PUT',
    id: '',
    version: '',
    version_code: '',
    screen_dpi: '',
    sha1: '',
    sha256: '',
    md5: '',
    minimum_android: '',
    minimum_android_level: '',
    target_android: '',
    target_android_level: '',
    architecture: '',
    permissions: '',
    languages: '',
    signature: '',
    file: '',
    file_size: '',
    file_type: '',
    date: '',
    whats_new: '',
})

const file = ref(null)

const initialState = {
    file: null,
    uploader: null,
    progress: 0,
    uploading: false,
    error: null,
    success: ''
}
const state = reactive({
    ...initialState,
    formattedProgress: computed(()=> Math.round(state.progress)),
    reset: () => {
        Object.assign(state, initialState)
    }
})
let updateData = ref(null)
const uploadFile = () => {
    state.file = file.value.files[0]
    if (!state.file) {
        return;
    }

    state.uploader = createUpload({
        'endpoint': route('admin.apps.versions.store', props.app.id),
        'method': 'post',
        'headers': {
            'X-CSRF-TOKEN': usePage().props.csrf_token
        },
        file: state.file,
        'chunkSize': 10 * 1024
    })

    state.uploader.on('attempt', () => {
        state.error = null
        state.uploading = true
    })
    state.uploader.on('progress', (p) => {
        state.progress = p.detail
    })
    state.uploader.on('success', () => {
        state.reset()
        state.success = 'App Uploaded Successfully'
        router.reload({
            only: ['app'],
            preserveScroll: true
        })
    })
    state.uploader.on('error', (error) => {
        state.uploading = false

        state.error = error.detail.message
    })

}

const fileUpdate = ref(null)

const initialStateUpdate = {
    file: null,
    uploader: null,
    progress: 0,
    uploading: false,
    error: null,
    success: ''
}
const stateUpdate = reactive({
    ...initialStateUpdate,
    formattedProgress: computed(()=> Math.round(stateUpdate.progress)),
    reset: () => {
        Object.assign(stateUpdate, initialStateUpdate)
    }
})

const versionById = (id) => {
  return props.app?.versions.find(v=>v.id === id)

}

watch(()=> editForm.file,file=>{
    editForm = versionById(editForm.id)
})
const updateUploadFile = () => {
    stateUpdate.file = fileUpdate.value.files[0]
    if (!stateUpdate.file) {
        return;
    }
    stateUpdate.uploader = createUpload({
        'endpoint': route('admin.apps.versions.update', {app: props.app.id, version:editForm.id}),
        'headers': {
            'X-CSRF-TOKEN': usePage().props.csrf_token
        },
        file: stateUpdate.file,
        'chunkSize': 10 * 1024
    })

    stateUpdate.uploader.on('attempt', () => {
        stateUpdate.error = null
        stateUpdate.uploading = true
    })
    stateUpdate.uploader.on('progress', (p) => {
        stateUpdate.progress = p.detail
    })
    stateUpdate.uploader.on('success', () => {
        stateUpdate.reset()
        stateUpdate.success = 'App Uploaded Successfully'

        router.reload({
            only: ['app'],
            preserveScroll: true,
            preserveState: true,
        })
        setTimeout(()=>{
            let versionUpdate = versionById(editForm.id)
            editForm.version = versionUpdate.version
            editForm.version_code = versionUpdate.version_code
            editForm.screen_dpi = versionUpdate.screen_dpi
            editForm.sha1 = versionUpdate.sha1
            editForm.sha256 = versionUpdate.sha256
            editForm.md5 = versionUpdate.md5
            editForm.minimum_android = versionUpdate.minimum_android
            editForm.minimum_android_level = versionUpdate.minimum_android_level
            editForm.target_android = versionUpdate.target_android
            editForm.target_android_level = versionUpdate.target_android_level
            editForm.architecture = versionUpdate.architecture
            editForm.permissions = versionUpdate.permissions
            editForm.languages = versionUpdate.languages
            editForm.signature = versionUpdate.signature
            editForm.file = versionUpdate.file
            editForm.file_size = versionUpdate.file_size
            editForm.file_type = versionUpdate.file_type
            editForm.date = versionUpdate.date
            editForm.whats_new = versionUpdate.whats_new
        },500)

    })
    stateUpdate.uploader.on('error', (error) => {
        stateUpdate.reset()
        stateUpdate.uploading = false
        stateUpdate.error = error.detail.message
    })

}
//add modal
const isShow = ref(false)
const loading = ref(false)

const openModal = () => {
    isShow.value = true
}
const closeModal = () => {
    isShow.value = false
}
const cancel = () => {
    state.uploader.abort()
    state.reset()

}
// update modal
const isShowUpdate = ref(false)
const openUpdateModal = (app) => {
    isShowUpdate.value = true
    editForm = app
}
const closeUpdateModal = () => {
    isShowUpdate.value = false
}
const isShowDelete = ref(false)
const openDeleteModal = (app) => {
    isShowDelete.value = true
    editForm = app
}
const closeDeleteModal = () => {
    isShowDelete.value = false
}

const deleteVersion = () => {
    router.delete(route('admin.apps.versions.destroy', [props.app.id, editForm.id]), {
        preserveScroll: true,
        onSuccess: () => {
            isShowDelete.value = false
        }
    })
}
const update = () => {
    editForm.post(route('admin.apps.versions.update.data', [props.app.id, editForm.id]), {
        preserveScroll: true,
        onSuccess: () => {
            isShowUpdate.value = false
        }
    })
}

const syncApp = (app) => {
    loading.value = true
    router.post(route('admin.apps.sync'), {app}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => loading.value = false
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
            class="overflow-hidden bg-white shadow-sm sm:rounded-lg "
        >

            <div
                class="fixed top-0  flex items-center justify-center h-screen z-40 opacity-60 inset-1 w-full bg-slate-900"
                v-if="loading">
                <ion-icon name="sync"
                          class="absolute top-1/2 right-[40%] animate-spin text-9xl text-red-100 font-extrabold"></ion-icon>
            </div>


            <div class="p-6 text-gray-900">
                <div id="content" class="w-full">

                    <Header title="main.app-list"></Header>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5 ">
                        <div
                            class="flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-900 ">

                            <div v-if="app" class="w-full">
                                <div class="flex justify-between py-5 ">
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
                                    <button
                                        type="button"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-green-500 px-4 py-2 text-sm font-semibold hover:bg-green-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 text-white"
                                        @click="syncApp(app)"
                                    >
                                        Sync
                                    </button>
                                </div>
                                <div class="mt-2 flex justify-between border space-x-3"
                                     :class="`bg-no-repeat bg-center bg-[url('${app.cover_image_url}')]`">
                                    <div class="flex space-x-3 bg-gray-50">
                                        <div class="rounded-md ">
                                            <img :src="app.icon" alt="" width="240"
                                                 class="object-cover rounded-sm shadow-md">
                                            <div
                                                class="text-center capitalize text-3xl shadow-md bg-emerald-500 text-white">
                                                {{ app.type }}
                                            </div>
                                        </div>
                                        <div class="flex flex-col">

                                               <span class="flex items-center">
                                                    <span class="text-lg">Title:</span>
                                                   {{app.title}}
                                               </span>
                                            <span class="text-lg">App ID: {{ app.app_id }}</span>
                                            <span class="text-lg">Category: <a class="text-indigo-600 font-semibold"
                                                                               :href="app.category?.google_url">{{ app.category?.name }}</a></span>
                                            <span class="text-lg">Developer: <a class="text-indigo-600 font-semibold"
                                                                                :href="app.developer?.google_url">{{ app.developer?.name }}</a></span>

                                            <span>
                                                    Downloads: {{ app.downloads }}
                                                </span>
                                            <span>
                                                    Review: {{ app.review }}
                                                </span>
                                            <div class="flex space-x-3 items-center">
                                                <span class="text-lg">Rating: {{ app.rating }} </span>
                                                <vue3-star-ratings v-model="app.rating" disableClick="true"
                                                                   inactiveColor="#cbd5e1"/>
                                            </div>
                                            <span class="mt-5">
                                                    Updated On: {{ app.updated }}
                                                </span>
                                            <span class="mt-5">
                                                   Url : <a class="text-indigo-600 font-semibold" target="_blank"
                                                            :href="app.url">Google Play</a>
                                                </span>
                                        </div>
                                    </div>

                                    <div v-if="app.trailer">
                                        <iframe class="rounded-md shadow-md" width="400" height="280"
                                                :src="app.trailer">
                                        </iframe>
                                    </div>
                                </div>
                                <div v-if="app.whats_new">
                                    <h2 class="text-2xl font-semibold py-1 px-2 bg-emerald-50">What's New</h2>
                                    <p v-html="app.whats_new" class="p-5">

                                    </p>
                                </div>
                                <div class="border mt-5 " v-if="app.screenshots">
                                    <h2 class="text-2xl font-semibold p-1 px-2 bg-emerald-500 text-white shadow-2xl rounded-sm">
                                        Screenshots</h2>
                                    <div class="flex space-x-3 overflow-x-scroll bg-green-600">
                                        <img v-for="screenshot in app.screenshots" :src="screenshot.photo" alt=""
                                             class="shadow-2xl rounded-sm border m-2 max-w-[320px]">
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <h2 class="text-2xl font-semibold p-1 px-2 bg-emerald-500 text-white shadow-2xl rounded-sm">
                                        Description</h2>
                                    <p v-html="app.description" class="text-sm p-2 border">

                                    </p>
                                </div>


                            </div>

                        </div>

                    </div>

                    <div class="flex justify-between items-center mt-5">
                        <div class="w-11/12">
                            <h2 class="text-2xl font-semibold p-1 px-2 bg-emerald-50">Version</h2>

                        </div>
                        <div class="w-1/12">
                            <button
                                type="button"
                                @click="openModal"
                                class="rounded-md bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                            >
                                Add Version
                            </button>
                        </div>

                    </div>
                    <div v-if="app.versions">
                        <table
                            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border"
                            style="width: 100%">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border">
                            <tr>
                                <th scope="col" class="p-4" style="width: 5%;">

                                    ID

                                </th>
                                <th scope="col" class="px-6 py-3" style="width: 10%;">
                                    Uploaded
                                </th>
                                <th scope="col" class="px-6 py-3" style="width: 20%;">
                                    Version
                                </th>
                                <th scope="col" class="px-6 py-3" style="width: 5%;">
                                    Size
                                </th>
                                <th scope="col" class="px-6 py-3" style="width: 5%;">
                                    Type
                                </th>
                                <th scope="col" class="px-6 py-3" style="width: 10%;">
                                    Screen DPI
                                </th>
                                <th scope="col" class="px-6 py-3" style="width: 15%;">
                                    Minimum Required
                                </th>
                                <th scope="col" class="px-6 py-3" style="width: 15%;">
                                    Target
                                </th>
                                <th scope="col" class="px-6 py-3" style="width: 15%;">
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                                v-for="(app,index) in app.versions" :key="index">
                                <td class="p-4 text-lg font-semibold">
                                    {{ index + 1 }}
                                </td>
                                <td class="p-4 text-lg font-semibold">
                                    {{ app.date }}
                                </td>

                                <th scope="row" class="p-2">
                                    <div class="flex space-x-3">
                                     v{{app.version}} ({{app.version_code}})
                                    </div>

                                </th>
                                <td class="px-2 py-2">
                                    {{ app.file_size }}
                                </td>
                                <td class="px-2 py-2">
                                    {{ app.file_type }}
                                </td>
                                <td class="px-2 py-2 capitalize">
                                    {{ app.screen_dpi }}
                                </td>
                                <td class="px-2 py-2 capitalize">
                                    {{ app.minimum_android }} ({{app.minimum_android_level}})
                                </td>
                                <td class="px-2 py-2 capitalize">
                                    {{ app.target_android }} ({{app.target_android_level}})
                                </td>
                                <td class="px-2 py-2 space-x-5">
                                    <a href="#" class="font-medium  dark:text-blue-500 hover:underline"
                                       v-on:click="openUpdateModal(app)">
                                        <ion-icon name="create"
                                                  class="pt-2 " size="large"></ion-icon>
                                    </a>
                                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                       v-on:click="openDeleteModal(app)">
                                        <ion-icon class="pt-2 text-red-500" name="trash" size="large"></ion-icon>
                                    </a>
                                </td>
                            </tr>

                            </tbody>
                        </table>

<!--                        <Pagination :meta="apps.meta"></Pagination>-->
                    </div>

                    <div v-else
                         class="text-center text-4xl font-extrabold bg-gradient-to-t from-green-300 to-green-800 bg-clip-text text-transparent leading-normal">
                        Data Not Found
                    </div>
                </div>
                <!--Start Add Modal-->
                <Modal :show="isShow">
                    <div class="px-10 pb-10 my-10">
                        <div class="flex justify-between justify-items-center items-center py-2">
                            <DialogTitle
                                as="h3"
                                class="text-lg font-medium leading-6 text-gray-900"
                            >
                                Add New Version
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
                        <form @submit.prevent="uploadFile">
                            <div class="flex w-full items-center space-x-3">

                                <div class="mt-2 w-10/12">
                                    <label for="file" class="text-lg font-semibold sr-only">Apk File</label>
                                    <input type="file" class="form-input px-4 py-3 rounded-md w-full my-2"
                                           ref="file" name="file"
                                           placeholder="Enter Your App ID">
                                    <span class="text-lg text-red-600 font-semibold"
                                          v-if="errors.file">{{ errors.file }}</span>
                                </div>
                                <div class="">
                                    <button
                                        type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-500 px-4 py-2 text-sm font-semibold hover:bg-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 text-white"
                                    >
                                        Upload
                                    </button>
                                </div>

                            </div>
                        </form>
                        <div v-if="state.uploading">

                            <div class="bg-gray-200 h-4 overflow-hidden rounded">
                                <div class="bg-blue-700 h-full transition-all duration-200" :style="{width: state.progress+'%'}"></div>
                            </div>
                            <div class="flex justify-between items-center" >
                               <div class="flex items-center space-x-3">
                                   <button class="text-sm text-indigo-500" @click="cancel">Cancel</button>
                                   <button class="text-sm text-indigo-500" v-if="!state.uploader.paused" @click="state.uploader.pause()">Pause</button>
                                   <button class="text-sm text-indigo-500" v-else @click="state.uploader.resume()">Resume</button>
                               </div>
                                <div>{{ state.formattedProgress }}%</div>
                            </div>
                        </div>
                        <div v-if="state.error">
                             <span class="text-red-400 font-semibold text-lg">
                                    {{state.error}}
                                </span>

                        </div>
                        <div v-if="state.success">
                                <span class="text-green-400 font-semibold text-lg">
                                    {{state.success}}
                                </span>
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
                                Edit APK
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
                        <form @submit.prevent="updateUploadFile">
                            <div class="flex w-full items-center space-x-3">

                                <div class="mt-2 w-10/12">
                                    <label for="fileUpdate" class="text-lg font-semibold sr-only">Apk File</label>
                                    <input type="file" class="form-input px-4 py-3 rounded-md w-full my-2"
                                           ref="fileUpdate" name="fileUpdate"
                                         >
                                    <span class="text-lg text-red-600 font-semibold"
                                          v-if="errors.fileUpdate">{{ errors.fileUpdate }}</span>
                                </div>
                                <div class="">
                                    <button
                                        type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-500 px-4 py-2 text-sm font-semibold hover:bg-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 text-white"
                                    >
                                        Upload
                                    </button>
                                </div>

                            </div>
                        </form>
                        <div v-if="stateUpdate.uploading">

                            <div class="bg-gray-200 h-4 overflow-hidden rounded">
                                <div class="bg-blue-700 h-full transition-all duration-200" :style="{width: stateUpdate.progress+'%'}"></div>
                            </div>
                            <div class="flex justify-between items-center" >
                                <div class="flex items-center space-x-3">
                                    <button class="text-sm text-indigo-500" @click="cancel">Cancel</button>
                                    <button class="text-sm text-indigo-500" v-if="!stateUpdate.uploader.paused" @click="stateUpdate.uploader.pause()">Pause</button>
                                    <button class="text-sm text-indigo-500" v-else @click="stateUpdate.uploader.resume()">Resume</button>
                                </div>
                                <div>{{ stateUpdate.formattedProgress }}%</div>
                            </div>
                        </div>
                    <div class="grid grid-cols-2">
                        <div class="mt-2">
                            <label for="version" class="text-lg font-semibold">Version</label>
                            {{updateData}}
                            <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                                   id="version" v-model="editForm.version"
                                   placeholder="Enter Your Version">
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="errors.version">{{ errors.version }}</span>
                        </div>
                        <div class="mt-2">
                            <label for="version_code" class="text-lg font-semibold">Version Code</label>
                            <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                                   id="version_code" v-model="editForm.version_code"
                                   placeholder="Enter Your Version Code">
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="errors.version_code">{{ errors.version_code }}</span>
                        </div>
                        <div class="mt-2">
                            <label for="minimum_android" class="text-lg font-semibold">Minimum Android</label>
                            <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                                   id="minimum_android" v-model="editForm.minimum_android"
                                   placeholder="Enter Your Minimum Android">
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="errors.minimum_android">{{ errors.minimum_android }}</span>
                        </div>
                        <div class="mt-2">
                            <label for="target_android" class="text-lg font-semibold">Target Android</label>
                            <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                                   id="target_android" v-model="editForm.target_android"
                                   placeholder="Enter Your Target Android">
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="errors.target_android">{{ errors.target_android }}</span>
                        </div>   <div class="mt-2">
                            <label for="minimum_android_level" class="text-lg font-semibold">Minimum Android Level</label>
                            <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                                   id="minimum_android_level" v-model="editForm.minimum_android_level"
                                   placeholder="Enter Your Minimum Android Level">
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="errors.minimum_android_level">{{ errors.minimum_android_level }}</span>
                        </div>
                        <div class="mt-2">
                            <label for="target_android_level" class="text-lg font-semibold">Target Android Level</label>
                            <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                                   id="target_android_level" v-model="editForm.target_android_level"
                                   placeholder="Enter Your Target Android Level">
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="errors.target_android_level">{{ errors.target_android_level }}</span>
                        </div>
                        <div class="mt-2">
                            <label for="architecture" class="text-lg font-semibold">Architecture</label>
                            <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                                   id="architecture" v-model="editForm.architecture"
                                   placeholder="Enter Your Architecture">
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="errors.architecture">{{ errors.architecture }}</span>
                        </div>
                        <div class="mt-2">
                            <label for="screen_dpi" class="text-lg font-semibold">Screen DPI</label>
                            <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                                   id="screen_dpi" v-model="editForm.screen_dpi"
                                   placeholder="Enter Your Screen DPI">
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="errors.screen_dpi">{{ errors.screen_dpi }}</span>
                        </div>

                        <div class="mt-2">
                            <label for="languages" class="text-lg font-semibold">Languages</label>
                            <input type="text" class="form-input px-4 py-3 rounded-md w-full my-2"
                                   id="languages" v-model="editForm.languages"
                                   placeholder="Enter Your Languages">
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="errors.languages">{{ errors.languages }}</span>
                        </div>
                        <div class="mt-2">
                            <label for="date" class="text-lg font-semibold">Date</label>
                            <input type="date" class="form-input px-4 py-3 rounded-md w-full my-2"
                                   id="date" v-model="editForm.date">
                            <span class="text-lg text-red-600 font-semibold"
                                  v-if="errors.date">{{ errors.date }}</span>
                        </div>




                    </div>
                        <div class="mt-2">
                            <label for="date" class="text-lg font-semibold">What's New</label>
                            <TextInput v-model="form.whats_new" ></TextInput>
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
                                @click="deleteVersion"
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
