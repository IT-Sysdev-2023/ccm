<template>

    <Head title="Dated & Post Dated Checks Report" />

    <div class="py-4">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <a-breadcrumb>
                <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                <a-breadcrumb-item><a href="">Reports</a></a-breadcrumb-item>
                <a-breadcrumb-item>Dated & Post Dated Checks</a-breadcrumb-item>
            </a-breadcrumb>
            <div v-if="isProgressing" class="mt-4">
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
            <div class="flex justify-end">
                <a-input-search v-model:value="query.search" style="width: 400px;" placeholder="Search Checks"
                    :loading="isFetching" />
            </div>
            <div class="" style="display: flex; justify-content: space-between">

                <div class="flex">
                    <a-range-picker v-model:value="dateRange" @change="handleChangeDateRange" class="mt-2 mr-2"
                        style="width: 250px" />
                    <div class="mr-2">
                        <a-tooltip :open="isTooltipVisible" :color="colors" title="select business unit first">
                            <a-select class="mt-2" ref="select" v-model:value="bunitCode" style="width: 160px"
                                placeholder="Business Unit" @change="handleChange">
                                <a-select-option v-for="bu in bunit" v-model:value="bu.businessunit_id">{{ bu.bname
                                    }}</a-select-option>
                            </a-select>
                        </a-tooltip>
                    </div>
                    <div class="mr-2">
                        <a-tooltip :color="colors" :open="isTooltipVisible" title="select check type first">
                            <a-select class="mt-2" ref="select" v-model:value="pdcdatedChecks" style="width: 150px"
                                placeholder="Check Type" @change="handleChange">
                                <a-select-option value="1">PDC</a-select-option>
                                <a-select-option value="2">DATED CHECKS</a-select-option>
                            </a-select>
                        </a-tooltip>
                    </div>
                    <div class="mr-2">
                        <a-tooltip :color="colors" :open="isTooltipVisible" title="select report type first">
                            <a-select class="mt-2" ref="select" v-model:value="repportType" style="width: 150px"
                                placeholder="Deposit Status" @change="handleChange">
                                <a-select-option value="0">VIEW ALL</a-select-option>
                                <a-select-option value="1">PENDING</a-select-option>
                                <a-select-option value="2">DEPOSITED</a-select-option>
                            </a-select>
                        </a-tooltip>
                    </div>
                </div>

                <div class="mt-2">
                    <a-button class="mr-2" v-on:click="generateReport" :loading="loadingGenerate">
                        <template #icon>
                            <CloudUploadOutlined />
                        </template>
                        Generate Excel Checks
                    </a-button>
                    <a-button type="primary" style="width: 200px;" @click="getData">
                        <template #icon>
                            <LoginOutlined />
                        </template>
                        Fetch Data
                    </a-button>
                </div>
            </div>
            <div class="mt-4">
                <a-table :total="85" :data-source="data.data" size="small" class="components-table-demo-nested"
                    :pagination="false" :columns="columns" :loading="loading" bordered @change="handleTableChange">
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'checks_r'">
                            {{ record.check_received }}
                        </template>

                        <template v-else-if="column.key === 'check_date'">
                            {{ record.check_date }}
                        </template>

                        <template v-else-if="column.key === 'check_a'">
                            {{ record.check_amount }}
                        </template>

                        <template v-else-if="column.key === 'details'">
                            <a-button size="small" @click="details(record)">
                                <SettingOutlined></SettingOutlined>
                            </a-button>
                        </template>
                    </template>
                </a-table>
                <pagination :datarecords="data" class="mt-6"></pagination>
            </div>
        </div>
    </div>

    <CheckModalDetail v-model:open="isModalOpen" :datarecords="selectDataDetails"></CheckModalDetail>
</template>

<script>
import debounce from "lodash/debounce";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";
import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
import { message } from "ant-design-vue";
import dayjs from "dayjs";
import { Head } from "@inertiajs/vue3";

export default {
    layout: AdminLayout,
    props: {
        data: Object,
        typeBackend: Object,
        reportTypeBackend: Object,
        dateRangeBackend: Array,
        bunit: Array,
        columns: Array,
        bunitBackend: Object,
    },
    data() {
        return {
            isProgressing: false,
            colors: 'red',
            showGenerateButton: false,
            showPagination: false,
            dateRange: this.dateRangeBackend ? [this.dateRangeBackend[0] ? dayjs(this.dateRangeBackend[0]) : null, this.dateRangeBackend[1] ? dayjs(this.dateRangeBackend[1]) : null] : null,
            bunitCode: this.bunitBackend,
            repportType: this.reportTypeBackend,
            dataSource: [],
            isModalOpen: false,
            selectDataDetails: [],
            pdcdatedChecks: this.typeBackend,
            loading: false,
            loadingGenerate: false,
            searchBar: null,
            isTooltipVisible: false,
            isFetching: false,
            pagination: {
                current: 1,
                total: "",
                pageSize: 10,
            },
            query: {
                search: "",
            },
            progressBar: {
                currentRow: 0,
                percentage: 0,
                message: "",
                totalRows: 0,
            },
        };
    },

    methods: {
        details(data) {
            this.isModalOpen = true;
            this.selectDataDetails = data;
        },
        getData() {
            this.$inertia.get(route('reports.dpdc'), {
                dt_from: this.dateRange == null ? null : this.dt_from,
                dt_to: this.dateRange == null ? null : this.dt_to,
                bu: this.bunitCode,
                ch_type: this.pdcdatedChecks,
                repporttype: this.repportType,
            });
        },
        handleChangeDateRange(obj, str) {
            this.dt_from = str[0];
            this.dt_to = str[1];
        },

        formattedPrice(value) {
            // You can implement your own formatting logic here
            const formatted = new Intl.NumberFormat("en-PH", {
                style: "currency",
                currency: "PHP",
            }).format(value);

            return formatted;
        },
        handleChange(value) {
            console.log(`selected ${value}`);
        },

        handleTableChange(pagination) {
            this.fetchData(pagination);
        },

        searchQ() {
            this.fetchData(this.searchBar);
        },

        generateReport() {
            this.isProgressing = true;
            this.$inertia.get(route('generate.datedpdc.excel'), {
                redAdmin: 2,
                dt_from: this.dateRangeBackend[0],
                dt_to: this.dateRangeBackend[1],
                bu: this.bunitCode,
                ch_type: this.pdcdatedChecks,
                repporttype: this.repportType,
                search: this.query.search,
            });
        },
    },

    watch: {

        query: {
            deep: true,
            handler: debounce(async function () {
                this.isFetching = true,
                    this.$inertia.get(route("reports.dpdc"), {
                        dt_from: this.dateRange == null? null : this.dateRangeBackend[0],
                        dt_to:  this.dateRange == null? null : this.dateRangeBackend[1],
                        bu: this.bunitCode,
                        ch_type: this.pdcdatedChecks,
                        repporttype: this.repportType,
                        search: this.query.search,
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

    mounted() {
        this.$ws
            .private(`generating-dated-pdc-reports-accounting.${this.$page.props.auth.user.id}`)
            .listen(".generating-pdc-reports", (e) => {
                this.progressBar = e;
            });
    },
};
</script>
