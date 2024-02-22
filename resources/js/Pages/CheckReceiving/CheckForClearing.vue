<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
import { Head } from '@inertiajs/vue3';
import dayjs from 'dayjs';
</script>

<template>
    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">This is treasury Dashboard</h2>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-card>
                    <a-date-picker v-model:value="generateDate" class="mb-3" style="width: 200px;"
                        @change="handleGenerateTable" />
                    <a-table size="small" :pagination="false" bordered :dataSource="data.data" :columns="columns" />
                </a-card>
            </div>
        </div>
    </TreasuryLayout>
</template>

<script>
import dayjs from 'dayjs';

export default {
    data() {
        return {
            generateDate: dayjs(),
        }
    },
    props: {
        data: Array,
        columns: Array,
    },
    methods: {
        handleGenerateTable(obj, str) {
            this.$inertia.get(route('check_for.clearing'), {
                generate_date: str,
            })
        }
    }
}
</script>