<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
import { Head } from '@inertiajs/vue3';
import { FullscreenOutlined, MenuFoldOutlined, FileSyncOutlined } from '@ant-design/icons-vue'
import { ref } from 'vue';

const size = ref('large');
</script>

<template>
    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">This is treasury Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="components-table-demo-nested max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-table :dataSource="data" :columns="columns" size="middle" bordered>
                    <template #bodyCell="{ column, record, index }">
                        <template v-if="column.key === 'action'">
                            <a-button>
                                <template #icon>
                                    <!-- <FullscreenOutlined /> -->
                                    <MenuFoldOutlined />
                                </template>
                            </a-button>
                            <a-button type="primary" ghost class="mx-2">
                                <template #icon>
                                    <FileSyncOutlined />
                                </template>
                            </a-button>
                        </template>
                    </template>
                </a-table>
            </div>
        </div>
    </TreasuryLayout>
</template>
<script>
export default {
    props: {
        data: Array,
        columns: Array,
    }
};
</script>