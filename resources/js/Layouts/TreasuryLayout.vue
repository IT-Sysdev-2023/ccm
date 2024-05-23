<script setup>
import { ref } from "vue";
import { Link } from '@inertiajs/vue3';
import NavLink from "@/Components/NavLink.vue";

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
            <header style="width: 100%; background: #001529; margin-top: -7px">
                <div class="px-4">
                    <div class="flex items-center justify-between">
                        <div class="flex shrink-0">
                            <a aria-current="page" class="flex items-center" href="/">
                                <img class="h-7 w-auto" src="/Logo/treasury.png" alt="" />
                                <p class="sr-only">Website Title</p>
                            </a>
                        </div>
                        <div class="hidden md:flex md:items-center md:justify-center md:gap-3">
                            <Link class="inline-block px-3 p-4 mt-2 text-sm text-gray-900 transition-all duration-200"
                                :class="{
                                    'bg-blue-600 text-white':
                                        route().current('treasury.dashboard'),
                                    'text-white':
                                        !route().current('treasury.dashboard'),
                                }" :href="route('treasury.dashboard')">
                            <DashboardOutlined /> Dashboard
                            </Link>
                            <Link class="inline-block px-3 p-4 mt-2 text-sm text-gray-900 transition-all duration-200"
                                :class="{
                                    'bg-blue-600 text-white':
                                        route().current('indeximportupdates'),
                                    'text-white':
                                        !route().current('indeximportupdates'),
                                }" :href="route('indeximportupdates')">
                            <CloudUploadOutlined /> Import&Update Checks
                            </Link>
                            <a-dropdown :class="{
                                'bg-blue-600 text-white':
                                    route().current(
                                        'check_for.clearing'
                                    ) ||
                                    route().current(
                                        'leasing.checks'
                                    ) || route().current(
                                        'pdc_clearing.checks'
                                    )
                            }" :placement="placements">
                                <a class="inline-block px-3 p-4 mt-2 text-sm text-white transition-all duration-200"
                                    href="#">
                                    <CheckCircleOutlined /> Check Receiving
                                </a>

                                <template #overlay>
                                    <a-menu style="background-color: #001529">
                                        <a-menu-item :class="{
                                            'bg-blue-600 text-white':
                                                route().current(
                                                    'check_for.clearing'
                                                ),
                                            'text-white':
                                                !route().current(
                                                    'check_for.clearing'
                                                ),
                                        }">
                                            <Link style="color: white" :href="route('check_for.clearing')
                                                ">Dated Cheques</Link>
                                        </a-menu-item>
                                        <a-menu-item :class="{
                                            'bg-blue-600 text-white':
                                                route().current(
                                                    'pdc_clearing.checks'
                                                ),
                                            'text-white': !route().current(
                                                'pdc_clearing.checks'
                                            ),
                                        }">
                                            <Link style="color: white" :href="route('pdc_clearing.checks')
                                                ">Post Dated Cheques</Link>
                                        </a-menu-item>
                                        <a-menu-item :class="{
                                            'bg-blue-600 text-white':
                                                route().current(
                                                    'leasing.checks'
                                                ),
                                            'text-white':
                                                !route().current(
                                                    'leasing.checks'
                                                ),
                                        }">
                                            <Link style="color: white" :href="route('leasing.checks')">Leasing Cheques
                                            </Link>
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                            </a-dropdown>
                            <a-dropdown :placement="placements" :class="{
                                'bg-blue-600 text-white':
                                    route().current('dated.checks') ||
                                    route().current('pdc.checks'),
                                'text-white':
                                    !route().current('dated.checks') ||
                                    !route().current('pdc.checks'),
                            }">
                                <a class="inline-block px-3 p-4 mt-2 text-sm text-gray-900 transition-all duration-200"
                                    href="#">
                                    <CalendarOutlined /> Dated Checks/Pdc
                                </a>


                                <template #overlay>
                                    <a-menu style="background-color: #001529">
                                        <a-menu-item style="color: white" :class="{
                                            'bg-blue-600 text-white':
                                                route().current(
                                                    'dated.checks'
                                                ),
                                            'text-white':
                                                !route().current(
                                                    'dated.checks'
                                                ),
                                        }">
                                            <Link :href="route('dated.checks')">Dated Checks</Link>
                                        </a-menu-item>
                                        <a-menu-item style="color: white" :class="{
                                            'bg-blue-600 text-white':
                                                route().current(
                                                    'pdc.checks'
                                                ),
                                            'text-white':
                                                !route().current(
                                                    'pdc.checks'
                                                ),
                                        }">
                                            <Link :href="route('pdc.checks')">Post Dated Checks</Link>
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                            </a-dropdown>
                            <a-dropdown :placement="placements" :class="{
                                'bg-blue-600 text-white':
                                    route().current('bounce.tagging') ||
                                    route().current('ds_tagging'),
                                'text-black-100':
                                    !route().current(
                                        'bounce.tagging'
                                    ) && !route().current('ds_tagging'),
                            }">
                                <a class="inline-block px-3 mt-2 p-4 text-sm text-white transition-all duration-200"
                                    href="#">
                                    <FundOutlined /> Ds/Bounce Tagging
                                </a>


                                <template #overlay>
                                    <a-menu style="background-color: #001529">
                                        <a-menu-item style="color: white" :class="{
                                            'bg-blue-600 text-white':
                                                route().current(
                                                    'ds_tagging'
                                                ),
                                            'text-white':
                                                !route().current(
                                                    'ds_tagging'
                                                ),
                                        }">
                                            <Link :href="route('ds_tagging')">Deposited Tagging</Link>
                                        </a-menu-item>
                                        <a-menu-item style="color: white" :class="{
                                            'bg-blue-600 text-white':
                                                route().current(
                                                    'bounce.tagging'
                                                ),
                                            'text-white':
                                                !route().current(
                                                    'bounce.tagging'
                                                ),
                                        }">
                                            <Link :href="route('bounce.tagging')">Bounce Tagging</Link>
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                            </a-dropdown>
                            <a-dropdown :placement="placements" :class="{
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
                            }">
                                <a class="inline-block px-3 mt-2 p-4 text-sm text-white transition-all duration-200"
                                    href="#">
                                    <ContactsOutlined /> Transaction
                                </a>


                                <template #overlay>
                                    <a-menu style="background-color: #001529">
                                        <a-menu-item style="color: white" :class="{
                                            'bg-blue-600 text-white':
                                                route().current(
                                                    'manual_entry.checks'
                                                ),
                                            'text-white': !route().current(
                                                'manual_entry.checks'
                                            ),
                                        }">
                                            <Link :href="route('manual_entry.checks')
                                                ">Check Manual Entry</Link>
                                        </a-menu-item>
                                        <a-menu-item style="color: white" :class="{
                                            'bg-blue-600 text-white':
                                                route().current(
                                                    'mergechecks.checks'
                                                ),
                                            'text-white':
                                                !route().current(
                                                    'mergechecks.checks'
                                                ),
                                        }">
                                            <Link :href="route('mergechecks.checks')
                                                ">Merge Checks</Link>
                                        </a-menu-item>
                                        <a-menu-item style="color: white" :class="{
                                            'bg-blue-600 text-white':
                                                route().current(
                                                    'bounce.checks'
                                                ),
                                            'text-white':
                                                !route().current(
                                                    'bounce.checks'
                                                ),
                                        }">
                                            <Link :href="route('bounce.checks')">Bounced Checks</Link>
                                        </a-menu-item>
                                        <a-menu-item style="color: white" :class="{
                                            'bg-blue-600 text-white':
                                                route().current(
                                                    'replace.checks'
                                                ),
                                            'text-white':
                                                !route().current(
                                                    'replace.checks'
                                                ),
                                        }">
                                            <Link :href="route('replace.checks')">Replacement Checks</Link>
                                        </a-menu-item>
                                        <a-menu-item style="color: white" :class="{
                                            'bg-blue-600 text-white':
                                                route().current(
                                                    'partial_payments.checks'
                                                ),
                                            'text-white': !route().current(
                                                'partial_payments.checks'
                                            ),
                                        }">
                                            <Link :href="route(
                                                'partial_payments.checks'
                                            )
                                                ">Partial Payment</Link>
                                        </a-menu-item>
                                        <a-menu-item style="color: white" :class="{
                                            'bg-blue-600 text-white':
                                                route().current(
                                                    'dcpdc.checks'
                                                ),
                                            'text-white':
                                                !route().current(
                                                    'dcpdc.checks'
                                                ),
                                        }">
                                            <Link :href="route('dcpdc.checks')">Dated / Post Dated Checks
                                            Reports</Link>
                                        </a-menu-item>
                                        <a-menu-item style="color: white" :class="{
                                            'bg-blue-600 text-white':
                                                route().current(
                                                    'duePdcReports.checks'
                                                ),
                                            'text-white': !route().current(
                                                'duePdcReports.checks'
                                            ),
                                        }">
                                            <Link :href="route(
                                                'duePdcReports.checks'
                                            )
                                                ">Due / Post Dated Checks
                                            Reports</Link>
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                            </a-dropdown>
                            <Link class="inline-block px-3 p-4 mt-2 text-sm text-gray-900 transition-all duration-200"
                                :class="{
                                    'bg-blue-600 text-white':
                                        route().current('settings'),
                                    'text-white':
                                        !route().current('settings'),
                                }" :href="route('settings')">
                            <SettingOutlined /> My Settings
                            </Link>
                            <Link class="inline-block px-3 p-4 mt-2 text-sm text-gray-900 transition-all duration-200"
                                :class="{
                                    'bg-blue-600 text-white':
                                        route().current('about.us'),
                                    'text-white':
                                        !route().current('about.us'),
                                }" :href="route('about.us')">
                            <AliwangwangOutlined /> About Us
                            </Link>
                        </div>
                        <div class="flex items-center  justify-end gap-3 mt-2">
                            <a-dropdown placement="bottom" arrow>
                                <div class="flex items-center justify-center" style="
                                        cursor: pointer;
                                    ">
                               <span class="text-white mr-2" style="font-size: 14px;"> Howdy! {{  $page.props.auth.user.username }}</span> <img :src="`/storage/users-image/${$page.props.auth.user.id}`"
                                        alt="Profile Picture" class="rounded-full" style="height: 30px;" />
                                </div>
                                <template #overlay>
                                    <div class="max-w-xs p-4 rounded-md mr-2" style="background: #001529;width: 500px;">
                                        <div class="flex items-center justify-center mb-4">
                                            <img :src="`/storage/users-image/${$page.props.auth.user.id}`"
                                                alt="Profile Picture" class="rounded-full w-16 h-16" />
                                        </div>
                                        <div class="text-center">
                                            <h2 class="text-lg font-semibold text-white">
                                                Hi there! {{ $page.props.auth.user.username }}
                                            </h2>
                                            <p class="text-gray-500 font-bold">

                                            </p>
                                        </div>
                                        <ul class="mt-10">
                                            <li class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                                <span class="text-white">
                                                    {{
                                                        $page.props.auth.user
                                                            .name
                                                    }}
                                                </span>
                                            </li>
                                            <li class="flex items-center space-x-2 text-white">
                                                <PhoneOutlined />
                                                <span class="text-white">{{
                                                    $page.props.auth.user
                                                        .ContactNo
                                                }}</span>
                                            </li>
                                        </ul>
                                        <div class="mt-5">

                                            <a-button block @click="logout">
                                                <template #icon>
                                                    <LogoutOutlined />
                                                </template>
                                                Logout
                                            </a-button>

                                        </div>
                                    </div>

                                </template>

                            </a-dropdown>
                        </div>
                    </div>
                </div>
            </header>

            <div>
                <header style="width: 95%; margin: 0 auto" class="py-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <img src="../../../public/Logo/ccmpbng.png" alt="" style="height: 80px" />

                            <p style="
                                    color: rgba(34, 34, 34, 0.788);
                                    font-size: max(1vw, 1rem);
                                    font-style: italic;
                                ">
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
            <div style="width: 96%; margin: 0 auto;">
                <main>
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'pinia'
import { useOnlineUsersStore } from '@/stores/online-users'

export default {
    data() {
        return {
            currentTime: "",
            dataws: [],
        };
    },

    methods: {
        logout() {
            this.$inertia.post(route('logout'));
        },

        ...mapActions(useOnlineUsersStore, [
            'setOnlineUsers',
            'addOnlineUser',
            'removeOnlineUser'
        ]),

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


        if (this.$page.props.auth) {
            this.$ws
                .join('online.users')
                .here(users => this.setOnlineUsers(users))
                .joining(async user => this.addOnlineUser(user))
                .leaving(async user => this.removeOnlineUser(user))
        }
    },
    beforeUnmount() {
        if (this.$page.props.auth) {
            this.$ws.leaveAllChannels()
        }
    }
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
