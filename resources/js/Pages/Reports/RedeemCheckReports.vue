<template>

    <Head title="Redeem Checks Reports" />
    <div class="py-4 max-w-8xl mx-auto sm:px-6 lg:px-8">
        <a-breadcrumb class="mb-2">
            <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
            <a-breadcrumb-item><a href="">Reports</a></a-breadcrumb-item>
            <a-breadcrumb-item>Redeem Cheque Reports</a-breadcrumb-item>
        </a-breadcrumb>

        <div v-if="isGeneratingShow">
            <div class="flex justify-between mt-5">
                <div>
                    <p> {{ progressBar.message }}{{ progressBar.currentRow
                        }}
                        to
                        {{
                            progressBar.totalRows }}</p>
                </div>
            </div>
            <a-progress :stroke-color="{
                from: '#108ee9', to: '#87d068',
            }" :percent="progressBar.percentage" status="active" />
        </div>


        <div class="flex justify-between">
            <div>
                <a-range-picker :disabled="isFetching" style="width: 350px;" class="mr-1 mb-3"
                    v-model:value="dateRange" />
                <a-select ref="select" :disabled="isFetching" :loading="isFetching" v-model:value="bunitValue"
                    style="width: 250px" placeholder="Select Business Unit">
                    <a-select-option v-for="item in bunit" v-model:value="item.businessunit_id">{{ item.bname
                        }}</a-select-option>
                </a-select>
                <a-button class="ml-1" :disabled="isFetching" :loading="isFetching" style="width: 200px;" type="primary"
                    ghost @click="fetchingData">
                    <template #icon>
                        <LoginOutlined />
                    </template>
                    Fetch data
                </a-button>
            </div>
            <div>
                <a-button type="primary" :loading="isLoading" @click="startGeneratingRepors"
                    :disabled="data.data.length <= 0">
                    <template #icon>
                        <CloudUploadOutlined />
                    </template>
                    Generate Redeem Cheque Reports
                </a-button>
            </div>
        </div>
        <a-table :data-source="data.data" :columns="columns" size="small" bordered :pagination="false">
            <template #bodyCell="{ column, record }">
                <template v-if="column.key === 'details'">
                    <template v-if="record.mode === 'CASH'">
                        <a-button size="small" class="mx-2" @click="openModalCashButton(record)">
                            <template #icon>
                                <RedEnvelopeOutlined />
                            </template>
                        </a-button>
                    </template>

                    <template v-else-if="record.mode === 'RE-DEPOSIT'">
                        <a-button size="small" class="mx-2" @click="
                            openModalReDepositButton(record)
                            ">
                            <template #icon>
                                <DeliveredProcedureOutlined />
                            </template>
                        </a-button>
                    </template>

                    <template v-else-if="record.mode === 'PARTIAL'">
                        <a-button size="small" class="mx-2" @click="openModalPartialButton(record)">
                            <template #icon>
                                <BarChartOutlined />
                            </template>
                        </a-button>
                    </template>

                    <template v-else-if="record.mode === 'CHECK'">
                        <a-button size="small" class="mx-2" @click="openModalCheckButton(record)">
                            <template #icon>
                                <AuditOutlined />
                            </template>
                        </a-button>
                    </template>

                    <template v-else>
                        <a-button size="small" class="mx-2" @click="
                            openModalCashCheckButton(record)
                            ">
                            <template #icon>
                                <CreditCardOutlined />
                            </template>
                        </a-button>
                    </template>
                </template>
            </template>
        </a-table>
        <pagination :datarecords="data" class="mt-5">
        </pagination>
    </div>
    <a-modal v-model:open="openModalCheck" width="1000px" title="Replaced Check Details" :footer="null"
        style="top: 50px">
        <div class="product-table">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th colspan="2"
                            class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-t border-gray-200">
                            Check replaced to a new check
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- {{
                        selectDataDetails
                    }} -->
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Replaced check no.
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_no }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Replaced Check amount
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_amount }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Ar# and DS#
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.ar_ds }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Penalty amount
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.penalty }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a-card>
                <template #>
                    <div class="flex mb-3">
                        <InfoCircleOutlined style="color: blue" />
                        <p class="ml-1 font-bold">Reason for return</p>
                    </div>

                    <div class="ml-4">
                        {{ selectDataDetails.reason }}
                    </div>
                </template>
            </a-card>
        </div>
    </a-modal>
    <a-modal v-model:open="openModalCash" width="1000px" title="Replaced Cash Details" :footer="null" style="top: 50px">
        <div class="product-table">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th colspan="2"
                            class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-t border-gray-200">
                            Check replaced to cash
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Bounced check amount.
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_amount }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Replaced amount
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_amount_paid }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Ar# and DS#
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.ar_ds }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Penalty amount
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.penalty }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a-card>
                <template #>
                    <div class="flex mb-3">
                        <InfoCircleOutlined style="color: blue" />
                        <p class="ml-1 font-bold">Reason for return</p>
                    </div>

                    <div class="ml-4">
                        {{ selectDataDetails.reason }}
                    </div>
                </template>
            </a-card>
        </div>
    </a-modal>
    <a-modal v-model:open="openModalCashAndCheck" width="1000px" title="Replaced Cash Details" :footer="null"
        style="top: 50px">
        <div class="product-table">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th colspan="2"
                            class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-t border-gray-200">
                            Check replaced to cash
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Replaced check no.
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_no }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Replaced check amount.
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_amount }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Replaced amount
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.cash }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Ar# and DS#
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.ar_ds }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Penalty amount
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.penalty }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a-card>
                <template #>
                    <div class="flex mb-3">
                        <InfoCircleOutlined style="color: blue" />
                        <p class="ml-1 font-bold">Reason for return</p>
                    </div>

                    <div class="ml-4">
                        {{ selectDataDetails.reason }}
                    </div>
                </template>
            </a-card>
        </div>
    </a-modal>
    <a-modal v-model:open="openModalReDeposit" width="1000px" title="Replaced Cash Details" :footer="null"
        style="top: 50px">
        <div class="product-table">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th colspan="2"
                            class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-t border-gray-200">
                            Check Redeposit
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Replaced check no.
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_no }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Replaced check amount.
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_amount }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Replaced amount
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.cash }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Ar# and DS#
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.ar_ds }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-1 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Penalty amount
                        </td>
                        <td class="px-6 py-1 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.penalty }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a-card>
                <template #>
                    <div class="flex mb-3">
                        <InfoCircleOutlined style="color: blue" />
                        <p class="ml-1 font-bold">Reason for return</p>
                    </div>

                    <div class="ml-4">
                        {{ selectDataDetails.reason }}
                    </div>
                </template>
            </a-card>
        </div>
    </a-modal>
    <a-modal v-model:open="openModalPartial" width="100%" title="Replaced Cash Details" :footer="null"
        style="top: 50px">
        <a-card style="border: none">
            <a-table bordered :data-source="partialData" size="small" :columns="partialColumns">
            </a-table>
        </a-card>
    </a-modal>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import dayjs from "dayjs";
