<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">This is treasury Dashboard</h2>
        </template>

        <div class="py-0">

            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mb-2 mt-2">
                    <a-breadcrumb-item href="">
                        <HomeOutlined />
                    </a-breadcrumb-item>
                    <a-breadcrumb-item href="">
                        <span>Dashboard</span>
                    </a-breadcrumb-item>
                    <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    <a-breadcrumb-item>Replace Checks</a-breadcrumb-item>
                </a-breadcrumb>
                <a-card>
                    <a-table bordered :pagination="false" :data-source="data.data" :columns="columns" size="small">

                    </a-table>
                </a-card>
            </div>
        </div>
    </TreasuryLayout>
</template>
<script>
import {
    SettingOutlined,
    TagOutlined,
    FolderAddOutlined,
    HomeOutlined,
    SaveOutlined,
    AccountBookOutlined,
    CalendarOutlined,
    MoneyCollectOutlined,
    UsergroupAddOutlined,
    BankOutlined,
    UserOutlined,
    InfoCircleOutlined,
    CheckOutlined,
    CloseOutlined

} from '@ant-design/icons-vue';
export default {
    props: {
        data: Array,
        columns: Array,
        pagination: Array,
    }
}
</script>