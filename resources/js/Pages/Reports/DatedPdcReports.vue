<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head } from "@inertiajs/vue3";
const colors = "red";
</script>

<template>

    <Head title="Dated & Post Dated Checks Report" />

    <AdminLayout>
        <template #header> </template>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-page-header style="
                        margin-bottom: 2px;
                        border: 1px solid rgb(235, 237, 240);
                        border-radius: 10px;
                        height: 50px;
                        display: flex;
                        align-items: center;
                    " title="Dated/Postdated Checks Report" :avatar="{
                        src: 'https://png.pngtree.com/png-vector/20221013/ourmid/pngtree-calendar-icon-logo-2023-date-time-png-image_6310337.png',
                    }">
                </a-page-header>
                <div class="" style="display: flex; justify-content: space-between">
                    <div class="flex">
                        <a-range-picker v-model:value="dateRange" class="mt-2 mr-2" style="width: 250px" />
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
                        <a-button style="
                                background-color: rgb(207, 250, 225);
                                margin-right: 10px;
                                width: 200px;
                            " v-on:click="generateReport" v-if="showGenerateButton" :loading="loadingGenerate">
                            Generate to Excel
                        </a-button>
                        <a-button style="
                                background-color: rgb(193, 224, 253);
                                margin-right: 10px;
                                width: 200px;
                            " v-on:click="fetchData">
                            Fetch Data
                        </a-button>
                    </div>
                </div>
                <div class="flex mt-5 justify-end mx-0">
                    <a-input placeholder="Search Cheques" v-model:value="query.search" style="width: 420px">

                        <template #prefix>
                            <FileSearchOutlined />
                        </template>

                        <template #suffix>
                            <a-tooltip title="Please input name or check number to filter ">
                                <InfoCircleOutlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                </div>
                <div class="mt-4">
                    <a-table :total="85" :dataSource="dataSource" size="middle" class="components-table-demo-nested"
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
                                <a-button size="small">
                                    <BarsOutlined />
                                </a-button>
                            </template>
                        </template>
                    </a-table>
                    <div v-if="showPagination" class="mt-3 mb-5" style="
                            border: 1px solid rgb(224, 224, 224);
                            border-radius: 10px;
                            padding: 10px;
                        ">
                        <div class="flex justify-end">
                            <a-pagination class="mt-0 mb-0" v-model:current="pagination.current"
                                v-model:page-size="pagination.pageSize" :show-size-changer="false"
                                :total="pagination.total" :show-total="(total, range) =>
                        `${range[0]}-${range[1]} of ${total} reports`
                        " @change="handleTableChange" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import debounce from "lodash/debounce";
import axios from "axios";
import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
import { message } from "ant-design-vue";
import dayjs from "dayjs";

export default {
    props: {
        bunit: Array,
        columns: Array,
    },
    data() {
        return {
            showGenerateButton: false,
            showPagination: false,
            dateRange: null,
            bunitCode: null,
            repportType: null,
            dataSource: [],
            pdcdatedChecks: null,
            loading: false,
            loadingGenerate: false,
            searchBar: null,
            isTooltipVisible: false,
            pagination: {
                current: 1,
                total: "",
                pageSize: 10,
            },
            query: {
                search: "",
            },

            pageCustom: 1,
        };
    },
    watch: {
        query: {
            deep: true,
            handler: debounce(async function () {
                this.loading = true;
                try {
                    const res = await axios.get(
                        `get_dated_pdc_checks_rep?page=${this.pageCustom}`,
                        {
                            params: {
                                dt_from: this.dateRange[0].toISOString(),
                                dt_to: this.dateRange[1].toISOString(),
                                bu: this.bunitCode,
                                ch_type: this.pdcdatedChecks,
                                repporttype: this.repportType,
                                search: this.query.search,
                            },
                        }
                    );
                    this.showGenerateButton = true;
                    this.dataSource = res.data.data;
                    this.pagination = res.data.pagination;

                    if (res.data.data.length > 0) {
                        this.showGenerateButton = true;
                    } else {
                        this.showGenerateButton = false;
                    }
                } catch (error) {
                    // Handle error, you can log it or show a message
                    console.error("Error in watcher:", error);
                } finally {
                    this.loading = false;
                }
            }, 500),
        },
    },

    methods: {
        async fetchData(page = 1) {
            this.loading = true;

            if (
                this.bunitCode === null &&
                this.pdcdatedChecks === null &&
                this.repportType === null
            ) {
                this.isTooltipVisible = true;
            } else {
                this.isTooltipVisible = false;
            }

            if (!this.dateRange) {
                try {
                    const response = await axios.get(
                        `get_dated_pdc_checks_rep?page=${page}`,
                        {
                            params: {
                                dt_from: "",
                                dt_to: "",
                                bu: this.bunitCode,
                                ch_type: this.pdcdatedChecks,
                                repporttype: this.repportType,
                                search: this.searchBar,
                            },
                        }
                    );

                    this.showPagination = true;
                    this.showGenerateButton = true;
                    this.dataSource = response.data.data;
                    this.pagination = response.data.pagination;
                    if (response.data.data.length > 0) {
                        this.showGenerateButton = true;
                    } else {
                        this.showGenerateButton = false;
                    }
                } catch (error) {
                    console.error("Error fetching data:", error);
                } finally {
                    this.loading = false;
                }
            } else {
                try {
                    const response = await axios.get(
                        `get_dated_pdc_checks_rep?page=${page}`,
                        {
                            params: {
                                dt_from: dayjs(this.dateRange[0]).format(
                                    "YYYY-MM-DD"
                                ),
                                dt_to: dayjs(this.dateRange[1]).format(
                                    "YYYY-MM-DD"
                                ),
                                bu: this.bunitCode,
                                ch_type: this.pdcdatedChecks,
                                repporttype: this.repportType,
                                search: this.searchBar,
                            },
                        }
                    );

                    this.showPagination = true;
                    this.dataSource = response.data.data;
                    this.pagination = response.data.pagination;
                    if (response.data.data.length > 0) {
                        this.showGenerateButton = true;
                    } else {
                        this.showGenerateButton = false;
                    }
                } catch (error) {
                    console.error("Error fetching data:", error);
                } finally {
                    this.loading = false;
                }
            }
        },

        // formattedDate(event) {
        //     const date = new Date(event);
        //     const options = {
        //         year: "numeric",
        //         month: "long",
        //         day: "numeric",
        //     };
        //     return date.toLocaleDateString("en-US", options);
        // },
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
            this.loadingGenerate = true;
            const params = {
                dt_from:
                    this.dateRange && this.dateRange[0]
                        ? this.dateRange[0].toISOString()
                        : "",
                dt_to:
                    this.dateRange && this.dateRange[1]
                        ? this.dateRange[1].toISOString()
                        : "",
                bu: this.bunitCode,
                ch_type: this.pdcdatedChecks,
                repporttype: this.repportType,
            };

            const urlWithParams =
                "/generate_reps_to_excel?" +
                new URLSearchParams(params).toString();
            window.location.href = urlWithParams;

            this.$inertia.get(urlWithParams, {}, {
                onFinish: () => {
                    message.success("Successfully Generated excel file");
                    this.loadingGenerate = false;
                },
            });
        },
    },
};
</script>
