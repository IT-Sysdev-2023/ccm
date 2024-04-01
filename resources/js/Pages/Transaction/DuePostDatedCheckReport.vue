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
                    <a-breadcrumb-item
                        ><a href="">Transaction </a></a-breadcrumb-item
                    >
                    <a-breadcrumb-item
                        >Due Post Dated Checks Report</a-breadcrumb-item
                    >
                </a-breadcrumb>
                <div v-if="isProgressShowing" style="font-size: 14px">
                    <div class="flex justify-between">
                        <div class="flex">
                            <DoubleRightOutlined class="mr-2" />
                            <p>
                                {{ progressBar.message }}
                                {{ progressBar.department }}
                                {{ progressBar.currentRow }} to
                                {{ progressBar.totalRows }} records
                            </p>
                        </div>
                        <div class="flex">
                            <InfoCircleTwoTone class="mr-2" />
                            <p>
                                "Please note: Do not exit the page while
                                generating or navigate to another link within
                                this system."
                            </p>
                        </div>
                    </div>
                    <a-progress
                        :stroke-color="{
                            from: '#108ee9',
                            to: '#87d068',
                        }"
                        :percent="progressBar.percentage"
                        status="active"
                    />
                </div>

                <a-card>
                    <div class="flex justify-between mb-10">
                        <div>
                            <a-range-picker
                                @change="handleDateGenerate"
                                v-model:value="dateRange"
                            />
                        </div>
                        <div>
                            <h2>{{ buname.bname }}</h2>
                        </div>
                        <div>
                            <a-button
                                @click="startGenerating"
                                type="primary"
                                :loading="isLoading"
                                :disabled="!data.data.length"
                            >
                                <template #icon>
                                    <UploadOutlined />
                                </template>
                                {{
                                    isLoading
                                        ? "Generating due post dated check report..."
                                        : "Generate Due Post Dated Check Report Excel"
                                }}
                            </a-button>
                        </div>
                    </div>
                    <a-table
                        :data-source="data.data"
                        :columns="columns"
                        bordered
                        size="small"
                        :pagination="false"
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'details'">
                                <a-button
                                    size="small"
                                    class="mx-1"
                                    @click="openUpDetails(record)"
                                >
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

        <a-modal
            v-model:open="openDetails"
            style="top: 25px"
            width="1000px"
            title="Details"
            @ok="handleOk"
            :ok-button-props="{ hidden: true }"
            :cancel-button-props="{ hidden: true }"
            :footer="null"
        >
            <div class="product-table">
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-t border-gray-200"
                            >
                                Customer Name
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-t border-gray-200"
                            >
                                {{ selectDataDetails.fullname }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200"
                            >
                                Check From
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200"
                            >
                                {{ selectDataDetails.department }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200"
                            >
                                Check Number
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200"
                            >
                                {{ selectDataDetails.check_no }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200"
                            >
                                Approving Officer
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200"
                            >
                                {{ selectDataDetails.approving_officer }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200"
                            >
                                Check Class
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200"
                            >
                                {{ selectDataDetails.check_class }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200"
                            >
                                Check Status
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200"
                            >
                                {{ selectDataDetails.check_status }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200"
                            >
                                Check Date
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200"
                            >
                                {{ selectDataDetails.check_date }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200"
                            >
                                Account No
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200"
                            >
                                {{ selectDataDetails.account_no }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200"
                            >
                                Check Received
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200"
                            >
                                {{ selectDataDetails.check_received }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200"
                            >
                                Account Name
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200"
                            >
                                {{ selectDataDetails.account_name }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200"
                            >
                                Received As
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200"
                            >
                                {{ selectDataDetails.check_type }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200"
                            >
                                Bank Name
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200"
                            >
                                {{ selectDataDetails.bankbranchname }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200"
                            >
                                Check Category
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200"
                            >
                                {{ selectDataDetails.check_category }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200"
                            >
                                Amount
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200"
                            >
                                {{ selectDataDetails.check_amount }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </a-modal>
    </TreasuryLayout>
</template>

<script>
import dayjs from "dayjs";
import { message } from "ant-design-vue";
import Pagination from "@/Components/Pagination.vue";
import { InfoCircleTwoTone } from "@ant-design/icons-vue";
export default {
    props: {
        data: Array,
        columns: Array,
        dateRangeValue: Object,
        buname: Array,
    },
    data() {
        return {
            statusValue: this.status,
            dateRange:
                this.dateRangeValue?.length > 0
                    ? [
                          dayjs(this.dateRangeValue[0]),
                          dayjs(this.dateRangeValue[1]),
                      ]
                    : null,
            isLoading: false,
            openDetails: false,
            selectDataDetails: [],
            isProgressShowing: false,
            progressBar: {
                currentRow: 0,
                percentage: 0,
                department: "",
                message: "",
                totalRows: 0,
            },
        };
    },
    methods: {
        openUpDetails(inData) {
            this.openDetails = true;
            this.selectDataDetails = inData;
        },
        handleDateGenerate(obj, str) {
            this.$inertia.get(route("duePdcReports.checks"), {
                date_from: str[0],
                date_to: str[1],
            });
        },
        startGenerating() {
            this.isProgressShowing = true;
            this.isLoading = true;
            this.$inertia.post(
                route("generate_duepdcrep.checks"),
                {
                    date_from:
                        this.dateRangeValue?.length > 0
                            ? dayjs(this.dateRangeValue[0]).format("YYYY-MM-DD")
                            : "",
                    date_to:
                        this.dateRangeValue?.length > 0
                            ? dayjs(this.dateRangeValue[1]).format("YYYY-MM-DD")
                            : "",
                },
                {
                    onFinish: () => {
                        this.isLoading = false;
                    },
                }
            );
        },
    },
    mounted() {
        this.$ws
            .private(`excel-progress.${this.$page.props.auth.user.id}`)
            .listen(".generate-excel", (e) => {
                this.progressBar = e;
            });
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
