<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';


const showingNavigationDropdown = ref(false);

const visible = ref(false);
const hide = () => {
    visible.value = false;
};
const placement = 'bottom';

import { SettingOutlined, UserOutlined } from '@ant-design/icons-vue'
</script>

<template>
    <div class="">
        <header v-if="$slots.header" style="width: 100%; margin-top: -5px; background: #001529;">
            <div class="px-4">
                <div class="flex items-center justify-between">
                    <div class="flex shrink-0">
                        <a aria-current="page" class="flex items-center" href="/">
                            <img class="h-7 w-auto" src="/Logo/treasury.png" alt="">
                            <p class="sr-only">Website Title</p>
                        </a>
                    </div>
                    <div class="hidden md:flex md:items-center md:justify-center md:gap-5">
                        <a aria-current="page"
                            class="inline-block rounded-lg px-2 py-1 text-sm text-white transition-all duration-200 hover:bg-white hover:text-black"
                            href="admin/dashboard">Dashboard</a>
                        <!-- <a class="inline-block rounded-lg px-2 py-1 text-sm text-white transition-all duration-200 hover:bg-white hover:text-white"
                                href="#">Reports</a> -->
                        <a-dropdown arrow :placement="placement">
                            <a class="inline-block rounded-lg px-2 py-1 text-sm text-white transition-all duration-200 hover:bg-white-100 hover:text-white-900"
                                href="#">Reports</a> -->
                            <template #overlay>
                                <a-menu class="hovering">
                                    <a-menu-item>
                                        <a style="color: white;" href="/datedpdcchecks-reports">Dated/PostDated Checks
                                            Reports</a>
                                    </a-menu-item>
                                </a-menu>
                            </template>
                        </a-dropdown>
                        <a class="inline-block rounded-lg px-2 py-1 text-sm text-white transition-all duration-200 hover:bg-white hover:text-black"
                            href="/users">Usermaintinance</a>
                        <a class="inline-block rounded-lg px-2 py-1 text-sm text-white transition-all duration-200 hover:bg-white hover:text-black"
                            href="#">Adjustments</a>
                        <a class="inline-block rounded-lg px-2 py-1 text-sm text-white transition-all duration-200 hover:bg-white hover:text-black"
                            href="#">MasterFile</a>
                    </div>
                    <div class="flex items-center justify-end gap-3">
                        <a-popover v-model:open="visible" title="Settings" trigger="click">

                            <div style="color: white; font-size: 25px;">
                                <UserOutlined />
                            </div>

                            <template #content>
                                <div class="max-w-xs bg-white p-4 rounded-md">
                                    <div class="flex items-center justify-center mb-4">
                                        <img src="https://placekitten.com/100/100" alt="Profile Picture"
                                            class="rounded-full w-16 h-16">
                                    </div>
                                    <div class="text-center">
                                        <h2 class="text-lg font-semibold">{{ $page.props.auth.user.name }}</h2>
                                        <p class="text-white font-bold">Admin</p>
                                    </div>
                                    <ul class="mt-4">
                                        <li class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 12h18M3 6h18M3 18h18"></path>
                                            </svg>
                                            <span class="text-white">Location: New York</span>
                                        </li>
                                        <li class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                            <span class="text-white">Username: {{ $page.props.auth.user.username }}
                                            </span>
                                        </li>
                                        <li class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 6h16M4 12h16m-7 6h7"></path>
                                            </svg>
                                            <span class="text-white">{{ $page.props.auth.user.ContactNo }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="text-center">
                                    <a-button class="mt-2">
                                        <NavLink :href="route('logout')" method="post" as="button">
                                            Logout
                                        </NavLink>
                                    </a-button>
                                </div>
                            </template>
                        </a-popover>
                    </div>
                </div>
            </div>
        </header>


        <!-- Page Content -->
        <main>
            <slot />
        </main>
    </div>
</template>
<style scoped>
.icons {
    margin-top: 1px;
    height: 30px;
    background: white;
    border-radius: 10%;
}

.hovering {
    background-color: #001529;
}

.hovering:hover {
    color: green;
}


.b-icons {
    height: 16px;
    width: 100%
}
</style>
