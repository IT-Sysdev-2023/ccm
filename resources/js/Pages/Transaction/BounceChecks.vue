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

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-card>
                    <a-table :loading="isLoadingTable" :dataSource="data.data" :columns="columns" size="small" bordered
                        :pagination="false">
                        <template #bodyCell="{ column, record, index }">
                            <template v-if="column.key === 'check_box'">
                                <a-switch size="small" v-model:checked="record.isChecked" @change="computedAmount(record)">
                                    <template #checkedChildren><check-outlined /></template>
                                    <template #unCheckedChildren><close-outlined /></template>
                                </a-switch>
                            </template>
                            <template v-if="column.key === 'action'">
                                <a-button size="square" class="mx-2" @click="openUpDetails(record)">
                                    <template #icon>
                                        <SettingOutlined />
                                    </template>
                                </a-button>
                            </template>
                        </template>
                    </a-table>
                </a-card>
            </div>
        </div>
    </TreasuryLayout>
</template>
<script>
export default {
    props: {
        data: Array,
        columns: Array,
        pagination: Array,
    }
}
</script>