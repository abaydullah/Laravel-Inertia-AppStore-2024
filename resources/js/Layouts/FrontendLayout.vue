<script setup>
import {computed, onMounted, ref} from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import {Link, router, useForm, usePage} from '@inertiajs/vue3';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import TextInput from "@/Components/TextInput.vue";
import {search} from "ionicons/icons";
import TopRated from "@/Pages/Frontend/App/Partials/TopRated.vue";
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const showingNavigationDropdown = ref(false);
const selectedTab = ref(0)
const page = usePage();
const changeTab = (index) => {
    selectedTab.value = index
}

const form = useForm({
    search: page.url.split('q=',)[1],
})


onMounted(()=>{
   let category = page.url.replace('/categories/','')
   let cat = page.props.apps_categories.find(cat =>cat.slug === category)
    if (typeof cat == "undefined"){
        selectedTab.value = 1
    }else{
        selectedTab.value = 0
    }
})
const currentDate = new Date().getFullYear()
const searchResult = () => {
    router.post(route('app.search')+'?q='+form.search,{

    })
}
</script>

<template>
    <div class="relative">
        <div class="min-h-screen bg-gray-100 pb-10">
            <nav
                class="border-b border-gray-100 bg-white"
            >
                <!-- Primary Navigation Menu -->
             <div class="mx-auto max-w-8xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('home')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    :href="route('home')"
                                    :active="route().current('home')"
                                >
                                    {{appName}}
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3" v-if="page.auth">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                            >
                                                Profile

                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
<!--                        <ResponsiveNavLink-->
<!--                            :href="route('admin.dashboard')"-->
<!--                            :active="route().current('admin.dashboard')"-->
<!--                        >-->
<!--                            Dashboard-->
<!--                        </ResponsiveNavLink>-->
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-gray-800"
                            >

                            </div>
                            <div class="text-sm font-medium text-gray-500">

                            </div>
                        </div>

                        <div class="mt-3 space-y-1">

                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->


            <!-- Page Content -->
            <main>
                <div class="py-4 mx-auto sm:px-6 lg:px-8">
                    <div class="md:flex justify-between gap-2 mx-auto w-11/12">
                        <div class="w-full md:w-2/12">
                            <div
                                class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                            >
                                <TabGroup :selectedIndex="selectedTab" @change="changeTab">
                                    <TabList class="flex justify-between">
                                        <Tab as="template"> <button
                                            :class="[
              'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
              'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2 border ',
              selectedTab===0
               ? 'bg-slate-500 text-gray-50 shadow font-semibold'
                : 'text-blue-500 hover:bg-green-600 hover:text-white',
            ]"
                                        >
                                           Apps
                                        </button></Tab>
                                        <Tab as="template"><button
                                            :class="[
              'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
              'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2 border',
              selectedTab===1
                ? 'bg-slate-500 text-gray-50 shadow font-semibold'
                : 'text-blue-500 hover:bg-green-600 hover:text-white',
            ]"
                                        >
                                            Games
                                        </button></Tab>

                                    </TabList>
                                    <TabPanels>
                                        <TabPanel key="app"
                                                  :class="[
            'rounded-xl bg-white p-3',
            'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
          ]">   <div class="p-6 text-gray-900">
                                            <h2 class="bg-green-600 p-2 rounded text-white font-semibold">Apps</h2>
                                            <nav>
                                                <ul class="divide-y-2">
                                                    <li class="nav-menu-item" :class="{active : route().current('categories.view',category.slug)}" v-for="category in $page.props.apps_categories"><Link :href="route('categories.view',category.slug)">
                                                        {{ category.name }}</Link></li>
                                                </ul>
                                            </nav>
                                        </div>
                                        </TabPanel>
                                        <TabPanel key="game"
                                                   :class="[
            'rounded-xl bg-white p-3',
            'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
          ]">  <div class="p-6 text-gray-900">
                                            <h2 class="bg-green-600 p-2 rounded text-white font-semibold">Games</h2>
                                            <nav>
                                                <ul class="divide-y-2">
                                                    <li class="nav-menu-item" :class="{active : route().current('categories.view',category.slug)}" v-for="category in $page.props.games_categories"><Link :href="route('categories.view',category.slug)">
                                                        {{ category.name }}</Link></li>
                                                </ul>
                                            </nav>
                                        </div></TabPanel>
                                    </TabPanels>
                                </TabGroup>


                            </div>

                        </div>
                        <div class="w-full md:w-8/12">

                            <slot />


                        </div>
                        <div class="w-full md:w-2/12">
                            <div
                                class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                            >
                                <div class="">
                                    <Label class="flex justify-between items-center justify-items-center w-full h-full">
                                        <TextInput v-model="form.search" class="border-2 border-green-500 rounded-l-md w-9/12" @keydown.enter="searchResult" placeholder="Search Here"> </TextInput><Button @click="searchResult" class="bg-green-500 border-2 border-green-500 w-3/12 flex items-center h-full justify-center"><ion-icon class="text-white size-6 py-2" name="search-outline"></ion-icon></Button>
                                    </Label>
                                </div>
                                <div class="">
                                    <TopRated :apps="page.props.top_rated_apps"/>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


            </main>
        </div>
        <div class="w-full text-center absolute bottom-0 bg-red-50/40">© {{currentDate}} All Rights Reserved by MD Mim Abaydullah.</div>
    </div>
</template>
