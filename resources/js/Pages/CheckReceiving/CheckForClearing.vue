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
                    <div class="flex justify-end">
                        <a-pagination class="mt-0 mb-0" v-model:current="pagination.current"
                            style="margin-top: 10px;  border: 1px solid rgb(219, 219, 219); border-radius: 10px; padding: 10px; "
                            v-model:page-size="pagination.pageSize" :show-size-changer="false" :total="pagination.total"
                            :show-total="(total, range) => `${range[0]}-${range[1]} of ${total} reports`"
                            @change="handleGenerateTable" />
                    </div>
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
            gen_date: dayjs(),
        }
    },
    props: {
        data: Array,
        columns: Array,
        pagination: Object,
    },
    methods: {
        handleGenerateTable(obj, str, page = 1) {
            this.gen_date = dayjs(str);
            this.$inertia.get(route('check_for.clearing'), {
                page: page,
                generate_date: str,
            })
        },
    }
}
</script>