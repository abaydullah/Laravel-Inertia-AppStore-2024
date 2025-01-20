<script setup>
import vue3StarRatings from "vue3-star-ratings";
import {router, useForm} from "@inertiajs/vue3";
import EmojiPicker from 'vue3-emoji-picker'
import 'vue3-emoji-picker/css'
import {computed, ref, watch} from "vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps({
    app: Object,
    errors: Object
})
const form = useForm({
    app_id: props.app.id,
    rating: 0,
    review: '',
    name: ''
})
let editForm = useForm({
    _method: 'PUT',
    id: '',
    app_id: props.app.id,
    rating: 0,
    review: '',
    name: '',
    ip: '',
})
const isOpenEditBox = ref(false)
const openEditBox = (review) => {
    editForm.id = review.id
    editForm.app_id = review.app_id
    editForm.rating = review.rating
    editForm.review = review.review
    editForm.name = review.name
    editForm.ip = review.ip
    isOpenEditBox.value = true

}

const closeEditBox = () => {
    isOpenEditBox.value = false;
    editForm.reset()

}
const isOpenDeleteBox = ref(false)
const openDeleteBox = (review) => {
    editForm.id = review.id
    editForm.app_id = review.app_id
    editForm.rating = review.rating
    editForm.review = review.review
    editForm.name = review.name
    editForm.ip = review.ip
    isOpenDeleteBox.value = true

}
const closeDeleteBox = () => {
    isOpenDeleteBox.value = false;
    editForm.reset()

}

const isComplete = ref(computed(() => getBoolean(form.review) && getBoolean(form.name) && form.rating > 0))

function getBoolean(value) {
    if (value.toString().length) {
        return true;
    }
    return false;
}

const storeReview = () => {
    form.post(route('app.view.review.store'), {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
        }
    })
}
const updateReview = () => {
    editForm.post(route('app.view.review.update', editForm.id), {
        preserveScroll: true,
        onSuccess: () => {
            editForm.reset()
        }
    })
}
const deleteReview = () => {
    editForm.delete(route('app.view.review.destroy', editForm.id), {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            editForm.reset()
            isOpenDeleteBox.value = false;
        }
    })
}
watch(() => form.rating, (value) => {
    form.rating = Math.round(value)
})
watch(() => editForm.rating, (value) => {
    editForm.rating = Math.round(value)
})

function onChangeText(text) {
    form.review = text
}

function onChangeTextUpdate(text) {
    editForm.review = text
}

const reviewMax = ref(5)
const reviewMore = (max) => {
    if (reviewMax.value + 5 < max) {
        reviewMax.value += 5;
    } else {
        reviewMax.value = max
    }
}
const reviewLess = (max) => {
    reviewMax.value = 5;

}

</script>

