<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const placements = 'bottom';

const showingNavigationDropdown = ref(false);

const visible = ref(false);
const hide = () => {
    visible.value = false;
};
</script>

<template>
    <div>
        <div class="min-h-screen">

            <header v-if="$slots.header" style="width: 100%; background: #092635; margin-top: -7px;"
                class="mt-1 bg-white mx-auto w-full w-full border-gray-100  py-3 shadow backdrop-blur-lg md:top-6">
                <div class="px-4">
                    <div class="flex items-center justify-between">
                        <div class="flex shrink-0">
                            <a aria-current="page" class="flex items-center" href="/">
                                <img class="h-7 w-auto" src="/Logo/treasury.png" alt="">
                                <p class="sr-only">Website Title</p>
                            </a>
                        </div>
                        <div class="hidden md:flex md:items-center md:justify-center md:gap-5">
                            <Link
                                class="inline-block rounded px-2 p-2 text-sm text-gray-900 transition-all duration-200"
                                :class="{ 'bg-gray-600 text-white': route().current('treasury_dashboard'), 'text-white': !route().current('treasury_dashboard') }"
                                :href="route('treasury_dashboard')">
                            Dashboard
                            </Link>
                            <Link
                                class="inline-block rounded px-2 p-2 text-sm text-gray-900 transition-all duration-200"
                                :class="{ 'bg-gray-600 text-white': route().current('indeximportupdates'), 'text-white': !route().current('indeximportupdates') }"
                                :href="route('indeximportupdates')">
                            Import&Update Checks
                            </Link>
                            <a-dropdown arrow :placement="placements">
                                <a class="inline-block rounded-lg px-2 py-1 text-sm text-white transition-all duration-200 hover:bg-gray-100 hover:text-gray-900"
                                    href="#">Check Receiving</a> -->
                                <template #overlay>
                                    <a-menu>
                                        <a-menu-item>
                                            <a :href="route('check_for.clearing')">Dated Cheques</a>
                                        </a-menu-item>
                                        <a-menu-item>
                                            <a :href="route('pdc_clearing.checks')">Post Dated Cheques</a>
                                        </a-menu-item>
                                        <a-menu-item>
                                            <a :href="route('leasing.checks')">Leasing Cheques</a>
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                            </a-dropdown>
                            <a-dropdown arrow :placement="placements">
                                <a class="inline-block rounded-lg px-2 py-1 text-sm text-white transition-all duration-200 hover:bg-gray-100 hover:text-gray-900"
                                    href="#">Dated Checks/Pdc</a> -->

                                <template #overlay>
                                    <a-menu>
                                        <a-menu-item>
                                            <Link :href="route('dated.checks')">Dated Checks</Link>
                                        </a-menu-item>
                                        <a-menu-item>
                                            <Link :href="route('pdc.checks')">Pdc Checks</Link>
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                            </a-dropdown>
                            <a-dropdown arrow :placement="placements">
                                <a class="inline-block rounded px-2 p-2 text-sm text-white transition-all duration-200"
                                    :class="{
                'bg-gray-600 text-white': route().current('bounce_tagging') || route().current('ds_tagging'),
                'text-black-100': !route().current('bounce_tagging') && !route().current('ds_tagging'),
            }" href="#">Ds/Bounce Tagging</a> -->

                                <template #overlay>
                                    <a-menu>
                                        <a-menu-item>
                                            <Link :href="route('ds_tagging')">Ds Tagging</Link>
                                        </a-menu-item>
                                        <a-menu-item>
                                            <Link :href="route('bounce_tagging')">Bounce Tagging</Link>
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                            </a-dropdown>
                            <a-dropdown arrow :placement="placements">
                                <a class="inline-block rounded-lg px-2 py-1 text-sm text-white transition-all duration-200 hover:bg-gray-100 hover:text-gray-900"
                                    href="#">Transaction</a> -->

                                <template #overlay>
                                    <a-menu>
                                        <a-menu-item>
                                            <Link :href="route('manual_entry.checks')">Check Manual Entry</Link>
                                        </a-menu-item>
                                        <a-menu-item>
                                            <Link :href="route('mergechecks.checks')">Merge Checks</Link>
                                        </a-menu-item>
                                        <a-menu-item>
                                            <Link :href="route('bounce.checks')">Bounced Checks</Link>
                                        </a-menu-item>
                                        <a-menu-item>
                                            <Link :href="route('replace.checks')">Replacement Checks</Link>
                                        </a-menu-item>
                                        <a-menu-item>
                                            <Link :href="route('partial_payments.checks')">Partial Payment</Link>
                                        </a-menu-item>
                                        <a-menu-item>
                                            <Link :href="route('dcpdc.checks')">Dated/Pdc Reports</Link>
                                        </a-menu-item>
                                        <a-menu-item>
                                            <Link :href="route('duePdcReports.checks')">Due Pdc Reports</Link>
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                            </a-dropdown>
                        </div>
                        <div class="flex items-center justify-end gap-3">
                            <a-popover v-model:open="visible" title="Settings" trigger="click">
                                <div style="color: white; font-size: 25px; cursor: pointer;">
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
                                            <p class="text-gray-500 font-bold">Admin</p>
                                        </div>
                                        <ul class="mt-4">
                                            <li class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M3 12h18M3 6h18M3 18h18"></path>
                                                </svg>
                                                <span class="text-gray-700">Location: New York</span>
                                            </li>
                                            <li class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                                <span class="text-gray-700">Username: {{ $page.props.auth.user.username
                                                    }}
                                                </span>
                                            </li>
                                            <li class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                                                </svg>
                                                <span class="text-gray-700">{{ $page.props.auth.user.ContactNo }}</span>
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
    </div>
</template>

<style>
.icons {
    margin-top: 1px;
    height: 30px;
    background: white;
    border-radius: 10%;
}

.b-icons {
    height: 16px;
    width: 100%
}
</style>