export default {
    layout: AdminLayout,
    props: {
        data: Object,
        bunit: Object,
        columns: Array,
        dateRangeBackend: Array,
        bunitBackend: Number,
    },
    data() {
        return {
            openModalCheck: false,
            openModalCash: false,
            openModalCashAndCheck: false,
            openModalPartial: false,
            openModalReDeposit: false,
            selectDataDetails: [],
            isGeneratingShow: false,
            progressBar: {
                currentRow: 0,
                percentage: 0,
                message: "",
                totalRows: 0,
            },
            isFetching: false,
            isLoading: false,
            bunitValue: this.bunitBackend,
            dateRange: this.dateRangeBackend ? [this.dateRangeBackend[0] ? dayjs(this.dateRangeBackend[0]) : null, this.dateRangeBackend[1] ? dayjs(this.dateRangeBackend[1]) : null] : null,
        }
    },
    methods: {
        details(data) {
            this.isOpenModal = true;
            this.selectDataDetails = data;
        },
        fetchingData() {
            this.isFetching = true;
            this.$inertia.get(route('redeem.reports.admin'), {
                bunitId: this.bunitValue,
                dateFrom: (this.dateRange && this.dateRange[0] !== null) ? dayjs(this.dateRange[0]).format('YYYY-MM-DD') : null,
                dateTo: (this.dateRange && this.dateRange[1] !== null) ? dayjs(this.dateRange[1]).format('YYYY-MM-DD') : null,
            });
        },
        startGeneratingRepors() {
            this.isLoading = true;
            this.isGeneratingShow = true;
            this.$inertia.get(route('start.generate.redeem.reports'), {
                bunitId: this.bunitValue,
                dateFrom: (this.dateRange && this.dateRange[0] !== null) ? dayjs(this.dateRange[0]).format('YYYY-MM-DD') : null,
                dateTo: (this.dateRange && this.dateRange[1] !== null) ? dayjs(this.dateRange[1]).format('YYYY-MM-DD') : null,
                reDirect: 2,
            });
        },
        openModalCheckButton(checkData) {
            axios
                .get(route("replacment.details"), {
                    params: {
                        checksId: checkData.checks_id,
                        bouncedId: checkData.bounce_id,
                    },
                })
                .then((response) => {
                    this.selectDataDetails = response.data;
                    this.openModalCheck = true;
                })
                .catch((error) => {
                    // Handle any errors that occur during the request
                    console.error(error);
                });
        },
        openModalCashButton(cashData) {
            axios
                .get(route("replacment.details"), {
                    params: {
                        checksId: cashData.checks_id,
                        bouncedId: cashData.bounce_id,
                    },
                })
                .then((response) => {
                    this.selectDataDetails = response.data;
                    // console.log(this.selectDataDetails);
                    this.openModalCash = true;
                })
                .catch((error) => {
                    // Handle any errors that occur during the request
                    console.error(error);
                });
        },
        openModalCashCheckButton(cashCheckData) {
            axios
                .get(route("replacment.details"), {
                    params: {
                        checksId: cashCheckData.checks_id,
                        bouncedId: cashCheckData.bounce_id,
                    },
                })
                .then((response) => {
                    this.selectDataDetails = response.data;

                    this.openModalCashAndCheck = true;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        openModalReDepositButton(reDepositData) {
            axios
                .get(route("replacment.details"), {
                    params: {
                        checksId: reDepositData.checks_id,
                        bouncedId: reDepositData.bounce_id,
                    },
                })
                .then((response) => {
                    this.selectDataDetails = response.data;

                    this.openModalReDeposit = true;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        openModalPartialButton(partialData) {
            axios
                .get(route("replacmentpartialTable.details"), {
                    params: {
                        checksId: partialData.checks_id,
                        bouncedId: partialData.bounce_id,
                    },
                })
                .then((response) => {
                    this.partialData = response.data;
                    this.openModalPartial = true;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
    mounted() {
        this.$ws
            .private(`generating-redeem-reports.${this.$page.props.auth.user.id}`)
            .listen(".generating-redeem-reports", (e) => {
                this.progressBar = e;
            });
    }
}
</script>

<style scoped>
.product-table {
    margin: 20px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
}

.product-table tr {
    border: 1px solid #ddd;
    width: 10%;
}

.product-table td {
    border: 1px solid #ddd;
}

.product-table th {
    border: 1px solid #b1b1b1;
    background: #d8d8d8;
}
</style>
