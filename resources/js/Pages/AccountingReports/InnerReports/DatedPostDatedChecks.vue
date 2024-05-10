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
                <div class="text-center font-bold mb-10">
                    <HomeOutlined /> {{ bunit[0].bname }}
                </div>
                <div v-if="isProgressing">
                    <div class="flex justify-between">
                        <div class="flex">
                            <DoubleRightOutlined class="mr-1" />
                            <DoubleRightOutlined class="mr-2" />
                            <p>
                                {{ progressBar.message }}
                                {{ progressBar.currentRow }} to
                                {{ progressBar.totalRows }} records
                            </p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <a-progress class="mt-2" :stroke-color="{
                            from: '#108ee9',
                            to: '#87d068',
                        }" :percent="progressBar.percentage" status="active" />

                    </div>
                </div>
                <div class="flex  mb-2">

                    <div>
                        <a-breadcrumb>
                            <a-breadcrumb-item>
                                <CopyOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Select Type</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-select placeholder="Select type" @change="handleChangeDataType"
                            v-model:value="fetch.dataType" style="width: 210px" :loading="isFetching"
                            :disabled="isFetching">
                            <a-select-option value="1">Dated Cheque</a-select-option>
                            <a-select-option value="2">Post Dated Cheques</a-select-option>
                        </a-select>
                    </div>
                    <div>
                        <a-breadcrumb>
                            <a-breadcrumb-item>
                                <FileTextOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Select Status</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-select placeholder="Select Status" v-model:value="fetch.dataStatus"
                            @change="handleChangeDataStatus" style="width: 210px" :loading="isFetching"
                            :disabled="isFetching">
                            <a-select-option value="0">View All</a-select-option>
                            <a-select-option value="1">Pending Deposit Cheques</a-select-option>
                            <a-select-option value="2">Deposited Cheques</a-select-option>
                        </a-select>
                    </div>

                    <div>
                        <a-breadcrumb>
                            <a-breadcrumb-item>
                                <ProfileOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Select Check From</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-select placeholder="Select type" @change="handleChangeDataFrom"
                            v-model:value="fetch.dataFrom" style="width: 210px" :loading="isFetching"
                            :disabled="isFetching">
                            <a-select-option value="">View All</a-select-option>
                            <a-select-option v-for="(item, key) in Object.keys(department_from)" :key="key"
                                v-model:value="department_from[item][0].department">
                                {{ department_from[item][0].department }}</a-select-option>
                        </a-select>
                    </div>
                    <div>
                        <a-breadcrumb>
                            <a-breadcrumb-item>
                                <CalendarOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Select Date Range</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-range-picker class="mr-10" v-model:value="fetch.dateRange" :loading="isFetching"
                            :disabled="isFetching" @change="handleChangeDateRange" />
                    </div>
                    <div class="mt-6">
                        <a-button type="primary" @click="startGeneratingExcel" :disabled="data.length <= 0"
                            :loading="isLoading">
                            <template #icon>
                                <CloudUploadOutlined />
                            </template>
                            {{ isLoading ? 'Generating checks in progress..' : 'Generate dated and pdc checks' }}
                        </a-button>
                    </div>
                </div>



                <a-table size="small" bordered :data-source="data.data" :columns="columns" :pagination="false" />
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
        bunit: Object,
    },
    data() {
        return {
            isFetching: false,
            isProgressing: false,
            isLoading: false,
            fetch: {
                dataType: this.dataTypeBackend,
                dataStatus: this.dataStatusBackend,
                dataFrom: this.dataFromBackend,
                dateRange: this.dataRangeBackend ? [this.dataRangeBackend[0] ? dayjs(this.dataRangeBackend[0]) : null, this.dataRangeBackend[1] ? dayjs(this.dataRangeBackend[1]) : null] : null,
            },
            progressBar: {
                currentRow: 0,
                percentage: 0,
                message: "",
                totalRows: 0,
            },
        };
    },

    mounted() {
        this.$ws
            .private(`generating-dated-pdc-reports-accounting.${this.$page.props.auth.user.id}`)
            .listen(".generating-pdc-reports", (e) => {
                this.progressBar = e;
            });
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
                dateRange: this.fetch.dateRange !== null ?
                    [
                        dayjs(this.fetch.dateRange[0]).format('YYYY-MM-DD'),
                        dayjs(this.fetch.dateRange[1]).format('YYYY-MM-DD')
                    ] : null
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
            this.isLoading = true;
            this.isProgressing = true;
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
