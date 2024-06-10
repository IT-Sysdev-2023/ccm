<template>

    <Head title="Deposited Check Reports" />

    <div class="ml-20">
        <a-breadcrumb>
            <a-breadcrumb-item href="">
                <home-outlined />
            </a-breadcrumb-item>
            <a-breadcrumb-item href="">
                <user-outlined />
                <span>Accounting Reports</span>
            </a-breadcrumb-item>
            <a-breadcrumb-item>Deposited Check Reports</a-breadcrumb-item>
        </a-breadcrumb>
    </div>
    <div class="py-12">
        <div class="text-center font-bold mb-2">
            <HomeOutlined /> {{ bunit[0].bname }}
        </div>
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div v-if="isProgressing">
                <div class="flex justify-between">
                    <div class="flex">
                        <DoubleRightOutlined />
                        <DoubleRightOutlined class="mr-1" />
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

            <a-card>

                <div class="flex justify-between">
                    <div>
                        <a-breadcrumb>
                            <a-breadcrumb-item>
                                <CalendarOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Select Date</a-breadcrumb-item>
                        </a-breadcrumb>

                        <a-range-picker v-model:value="form.dateRange" class="mb-2" style="width: 350px;" />
                    </div>
                    <div class="mt-5">
                        <a-button class="mr-1" type="primary" @click="startGeneratingDepositedChecks"
                            :loading="isLoading" :disabled="data.data.length <= 0">
                            <template #icon>
                                <CloudDownloadOutlined />
                            </template>
                            {{
                                !isLoading ?
                                    'Generate deposited checks reports' : 'Generating Deposit Reports In Progress...'
                            }}
                        </a-button>
                        <a-input-search v-model:value="form.search" style="width: 350px;" class="mb-5"
                            placeholder="Search Checks" :loading="isFetching" />
                    </div>
                </div>

                <a-table :data-source="data.data" :columns="columns" size="small" :pagination="false" bordered>
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex">
                            <span v-html="highlightText(record[column.dataIndex], form.search)">
                            </span>
                        </template>
                        <template v-if="column.key === 'details'">
                            <a-button size="small" @click="details(record)" :loading="isLoadingBtn">
                                <template #icon>
                                    <SettingOutlined></SettingOutlined>
                                </template>
                            </a-button>
                        </template>
                    </template>
                </a-table>
                <pagination :datarecords="data" class="mt-4" />
            </a-card>

        </div>
    </div>
    <a-modal v-model:open="isOpenModal" title="Basic Modal" style="width: 900px;" :footer="false">
        <a-table :data-source="selectDataDetails" :columns="selectedColumns" size="small" bordered>
            <template #bodyCell="{ column, record }">

                <template v-if="column.key === 'details'">
                    <a-button size="small" @click="detailsSelected(record)">
                        <template #icon>
                            <SettingOutlined></SettingOutlined>
                        </template>
                    </a-button>
                </template>
            </template>
        </a-table>
    </a-modal>
    <CheckModalDetail v-model:open="selectedIsOpen" :datarecords="selectedDetails"></CheckModalDetail>
</template>



<script>
import dayjs from 'dayjs';
import debounce from "lodash/debounce";
import AccountingLayout from '@/Layouts/AccountingLayout.vue';
import { highlighten } from "@/Mixin/highlighten.js";
export default {
    layout: AccountingLayout,
    props: {
        data: Object,
        columns: Array,
        dateRangeBackend: Object,
        bunit: Object,
        filters: Object
    },
    setup() {
        const { highlightText } = highlighten();
        return { highlightText };
    },

    data() {
        return {
            form: {
                search: this.filters.search,
                dateRange: this.filters.dateRange ? [
                    dayjs(this.filters.dateRange[0]),
                    dayjs(this.filters.dateRange[1])] : null,
            },
            isOpenModal: false,
            selectDataDetails: [],
            selectedDetails: [],
            isProgressing: false,
            isLoading: false,
            isFetching: false,
            selectedIsOpen: false,
            progressBar: {
                currentRow: 0,
                percentage: 0,
                message: "",
                totalRows: 0,
            },

            selectedColumns: [
                {
                    title: 'Ds Date',
                    dataIndex: 'date_deposit',
                    key: 'id'
                },
                {
                    title: 'Check Date',
                    dataIndex: 'check_date',
                    key: 'id'
                },
                {
                    title: 'Bank',
                    dataIndex: 'bankbranchname',
                    key: 'id'
                },
                {
                    title: 'Customer',
                    dataIndex: 'fullname',
                    key: 'id'
                },
                {
                    title: 'Check No',
                    dataIndex: 'check_no',
                    key: 'id'
                },
                {
                    title: 'Amount',
                    dataIndex: 'check_amount',
                    key: 'id'
                },
                {
                    title: 'Ds No',
                    dataIndex: 'ds_no',
                    key: 'id'
                },
                {
                    title: 'Details',
                    key: 'details',
                    align: 'center'
                },
            ],
        }
    },
    mounted() {
        this.$ws
            .private(`generating-deposited-checks-accounting.${this.$page.props.auth.user.id}`)
            .listen(".generating-deposited-accounting", (e) => {
                this.progressBar = e;
            });
    },

    methods: {
        detailsSelected(data) {
            console.log(data)
            this.selectedIsOpen = true;
            this.selectedDetails = data;
        },
        details(data) {
            axios.get(`/details/bounce`, {
                params: {
                    ds_no: data.ds_no,
                    bunit: this.$page.props.auth.user.businessunit_id,
                    date: data.date_deposit
                }
            })
                .then(response => {
                    this.selectDataDetails = response.data;
                    this.isOpenModal = true;
                })
                .catch(error => {

                    console.error('Error fetching image details:', error);
                });
        },

        startGeneratingDepositedChecks() {
            this.isLoading = true;
            this.isProgressing = true;
            this.$inertia.get(route('start.generate.rep,deposited.accouting'), {
                dateRange: this.filters.dateRange ? [
                    dayjs(this.filters.dateRange[0]).format('YYYY-MM-DD'),
                    dayjs(this.filters.dateRange[1]).format('YYYY-MM-DD')] : null
            });
        }
    },
    watch: {
        form: {
            deep: true,
            handler: debounce(async function () {
                this.isFetching = true,
                    this.$inertia.get(route("deposited.reports.accounting"), {
                        search: this.form.search,
                        dateRange: this.form.dateRange ? [
                            dayjs(this.form.dateRange[0]).format('YYYY-MM-DD'),
                            dayjs(this.form.dateRange[1]).format('YYYY-MM-DD')] : null
                    }, {
                        preserveState: true,
                        onSuccess: () => {
                            this.isFetching = false;
                        },
                        onError: () => {
                            this.isFetching = false; // Ensure the flag is reset on error
                        }
                    }
                    );
            }, 600),
        },
    },
}
</script>
