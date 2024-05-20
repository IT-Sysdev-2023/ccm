<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/vue3";
import { SettingOutlined } from "@ant-design/icons-vue";

const showingNavigationDropdown = ref(false);

const visible = ref(false);
const hide = () => {
    visible.value = false;
};
const selectedKeys = ref(['']);
const placement = "bottom";
</script>

<template>
    <a-layout style="min-height: 100vh; background-color: green;">
        <a-layout-sider v-model:collapsed="collapsed" collapsible>
            <div class="flex  mt-10 justify-center mb-5">

                <img v-if="!collapsed" style="height: 50px; border-radius: 50%"
                    :src="'http://172.16.161.34:8080/hrms' + $page.props.auth.user.employee3.applicant.photo"
                    alt="logo" />
                <div class="flex justify-center items-center text-white">
                    <img style="height: 50px;" src="../../../public/ccmlogo/lgremove.png" alt="" />
                </div>
            </div>

            <a-menu theme="dark" mode="inline" v-model:selectedKeys="selectedKeys">
                <a-menu-item key="1" :class="{
                    'bg-blue-500 text-white':
                        route().current('admin.dashboard'),
                }">
                    <DashboardOutlined />
                    <span>
                        <Link :href="route('admin.dashboard')">Dashboard</Link>
                    </span>
                </a-menu-item>
                <a-menu-item key="2" :class="{
                    'bg-blue-500 text-white': route().current('users.index'),
                }">
                    <UserOutlined />
                    <span>
                        <Link :href="route('users.index')">User</Link>
                    </span>
                </a-menu-item>
                <a-sub-menu>
                    <template #title>
                        <span>
                            <FundProjectionScreenOutlined />
                            <span>Adjusments</span>
                        </span>
                    </template>
                    <a-menu-item key="8" :class="{
                        'bg-blue-500 text-white': route().current('checks.adjustment'),
                    }">
                        <EditOutlined />
                        <span>
                            <Link :href="route('checks.adjustment')">Edit Checks </Link>
                        </span>
                    </a-menu-item>
                    <a-menu-item key="9" :class="{
                        'bg-blue-500 text-white': route().current('deposit.adjustment'),
                    }">
                        <WalletOutlined />
                        <span>
                            <Link :href="route('deposit.adjustment')">Deposit </Link>
                        </span>
                    </a-menu-item>
                    <a-menu-item key="10" :class="{
                        'bg-blue-500 text-white': route().current('bounce.checks.adjustments'),
                    }">
                        <ShakeOutlined />
                        <span>
                            <Link :href="route('bounce.checks.adjustments')">Bounce </Link>
                        </span>
                    </a-menu-item>
                    <a-menu-item>
                        <RollbackOutlined />
                        <span>Replacement</span>
                    </a-menu-item>
                </a-sub-menu>
                <a-sub-menu>
                    <template #title>
                        <span>
                            <FileDoneOutlined />
                            <span>Master File</span>
                        </span>
                    </template>
                    <a-menu-item>
                        <AppleOutlined />
                        <span>App Config</span>
                    </a-menu-item>
                </a-sub-menu>
                <a-sub-menu>
                    <template #title>
                        <span>
                            <DingtalkOutlined />
                            <span>Reports</span>
                        </span>
                    </template>
                    <a-menu-item key="3" :class="{
                        'bg-blue-600 text-white':
                            route().current('reports.dpdc'),
                        'text-black-100':
                            !route().current('reports.dpdc')
                    }">
                        <CalendarOutlined />
                        <span>
                            <Link :href="route('reports.dpdc')">Dated / Pdc</Link>

                        </span>
                    </a-menu-item>
                    <a-menu-item key="4" :class="{
                        'bg-blue-600 text-white':
                            route().current('deposited.checks'),
                        'text-black-100':
                            !route().current('deposited.checks'),
                    }">
                        <WalletOutlined />
                        <span>
                            <Link :href="route('deposited.checks')">Deposited</Link>
                        </span>
                    </a-menu-item>
                    <a-menu-item key="5" :class="{
                        'bg-blue-600 text-white':
                            route().current('bounce.checks.report'),
                        'text-black-100':
                            !route().current('bounce.checks.report'),
                    }">
                        <ShakeOutlined />
                        <span>
                            <Link :href="route('bounce.checks.report')">Bounce</Link>
                        </span>
                    </a-menu-item>
                    <a-menu-item key="6" :class="{
                        'bg-blue-600 text-white':
                            route().current('redeem.reports.admin'),
                        'text-black-100':
                            !route().current('redeem.reports.admin'),
                    }">
                        <TagOutlined />
                        <span>
                            <Link :href="route('redeem.reports.admin')">Redeem</Link>
                        </span>
                    </a-menu-item>
                    <a-menu-item key="7" :class="{
                        'bg-blue-600 text-white':
                            route().current('alta.reports'),
                        'text-black-100':
                            !route().current('alta.reports'),
                    }">
                        <RobotOutlined />
                        <span>
                            <Link :href="route('alta.reports')">Alta Cita</Link>
                        </span>
                    </a-menu-item>
                </a-sub-menu>
            </a-menu>
        </a-layout-sider>
        <a-layout>
            <div style="height: 50px; background: #001529">
                <div class="flex items-center justify-between" style="
                        width: 99%;
                        height: 50px;
                        color: white;
                    ">
                    <div>
                        <a-input-search v-model:value="value" class="mr-3" placeholder="input search text"
                            style="width: 300px" @search="onSearch" />
                        <MessageOutlined style="font-size: 25px; " class="mt-1 mr-3" />
                        <NotificationOutlined class="mr-3" style="font-size: 25px;" />
                        <BellOutlined class="mr-3" style="font-size: 25px;" />
                        <!-- <SettingOutlined class="mr-3" style="font-size: 25px;"/> -->
                    </div>

                    <div>
                        <div class="flex items-center justify-end gap-3">
                            <a-dropdown placement="bottom" arrow>
                                <div class="flex items-center " style="
                                        color: white;
                                        cursor: pointer;
                                    ">
                                    <span class="mr-2">
                                        Howdy! {{ $page.props.auth.user.username }}
                                    </span>
                                    <img style="
                                        height: 30px;
                                        width: 30px;
                                        border-radius: 50%;"
                                        :src="'http://172.16.161.34:8080/hrms' + $page.props.auth.user.employee3.applicant.photo"
                                        alt="">
                                </div>
                                <template #overlay>
                                    <div class="max-w-xs p-4 rounded-md mr-2" style="background: #001529;width: 500px;">
                                        <div class="flex items-center justify-center mb-4">
                                            <img :src="'http://172.16.161.34:8080/hrms' + $page.props.auth.user.employee3.applicant.photo"
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
            </div>
            <a-layout-content style="background-color: white;">
                <div style="padding: 25px">
                    <slot />
                </div>
            </a-layout-content>
            <a-layout-footer style="text-align: center">

                Ant Design ©2024 Created by Ant UED - Programmer
                Kenstilllearning
            </a-layout-footer>
        </a-layout>
    </a-layout>
</template>
<script>

import { mapState, mapActions } from 'pinia'
import { useOnlineUsersStore } from '@/stores/online-users'
export default {
    data() {
        return {
            dataws: [],
            collapsed: false,
        };
    },
    methods: {
        ...mapActions(useOnlineUsersStore, [
            'setOnlineUsers',
            'addOnlineUser',
            'removeOnlineUser'
        ]),
        logout() {
            this.$inertia.post(route('logout'));
        },
    },

    mounted() {
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
    width: 100%;
}
</style>
