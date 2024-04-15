<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

const placements = "bottom";

const showingNavigationDropdown = ref(false);

const visible = ref(false);
const hide = () => {
    visible.value = false;
};
</script>

<template>
    <div>
        <div class="min-h-screen">
            <header
                v-if="$slots.header"
                style="width: 100%; background: #001529; margin-top: -7px"
            >
                <div class="px-4">
                    <div class="flex items-center justify-between">
                        <div class="flex shrink-0">
                            <a
                                aria-current="page"
                                class="flex items-center"
                                href="/"
                            >
                                <img
                                    class="h-7 w-auto"
                                    src="/Logo/treasury.png"
                                    alt=""
                                />
                                <p class="sr-only">Website Title</p>
                            </a>
                        </div>
                        <div
                            class="hidden md:flex md:items-center md:justify-center md:gap-3"
                        >
                            <Link
                                class="inline-block px-3 p-4 mt-2 text-sm text-gray-900 transition-all duration-200"
                                :class="{
                                    'bg-blue-600 text-white':
                                        route().current('treasury.dashboard'),
                                    'text-white':
                                        !route().current('treasury.dashboard'),
                                }"
                                :href="route('treasury.dashboard')"
                            >
                                <DashboardOutlined /> Dashboard
                            </Link>
                            <Link
                                class="inline-block px-3 p-4 mt-2 text-sm text-gray-900 transition-all duration-200"
                                :class="{
                                    'bg-blue-600 text-white':
                                        route().current('indeximportupdates'),
                                    'text-white':
                                        !route().current('indeximportupdates'),
                                }"
                                :href="route('indeximportupdates')"
                            >
                                <CloudUploadOutlined /> Import&Update Checks
                            </Link>
                            <a-dropdown arrow :placement="placements">
                                <a
                                    :class="{
                                        'bg-blue-600 text-white':
                                            route().current(
                                                'check_for.clearing'
                                            ) ||
                                            route().current(
                                                'pdc_clearing.checks'
                                            ) ||
                                            route().current('leasing.checks'),
                                        'text-white':
                                            !route().current(
                                                'check_for.clearing'
                                            ) ||
                                            !route().current(
                                                'pdc_clearing.checks'
                                            ) ||
                                            !route().current('leasing.checks'),
                                    }"
                                    class="inline-block px-3 p-4 mt-2 text-sm text-gray-900 transition-all duration-200"
                                    href="#"
                                >
                                    <CheckCircleOutlined /> Check Receiving</a
                                >
                                -->
                                <template #overlay>
                                    <a-menu style="background-color: #001529">
                                        <a-menu-item
                                            :class="{
                                                'bg-blue-600 text-white':
                                                    route().current(
                                                        'check_for.clearing'
                                                    ),
                                                'text-white':
                                                    !route().current(
                                                        'check_for.clearing'
                                                    ),
                                            }"
                                        >
                                            <Link
                                                style="color: white"
                                                :href="
                                                    route('check_for.clearing')
                                                "
                                                >Dated Cheques</Link
                                            >
                                        </a-menu-item>
                                        <a-menu-item
                                            :class="{
                                                'bg-blue-600 text-white':
                                                    route().current(
                                                        'pdc_clearing.checks'
                                                    ),
                                                'text-white': !route().current(
                                                    'pdc_clearing.checks'
                                                ),
                                            }"
                                        >
                                            <Link
                                                style="color: white"
                                                :href="
                                                    route('pdc_clearing.checks')
                                                "
                                                >Post Dated Cheques</Link
                                            >
                                        </a-menu-item>
                                        <a-menu-item
                                            :class="{
                                                'bg-blue-600 text-white':
                                                    route().current(
                                                        'leasing.checks'
                                                    ),
                                                'text-white':
                                                    !route().current(
                                                        'leasing.checks'
                                                    ),
                                            }"
                                        >
                                            <Link
                                                style="color: white"
                                                :href="route('leasing.checks')"
                                                >Leasing Cheques</Link
                                            >
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                            </a-dropdown>
                            <a-dropdown arrow :placement="placements">
                                <a
                                    :class="{
                                        'bg-blue-600 text-white':
                                            route().current('dated.checks') ||
                                            route().current('pdc.checks'),
                                        'text-white':
                                            !route().current('dated.checks') ||
                                            !route().current('pdc.checks'),
                                    }"
                                    class="inline-block px-3 p-4 mt-2 text-sm text-gray-900 transition-all duration-200"
                                    href="#"
                                    ><CalendarOutlined /> Dated Checks/Pdc</a
                                >
                                -->

                                <template #overlay>
                                    <a-menu style="background-color: #001529">
                                        <a-menu-item
                                            style="color: white"
                                            :class="{
                                                'bg-blue-600 text-white':
                                                    route().current(
                                                        'dated.checks'
                                                    ),
                                                'text-white':
                                                    !route().current(
                                                        'dated.checks'
                                                    ),
                                            }"
                                        >
                                            <Link :href="route('dated.checks')"
                                                >Dated Checks</Link
                                            >
                                        </a-menu-item>
                                        <a-menu-item
                                            style="color: white"
                                            :class="{
                                                'bg-blue-600 text-white':
                                                    route().current(
                                                        'pdc.checks'
                                                    ),
                                                'text-white':
                                                    !route().current(
                                                        'pdc.checks'
                                                    ),
                                            }"
                                        >
                                            <Link :href="route('pdc.checks')"
                                                >Post Dated Checks</Link
                                            >
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                            </a-dropdown>
                            <a-dropdown arrow :placement="placements">
                                <a
                                    class="inline-block px-3 mt-2 p-4 text-sm text-white transition-all duration-200"
                                    :class="{
                                        'bg-blue-600 text-white':
                                            route().current('bounce_tagging') ||
                                            route().current('ds_tagging'),
                                        'text-black-100':
                                            !route().current(
                                                'bounce_tagging'
                                            ) && !route().current('ds_tagging'),
                                    }"
                                    href="#"
                                    ><FundOutlined /> Ds/Bounce Tagging</a
                                >
                                -->

                                <template #overlay>
                                    <a-menu style="background-color: #001529">
                                        <a-menu-item style="color: white">
                                            <Link :href="route('ds_tagging')"
                                                >Deposited Tagging</Link
                                            >
                                        </a-menu-item>
                                        <a-menu-item
                                            style="color: white"
                                            :class="{
                                                'bg-blue-600 text-white':
                                                    route().current(
                                                        'bounce_tagging'
                                                    ),
                                                'text-white':
                                                    !route().current(
                                                        'bounce_tagging'
                                                    ),
                                            }"
                                        >
                                            <Link
                                                :href="route('bounce_tagging')"
                                                >Bounce Tagging</Link
                                            >
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                            </a-dropdown>
                            <a-dropdown arrow :placement="placements">
                                <a
                                    :class="{
                                        'bg-blue-600 text-white':
                                            route().current(
                                                'manual_entry.checks'
                                            ) ||
                                            route().current(
                                                'mergechecks.checks'
                                            ) ||
                                            route().current('bounce.checks') ||
                                            route().current('replace.checks') ||
                                            route().current(
                                                'partial_payments.checks'
                                            ) ||
                                            route().current('dcpdc.checks') ||
                                            route().current(
                                                'duePdcReports.checks'
                                            ),
                                        'text-white':
                                            !route().current(
                                                'manual_entry.checks'
                                            ) ||
                                            !route().current(
                                                'mergechecks.checks'
                                            ) ||
                                            !route().current('bounce.checks') ||
                                            !route().current(
                                                'replace.checks'
                                            ) ||
                                            !route().current(
                                                'partial_payments.checks'
                                            ) ||
                                            !route().current('dcpdc.checks') ||
                                            !route().current(
                                                'duePdcReports.checks'
                                            ),
                                    }"
                                    class="inline-block px-3 mt-2 p-4 text-sm text-white transition-all duration-200"
                                    href="#"
                                    ><ContactsOutlined /> Transaction</a

                                >
                                -->

                                <template #overlay>
                                    <a-menu style="background-color: #001529">
                                        <a-menu-item
                                            style="color: white"
                                            :class="{
                                                'bg-blue-600 text-white':
                                                    route().current(
                                                        'manual_entry.checks'
                                                    ),
                                                'text-white': !route().current(
                                                    'manual_entry.checks'
                                                ),
                                            }"
                                        >
                                            <Link
                                                :href="
                                                    route('manual_entry.checks')
                                                "
                                                >Check Manual Entry</Link
                                            >
                                        </a-menu-item>
                                        <a-menu-item
                                            style="color: white"
                                            :class="{
                                                'bg-blue-600 text-white':
                                                    route().current(
                                                        'mergechecks.checks'
                                                    ),
                                                'text-white':
                                                    !route().current(
                                                        'mergechecks.checks'
                                                    ),
                                            }"
                                        >
                                            <Link
                                                :href="
                                                    route('mergechecks.checks')
                                                "
                                                >Merge Checks</Link
                                            >
                                        </a-menu-item>
                                        <a-menu-item
                                            style="color: white"
                                            :class="{
                                                'bg-blue-600 text-white':
                                                    route().current(
                                                        'bounce.checks'
                                                    ),
                                                'text-white':
                                                    !route().current(
                                                        'bounce.checks'
                                                    ),
                                            }"
                                        >
                                            <Link :href="route('bounce.checks')"
                                                >Bounced Checks</Link
                                            >
                                        </a-menu-item>
                                        <a-menu-item
                                            style="color: white"
                                            :class="{
                                                'bg-blue-600 text-white':
                                                    route().current(
                                                        'replace.checks'
                                                    ),
                                                'text-white':
                                                    !route().current(
                                                        'replace.checks'
                                                    ),
                                            }"
                                        >
                                            <Link
                                                :href="route('replace.checks')"
                                                >Replacement Checks</Link
                                            >
                                        </a-menu-item>
                                        <a-menu-item
                                            style="color: white"
                                            :class="{
                                                'bg-blue-600 text-white':
                                                    route().current(
                                                        'partial_payments.checks'
                                                    ),
                                                'text-white': !route().current(
                                                    'partial_payments.checks'
                                                ),
                                            }"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'partial_payments.checks'
                                                    )
                                                "
                                                >Partial Payment</Link
                                            >
                                        </a-menu-item>
                                        <a-menu-item
                                            style="color: white"
                                            :class="{
                                                'bg-blue-600 text-white':
                                                    route().current(
                                                        'dcpdc.checks'
                                                    ),
                                                'text-white':
                                                    !route().current(
                                                        'dcpdc.checks'
                                                    ),
                                            }"
                                        >
                                            <Link :href="route('dcpdc.checks')"
                                                >Dated / Post Dated Checks
                                                Reports</Link
                                            >
                                        </a-menu-item>
                                        <a-menu-item
                                            style="color: white"
                                            :class="{
                                                'bg-blue-600 text-white':
                                                    route().current(
                                                        'duePdcReports.checks'
                                                    ),
                                                'text-white': !route().current(
                                                    'duePdcReports.checks'
                                                ),
                                            }"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'duePdcReports.checks'
                                                    )
                                                "
                                                >Due / Post Dated Checks
                                                Reports</Link
                                            >
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                            </a-dropdown>
                        </div>
                        <div class="flex items-center justify-end gap-3">
                            <a-popover
                                v-model:open="visible"
                                title="Settings"
                                trigger="click"
                            >
                                <div
                                    style="
                                        color: white;
                                        font-size: 25px;
                                        cursor: pointer;
                                    "
                                >
                                    <UserOutlined />
                                </div>

                                <template #content>
                                    <div
                                        class="max-w-xs bg-white p-4 rounded-md"
                                    >
                                        <div
                                            class="flex items-center justify-center mb-4"
                                        >
                                            <img
                                                src="https://placekitten.com/100/100"
                                                alt="Profile Picture"
                                                class="rounded-full w-16 h-16"
                                            />
                                        </div>
                                        <div class="text-center">
                                            <h2 class="text-lg font-semibold">
                                                {{ $page.props.auth.user.name }}
                                            </h2>
                                            <p class="text-gray-500 font-bold">
                                                Admin
                                            </p>
                                        </div>
                                        <ul class="mt-4">
                                            <li
                                                class="flex items-center space-x-2"
                                            >
                                                <svg
                                                    class="w-4 h-4 text-gray-600"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 12h18M3 6h18M3 18h18"
                                                    ></path>
                                                </svg>
                                                <span class="text-gray-700"
                                                    >Location: New York</span
                                                >
                                            </li>
                                            <li
                                                class="flex items-center space-x-2"
                                            >
                                                <svg
                                                    class="w-4 h-4 text-gray-600"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 10V3L4 14h7v7l9-11h-7z"
                                                    ></path>
                                                </svg>
                                                <span class="text-gray-700"
                                                    >Username:
                                                    {{
                                                        $page.props.auth.user
                                                            .username
                                                    }}
                                                </span>
                                            </li>
                                            <li
                                                class="flex items-center space-x-2"
                                            >
                                                <svg
                                                    class="w-4 h-4 text-gray-600"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 6h16M4 12h16m-7 6h7"
                                                    ></path>
                                                </svg>
                                                <span class="text-gray-700">{{
                                                    $page.props.auth.user
                                                        .ContactNo
                                                }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="text-center">
                                        <a-button class="mt-2">
                                            <NavLink
                                                :href="route('logout')"
                                                method="post"
                                                as="button"
                                            >
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

            <div>
                <header style="width: 95%; margin: 0 auto" class="py-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <img
                                src="../../../public/Logo/ccmpbng.png"
                                alt=""
                                style="height: 80px"
                            />

                            <p
                                style="
                                    color: rgba(34, 34, 34, 0.788);
                                    font-size: max(1vw, 1rem);
                                    font-style: italic;
                                "
                            >
                                {{ $page.props.quote.quote }}
                            </p>
                        </div>
                        <div>
                            <p id="MyClockDisplay" class="clock text-end">
                                {{ currentTime }}
                            </p>
                        </div>
                        <br />
                    </div>
                    <p class="text-end text-sm underline">
                        -{{ $page.props.quote.author }}
                    </p>
                </header>
            </div>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            currentTime: "",
        };
    },
    methods: {
        showTime() {
            var date = new Date();
            var h = date.getHours(); // 0 - 23
            var m = date.getMinutes(); // 0 - 59
            var s = date.getSeconds(); // 0 - 59
            var session = "AM";

            if (h == 0) {
                h = 12;
            }

            if (h > 12) {
                h = h - 12;
                session = "PM";
            }

            h = h < 10 ? "0" + h : h;
            m = m < 10 ? "0" + m : m;
            s = s < 10 ? "0" + s : s;

            var time = h + ":" + m + ":" + s + " " + session;
            this.currentTime = time;

            setTimeout(this.showTime, 1000);
        },
    },
    mounted() {
        this.showTime();
    },
};
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap");
.icons {
    margin-top: 1px;
    height: 30px;
    background: white;
    border-radius: 10%;
    /* Optionally, you can also reset background color on hover */
}
.b-icons {
    height: 16px;
    width: 100%;
}

.clock {
    color: #000000;
    font-size: 30px;
    font-family: "Chakra Petch", sans-serif;
    font-weight: 600;
    font-style: normal;
    letter-spacing: 8px;
}
</style>
