<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
</script>

<template>
    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">This is treasury Dashboard</h2>
        </template>

        <div class="py-0">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mt-2 mb-3">
                    <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                    <a-breadcrumb-item><a href="">Transaction </a></a-breadcrumb-item>
                    <a-breadcrumb-item>Partial Payments</a-breadcrumb-item>
                </a-breadcrumb>
                <a-card>
                    <a-table :data-source="data.data" :pagination="false" :columns="columns" size="small" bordered>
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'action'">
                                <a-button size="small" class="mx-1" @click="openUpDetails(record)">
                                    <template #icon>
                                        <SettingOutlined />
                                    </template>
                                </a-button>
                                <a-button size="small" class="mx-1" @click="openUpDetails(record)">
                                    <template #icon>
                                        <MoneyCollectOutlined />
                                    </template>
                                </a-button>
                                <a-button size="small" class="mx-1" @click="openUpDetails(record)">
                                    <template #icon>
                                        <CreditCardFilled />
                                    </template>
                                </a-button>
                            </template>
                        </template>
                    </a-table>
                    <pagination class="mt-6" :datarecords="data"></pagination>
                </a-card>
            </div>
        </div>
    </TreasuryLayout>
</template>

<script>
import Pagination from "@/Components/Pagination.vue"
export default {
    props: {
        data: Array,
        columns: Array,
    }
}
</script>