<template>
    <div class="w-full">
        <div
            class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-2"
        >
            <div class="px-4 py-2">
                <div class="flex w-full">
                    <div class="flex flex-col w-2/12 items-center justify-items-center space-y-2">
                        <span class="text-lg font-semibold">Rating</span>
                        <span class="text-5xl">{{ app.review_count.data.rating }}</span>
                        <span>{{ app.review_count.data.total }} Reviews</span>
                    </div>
                    <div class="w-10/12">
                        <div class="flex space-x-3 items-center">
                            <div>5</div>
                            <div class="bg-green-500 w-full h-2 rounded-md"
                                 :style="`width: ${app.review_count.data.five}%`"></div>

                        </div>
                        <div class="flex space-x-3 items-center">
                            <div>4</div>
                            <div class="bg-[#AED888] w-full h-2 rounded-md"
                                 :style="`width: ${app.review_count.data.four}%`"></div>

                        </div>
                        <div class="flex space-x-3 items-center">
                            <div>3</div>
                            <div class="bg-[#FFD935] w-full h-2 rounded-md"
                                 :style="`width: ${app.review_count.data.three}%`"></div>

                        </div>
                        <div class="flex space-x-3 items-center">
                            <div>2</div>
                            <div class="bg-[#FFB235] w-full h-2 rounded-md"
                                 :style="`width: ${app.review_count.data.two}%`"></div>

                        </div>
                        <div class="flex space-x-3 items-center">
                            <div>1</div>
                            <div class="bg-[#FF8C5A] w-full h-2 rounded-md"
                                 :style="`width: ${app.review_count.data.one}%`"></div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div
            class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-2"
        >
            <div class="px-4 py-10">
                <EmojiPicker :native="true" pickerType="textarea" class="w-full" @update:text="onChangeText"
                             :text="form.review"/>

                <div class="w-full h-10 bg-gray-200 flex items-center justify-between px-10">
                    <div class="flex items-center space-x-3">
                        <InputLabel>Name :</InputLabel>
                        <TextInput v-model="form.name"></TextInput>
                    </div>
                    <div class="">
                        <vue3-star-ratings star-size="25" v-model="form.rating"
                                           inactiveColor="#cbd5e1" in :numberOfStars="5"/>
                    </div>

                </div>

                <div class="float-right mt-2">
                    <button @click="storeReview" :disabled="!isComplete" :class="{'bg-green-500': isComplete}"
                            class="flex items-center space-x-2 disabled:bg-gray-400 text-gray-50 py-2 px-4 rounded-md disabled:cursor-not-allowed">
                        <span>Comment</span>
                        <ion-icon name="send-outline"></ion-icon>
                    </button>
                </div>
            </div>

        </div>
        <div
            class="overflow-hidden  shadow-sm sm:rounded-lg mt-2"
        >
            <div class="divide-y space-y-2">
                <div class="" v-for="review in app.reviews.slice(0,reviewMax)">

                    <div class="flex rounded-md  bg-white py-5 px-2">
                        <div class="w-4/12 flex justify-between">
                            <div class="w-4/12 mr-2">
                                <img :src="review.icon" width="80" alt="" class="rounded-md">
                            </div>
                            <div class="w-8/12">
                                <h4>{{ review.name }}</h4>
                                <div>

                                    <vue3-star-ratings v-model="review.rating" star-size="15"
                                                       inactiveColor="#cbd5e1" :disableClick="true"/>
                                </div>
                                <div> {{ review.date }}</div>
                            </div>
                        </div>
                        <div class="w-6/12">
                            <p>{{ review.review }}</p>

                        </div>
                        <div class="w-2/12">

                            <div class="flex space-x-3">

                                <button v-if="review.ip===$page.props.ip" type="button"
                                        class="bg-blue-600 hover:bg-blue-800 py-1 px-2 text-white rounded-md font-semibold transition-all duration-300"
                                        @click="openEditBox(review)">Edit
                                </button>
                                <button v-if="review.ip===$page.props.ip" type="button"
                                        class="bg-red-600 hover:bg-red-700 py-1 px-2 text-white rounded-md font-semibold transition-all duration-300"
                                        @click="openDeleteBox(review)">Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="inline-block w-full" v-if="review.id === editForm.id">
                        <EmojiPicker pickerType="textarea" @update:text="onChangeTextUpdate" class="w-full"
                                     :text="review.review"/>

                        <div class="w-full h-10 bg-gray-200 flex items-center justify-between px-10">
                            <div class="flex items-center space-x-3">
                                <InputLabel>Name :</InputLabel>
                                <TextInput v-model="editForm.name"></TextInput>
                            </div>
                            <div class="">
                                <vue3-star-ratings star-size="25" v-model="editForm.rating"
                                                   inactiveColor="#cbd5e1" in :numberOfStars="5"/>
                            </div>

                        </div>
                        <div class="flex justify-between mt-2">
                            <button @click="closeEditBox"

                                    class="flex items-center space-x-2  text-gray-50 py-2 px-4 rounded-md bg-red-500">
                                <span>Cancel</span>
                                <ion-icon name="close-outline"></ion-icon>
                            </button>
                            <button @click="updateReview" :disabled="!editForm.isDirty"
                                    :class="{'bg-green-500': editForm.isDirty}"
                                    class="flex items-center space-x-2 disabled:bg-gray-400 text-gray-50 py-2 px-4 rounded-md disabled:cursor-not-allowed">
                                <span>Update Comment</span>
                                <ion-icon name="send-outline"></ion-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center pt-5 border-t" v-if="app.reviews.length > 5">
                <Button v-if="reviewMax!==app.reviews.length"
                        class="px-32 py-2 bg-gray-50 border-2 border-green-500 hover:border-green-200 rounded-md hover:bg-green-500 hover:text-gray-50 transition-all duration-300"
                        @click="reviewMore(app.reviews.length)">
                    Show More
                </Button>
                <Button v-else
                        class="px-32 py-2 bg-gray-50 border-2 border-green-500 hover:border-green-200 rounded-md hover:bg-green-500 hover:text-gray-50 transition-all duration-300"
                        @click="reviewLess(app.reviews.length)">
                    Show Less
                </Button>
            </div>
        </div>
        <!--Start Delete Modal-->
        <Modal :show="isOpenDeleteBox">
            <div class="pb-10 m-10 border-x border-t rounded-md">
                <div class="flex justify-between justify-items-center items-center p-1  mb-2 rounded-md ">
                    <DialogTitle
                        as="h3"
                        class="text-lg font-medium leading-6 text-gray-900"
                    >
                        Delete Review
                    </DialogTitle>
                    <div>
                        <button type="button"
                                class="text-5xl leading-6 text-gray-400 font-normal"
                                @click="closeDeleteBox" data-bs-dismiss="modal" aria-label="Close">
                            &times;
                        </button>
                    </div>

                </div>
                <hr>

                <div class="bg-red-100 p-5 text-red-700 space-y-2 rounded-md">
                    <h2 class="flex space-x-2"><ion-icon name="alert-circle-outline" class="text-3xl"></ion-icon> <span class="text-2xl">Delete Review</span></h2>
                    <p class="px-10">You are sure you want to delete It?</p>
                </div>


                <div class="mt-4 space-x-2 float-end">
                    <button
                        type="button"
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                        @click="deleteReview"
                    >
                        Delete
                    </button>
                    <button type="button"
                            class="inline-flex justify-center rounded-md border border-transparent bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                            @click="closeDeleteBox">Cancel
                    </button>
                </div>
            </div>
        </Modal>
        <!--End Delete Modal-->
    </div>
</template>

<style scoped>

</style>
