<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/vue3";

const showingNavigationDropdown = ref(false);

const visible = ref(false);
const hide = () => {
    visible.value = false;
};
const placement = "bottom";
</script>

<template>
    <a-layout style="min-height: 100vh">
        <a-layout-sider v-model:collapsed="collapsed" collapsible>
            <div class="flex p-1 ml-5 mt-1">
                <img
                    style="height: 50px; border-radius: 50%"
                    src="https://preview.redd.it/i-got-bored-so-i-decided-to-draw-a-random-image-on-the-v0-4ig97vv85vjb1.png?width=640&crop=smart&auto=webp&s=22ed6cc79cba3013b84967f32726d087e539b699"
                    alt="logo"
                />
                <div class="ml-2 flex justify-center items-center text-white">
                    {{ $page.props.auth.user.username }}
                </div>
            </div>

            <a-menu
                theme="dark"
                v-model:selectedKeys="selectedKeys"
                mode="inline"
            >
                <a-menu-item
                    key="1"
                    :class="{
                        'bg-gray-200 text-black':
                            route().current('admin_dashboard'),
                        'text-black-100': !route().current('admin_dashboard'),
                    }"
                >
                    <DashboardOutlined />
                    <span
                        ><Link :href="route('admin_dashboard')"
                            >Dashboard</Link
                        ></span
                    >
                </a-menu-item>
                <a-menu-item
                    key="2"
                    :class="{
                        'bg-gray-200 text-black':
                            route().current('users.index'),
                        'text-black-100': !route().current('users.index'),
                    }"
                >
                    <UserOutlined />
                    <span><Link :href="route('users.index')">User</Link></span>
                </a-menu-item>
                <a-sub-menu key="sub1">
                    <template #title>
                        <span>
                            <FundProjectionScreenOutlined />
                            <span>Adjusments</span>
                        </span>
                    </template>
                    <a-menu-item key="2">
                        <EditOutlined />
                        <span>Edit Details</span>
                    </a-menu-item>
                    <a-menu-item key="2">
                        <WalletOutlined />
                        <span>Deposit</span>
                    </a-menu-item>
                    <a-menu-item key="2">
                        <ShakeOutlined />
                        <span>Bounce</span>
                    </a-menu-item>
                    <a-menu-item key="2">
                        <RollbackOutlined />
                        <span>Replacement</span>
                    </a-menu-item>
                </a-sub-menu>
                <a-sub-menu key="sub2">
                    <template #title>
                        <span>
                            <FileDoneOutlined />
                            <span>Master File</span>
                        </span>
                    </template>
                    <a-menu-item key="2">
                        <AppleOutlined />
                        <span>App Config</span>
                    </a-menu-item>
                </a-sub-menu>
                <a-sub-menu key="sub3">
                    <template #title>
                        <span>
                            <DingtalkOutlined />
                            <span>Reports</span>
                        </span>
                    </template>
                    <a-menu-item
                        key="2"
                        :class="{
                            'bg-blue-600 text-white':
                                route().current('reports.dpdc'),
                            'text-black-100': !route().current('reports.dpdc'),
                        }"
                    >
                        <CalendarOutlined />
                        <span
                            ><Link :href="route('reports.dpdc')"
                                >Dated / Pdc</Link
                            ></span
                        >
                    </a-menu-item>
                    <a-menu-item key="2">
                        <WalletOutlined />
                        <span>Deposited</span>
                    </a-menu-item>
                    <a-menu-item key="2">
                        <ShakeOutlined />
                        <span>Bounce</span>
                    </a-menu-item>
                    <a-menu-item key="2">
                        <TagOutlined />
                        <span>Redeem</span>
                    </a-menu-item>
                    <a-menu-item key="2">
                        <RobotOutlined />
                        <span>Alta Cita</span>
                    </a-menu-item>
                </a-sub-menu>
            </a-menu>
        </a-layout-sider>
        <a-layout>
            <a-layout-header theme="dark" />
            <a-layout-content style="margin: 16px">
                <div
                    :style="{
                        padding: '24px',
                        background: '#fff',
                        minHeight: '360px',
                    }"
                >
                    <slot />
                </div>
            </a-layout-content>
            <a-layout-footer style="text-align: center">
                Ant Design ©2018 Created by Ant UED
            </a-layout-footer>
        </a-layout>
    </a-layout>
</template>
<script>
export default {
    data() {
        return {
            collapsed: false,
        };
    },
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
