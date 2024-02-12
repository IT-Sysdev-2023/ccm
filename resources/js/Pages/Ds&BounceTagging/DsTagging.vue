<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
import { Head } from '@inertiajs/vue3';
import { reactive } from 'vue';


</script>

<template>
    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">This is treasury Dashboard</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mb-5">
                    <a-breadcrumb-item href="">
                        <HomeOutlined />
                    </a-breadcrumb-item>
                    <a-breadcrumb-item href="">
                        <user-outlined />
                        <span>Ds&Bounce Tagging</span>
                    </a-breadcrumb-item>
                    <a-breadcrumb-item>Ds Taggings</a-breadcrumb-item>
                    <a-breadcrumb-item></a-breadcrumb-item>
                </a-breadcrumb>
                <a-table :data-source="ds_c_table.data" :pagination="false" :columns="columns" size="small"
                    class="components-table-demo-nested" bordered>
                    <template #bodyCell="{ column, record }">
                        <!-- <p>{{ record.id }}</p> -->
                        <template v-if="column.key === 'select'">
                            <a-switch :checked="switchValues[record.checks_id]" @change="handleSwitchChange(record.checks_id)">
                                <template #checkedChildren>
                                    <check-outlined />
                                </template>
                                <template #unCheckedChildren>
                                    <close-outlined />
                                </template>
                            </a-switch>
                        </template>
                    </template>
                </a-table>
                <div class="mt-3 mb-5" style="border: 1px solid rgb(224, 224, 224); border-radius: 10px; padding: 10px">
                    <div class="flex justify-end">
                        <a-pagination class="mt-0 mb-0" v-model:current="pagination.current"
                            v-model:page-size="pagination.pageSize" :show-size-changer="false" :total="pagination.total"
                            :show-total="(total, range) => `${range[0]}-${range[1]} of ${total} reports`"
                            @change="handleTableChange" />
                    </div>
                </div>
            </div>
        </div>
    </TreasuryLayout>
</template>

<script>
import { HomeOutlined, CheckOutlined, CloseOutlined } from '@ant-design/icons-vue';
export default {
    data() {
        return {
            switchValues: {},
        };
    },
    props: {
        pagination: Array,
        columns: Array,
        ds_c_table: Array,
    },

    methods: {
        handleTableChange(page) {
            try {
                this.$inertia.get(route().current(), {
                    page: page
                });

            } catch (error) {
                console.error('Error fetching data:', error);
            }
        },
        handleSwitchChange(id) {

            this.switchValues[id] = !this.switchValues[id];

            console.log(id);
       
        },
        isChecked(id) {
            this.checkedIds = id;
        },
    }
};
</script>