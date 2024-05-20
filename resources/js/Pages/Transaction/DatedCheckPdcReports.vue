<template>

    <Head title="Dashboard" />

        <div class="py-0">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mt-2 mb-3">
                    <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                    <a-breadcrumb-item><a href="">Transaction </a></a-breadcrumb-item>
                    <a-breadcrumb-item>Partial Payments</a-breadcrumb-item>
                </a-breadcrumb>
                <div v-if="isProgressShowing" style="font-size: 14px;">
                    <div>
                        <div v-if="isProgressShowing" class="mb-10">
                            <div class="flex justify-between">
                                <div>
                                    <p> {{ progressBar.message }} {{ progressBar.department }} {{ progressBar.currentRow
                                        }}
                                        to
                                        {{
                    progressBar.totalRows }}</p>
                                </div>

                                <div>
                                    <span class="font-bold">{{ progressBar.preProcess }} </span> out of
                                    <span class="font-bold">{{ progressBar.departmentToBeProcessed }}</span>
                                </div>
                            </div>
                            <a-progress :stroke-color="{
                    from: '#108ee9', to: '#87d068',
                }" :percent="progressBar.percentage" status="active" />
                        </div>


                    </div>
                </div>
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
                            <a-button @click="startGenerating" type="primary" :loading="isLoading" :disabled="!statusValue ||
                    !dateRange ||
                    !data.data.length
                    ">
                                <template #icon>
                                    <UploadOutlined />
                                </template>
                                {{ isLoading ? 'Generating in progress please wait it will takes some time...' : 'Generate Dated and Post Dated Check report excel' }}
                            </a-button>
                        </div>
                    </div>

                    <a-table :data-source="data.data" :pagination="false" :columns="columns" size="small" bordered>
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'details'">
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
        <a-modal v-model:open="openDetails" style="top: 25px" width="1000px" title="Details" @ok="handleOk"
            :ok-button-props="{ hidden: true }" :cancel-button-props="{ hidden: true }" :footer="null">
            <div class="product-table">
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-t border-gray-200">
                                Customer Name
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-t border-gray-200">
                                {{ selectDataDetails.fullname }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check From
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.department }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Number
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_no }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Approving Officer
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.approving_officer }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Class
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_class }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Status
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_status }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Date
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_date }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Account No
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.account_no }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Received
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_received }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Account Name
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.account_name }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Received As
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_type }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Bank Name
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.bankbranchname }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Category
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_category }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Amount
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_amount }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </a-modal>
</template>

<script>
import dayjs from "dayjs";
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import { message } from "ant-design-vue";
import Pagination from "@/Components/Pagination.vue";
export default {
    layout: TreasuryLayout,
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
            progressBar: {
                currentRow: 0,
                percentage: 0,
                department: "",
                message: "",
                totalRows: 0,
                preProcess: 0,
                departmentToBeProcessed: 0
            },
            statusValue: this.status,
            dateRange: [
                dayjs(this.dateRangeValue[0]),
                dayjs(this.dateRangeValue[1]),
            ],
            isLoading: false,
            openDetails: false,
            selectDataDetails: [],
            isProgressShowing: false,
        };
    },
    mounted() {
        this.$ws
            .private(`excel-progress.${this.$page.props.auth.user.id}`)
            .listen(".generate-excel", (e) => {
                this.progressBar = e;
            });
    },

    methods: {
        handleDateGenerate(obj, str) {
            console.log(this.dateRangeValue);
            this.$inertia.get(
                route("dcpdc.checks"),
                {
                    status: this.statusValue,
                    date_from: str[0],
                    date_to: str[1],
                },
                {
                    preserveState: true,
                }
            );
        },
        handleChangeStatus() {
            this.$inertia.get(
                route("dcpdc.checks"),
                {
                    status: this.statusValue,
                    date_from: this.filters.date_from || "",
                    date_to: this.filters.date_to || "",
                },
                {
                    preserveState: true,
                }
            );
        },
        startGenerating() {
            this.isProgressShowing = true;
            this.isLoading = true;

            this.$inertia.get(route('generate_report.checks'), {
                status: this.statusValue,
                date_from: dayjs(this.dateRangeValue[0]).format("YYYY-MM-DD"),
                date_to: dayjs(this.dateRangeValue[1]).format("YYYY-MM-DD"),
            });
        },

        openUpDetails(inData) {
            this.openDetails = true;
            this.selectDataDetails = inData;
        },
    },
};
</script>

<style>
.product-table {
    margin: 20px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
}

.product-table tr {
    border: 1px solid #ddd;
}

.product-table td {
    border: 1px solid #ddd;
}
</style>
