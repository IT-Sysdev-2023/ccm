<script setup>
import AccountingLayout from '@/Layouts/AccountingLayout.vue';
import dayjs from 'dayjs';

</script>

<template>

    <Head title="Dashboard" />

    <AccountingLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">this is accounting Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between mb-2">
                    <div>
                        <a-select placeholder="Select type" @change="handleChangeDataType"
                            v-model:value="fetch.dataType" style="width: 210px" :loading="isFetching"
                            :disabled="isFetching">
                            <a-select-option value="0">View All</a-select-option>
                            <a-select-option value="1">Dated Cheque</a-select-option>
                            <a-select-option value="2">Post Dated Cheques</a-select-option>
                        </a-select>
                        <a-select placeholder="Select type" v-model:value="fetch.dataStatus"
                            @change="handleChangeDataStatus" style="width: 210px" :loading="isFetching"
                            :disabled="isFetching">
                            <a-select-option value="0">View All</a-select-option>
                            <a-select-option value="1">Pending Deposit Cheques</a-select-option>
                            <a-select-option value="2">Deposited Cheques</a-select-option>
                        </a-select>

                        <a-select placeholder="Select type" @change="handleChangeDataFrom"
                            v-model:value="fetch.dataFrom" style="width: 210px" :loading="isFetching"
                            :disabled="isFetching">
                            <a-select-option value="">View All</a-select-option>
                            <a-select-option v-for="(item, key) in Object.keys(department_from)" :key="key"
                                v-model:value="department_from[item][0].department">
                                {{ department_from[item][0].department }}</a-select-option>
                        </a-select>

                        <a-range-picker v-model:value="fetch.dateRange" :loading="isFetching" :disabled="isFetching"
                            @change="handleChangeDateRange" />
                    </div>
                    <div>
                        <a-button style="width: 200px" type="primary" @click="startGeneratingExcel">
                            <CloudUploadOutlined />
                            generate excel
                        </a-button>
                    </div>
                </div>

                <a-table size="small" bordered :dataSource="data.data" :columns="columns" :pagination="false" />
                <pagination class="mt-6 " :datarecords="data" />

            </div>
        </div>
    </AccountingLayout>
</template>

<script>
export default {
    props: {
        columns: Array,
        data: Object,
        department_from: Object,
        dataTypeBackend: Number,
        dataStatusBackend: Number,
        dataFromBackend: String,
        dataRangeBackend: Array,
    },
    data() {
        return {
            // num: 0,
            isFetching: false,
            fetch: {
                dataType: this.dataTypeBackend,
                dataStatus: this.dataStatusBackend,
                dataFrom: this.dataFromBackend,
                dateRange: this.dataRangeBackend ? [this.dataRangeBackend[0] ? dayjs(this.dataRangeBackend[0]) : null, this.dataRangeBackend[1] ? dayjs(this.dataRangeBackend[1]) : null] : null,
            }
        };
    },

    methods: {
        handleChangeDataType(value) {
            this.isFetching = true;
            this.$inertia.get(route('datedpcchecks.accounting'), {
                dataType: value,
                dataFrom: this.fetch.dataFrom,
                dataStatus: this.fetch.dataStatus,
                dateRange: this.fetch.dateRange !== null ? [dayjs(this.fetch.dateRange[0]).format('YYYY-MM-DD'), dayjs(this.fetch.dateRange[1]).format('YYYY-MM-DD')] : null
            })
        },
        handleChangeDateRange(value, valueStr) {
            this.isFetching = true;
            this.$inertia.get(route('datedpcchecks.accounting'), {
                dataType: this.fetch.dataType,
                dataFrom: this.fetch.dataFrom,
                dataStatus: this.fetch.dataStatus,
                dateRange: valueStr,
            })
        },
        handleChangeDataStatus(value) {
            this.isFetching = true;
            this.$inertia.get(route('datedpcchecks.accounting'), {
                dataType: this.fetch.dataType,
                dataFrom: this.fetch.dataFrom,
                dataStatus: value,
                dateRange: this.fetch.dateRange !== null ? [dayjs(this.fetch.dateRange[0]).format('YYYY-MM-DD'), dayjs(this.fetch.dateRange[1]).format('YYYY-MM-DD')] : null
            })
        },
        handleChangeDataFrom(value) {
            this.isFetching = true;
            this.$inertia.get(route('datedpcchecks.accounting'), {
                dataType: this.fetch.dataType,
                dataFrom: value,
                dataStatus: this.fetch.dataStatus,
                dateRange: this.fetch.dateRange !== null ? [dayjs(this.fetch.dateRange[0]).format('YYYY-MM-DD'), dayjs(this.fetch.dateRange[1]).format('YYYY-MM-DD')] : null
            })
        },

        startGeneratingExcel() {
            this.$inertia.get(route('start.generate.rep.accouting'), {
                dataType: this.fetch.dataType,
                dataFrom: this.fetch.dataFrom,
                dataStatus: this.fetch.dataStatus,
                dateRange: this.fetch.dateRange !== null ? [dayjs(this.fetch.dateRange[0]).format('YYYY-MM-DD'), dayjs(this.fetch.dateRange[1]).format('YYYY-MM-DD')] : null
            });
        }
    }
};
</script>
