<script setup>
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
</script>

<template>

    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                This is treasury Dashboard
            </h2>
        </template>

        <div class="py-0">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mt-2 mb-3">
                    <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                    <a-breadcrumb-item><a href="">Transaction </a></a-breadcrumb-item>
                    <a-breadcrumb-item>Partial Payments</a-breadcrumb-item>
                </a-breadcrumb>
                <a-card>
                    <div class="flex justify-between mb-10">
                        <div>
                            <a-select ref="select" @change="handleChangeStatus" v-model:value="statusValue" class="mr-2"
                                placeholder="Select Status Checks Report" style="width: 250px" @focus="focus">
                                <a-select-option value="1">Dated Check Report</a-select-option>
                                <a-select-option value="2">Post Dated Check Report</a-select-option>
                            </a-select>
                            <a-range-picker @change="handleDateGenerate" v-model:value="dateRange" />
                        </div>
                        <div>
                            <a-button @click="startGenerating">
                                Generate Report Excel
                            </a-button>
                        </div>
                    </div>

                    <a-table :data-source="data.data" :pagination="false" :columns="columns" size="small" bordered>

                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'action'">
                                <a-button size="small" class="mx-1" @click="openUpDetails(record)">
                                    <template #icon>
                                        <SettingOutlined />
                                    </template>
                                </a-button>
                            </template>
                        </template>
                    </a-table>
                    <pagination class="mt-6" :datarecords="data" />
                </a-card>
            </div>
        </div>
    </TreasuryLayout>
</template>

<script>
import dayjs from 'dayjs';
import throttle from 'lodash/throttle';
import pickBy from 'lodash/pickBy';
import Pagination from "@/Components/Pagination.vue";
export default {
    props: {
        data: Array,
        columns: Array,
        statusReport: Object,
        dateRangeValue: Array,
        filters: Array,
        status: Object,
    },
    data() {
        return {
            statusValue: this.status,
            dateRange: [dayjs(this.dateRangeValue[0]), dayjs(this.dateRangeValue[1])],
        }
    },

    methods: {
        handleDateGenerate(obj, str) {
            console.log(this.dateRangeValue);
            this.$inertia.get(route("dcpdc.checks"), {
                status: this.statusValue,
                date_from: str[0],
                date_to: str[1],
            }, {
                preserveState: true
            });
        },
        handleChangeStatus() {


            this.$inertia.get(route("dcpdc.checks"), {
                status: this.statusValue,
                date_from: this.filters.date_from || '',
                date_to: this.filters.date_to || ''
            }, {
                preserveState: true
            });
        },
        startGenerating() {
            this.$inertia.get(route('generate_report.checks'));
        }
    }
}
</script>
