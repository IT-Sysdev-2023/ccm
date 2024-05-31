<template>

    <Head title="Replacement Checks" />

    <div class="py-0">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <a-breadcrumb class="mb-2 mt-2">
                <a-breadcrumb-item href="">
                    <HomeOutlined />
                </a-breadcrumb-item>
                <a-breadcrumb-item href="">
                    <span>Dashboard</span>
                </a-breadcrumb-item>
                <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                <a-breadcrumb-item>Replace Checks</a-breadcrumb-item>
            </a-breadcrumb>
            <a-card>
                <div class="flex justify-between">
                    <a-select class="mb-3" ref="select" placeholder="Filter table" v-model:value="getMode"
                        style="width: 300px" @focus="focus" @change="handleChangeNode">
                        <a-select-option value="1">CHECK</a-select-option>
                        <a-select-option value="2">CHECK & CASH</a-select-option>
                        <a-select-option value="3">CASH</a-select-option>
                        <a-select-option value="4">RE DEPOSIT</a-select-option>
                        <a-select-option value="5">PARTIALS</a-select-option>
                    </a-select>

                    <a-input-search v-model:value="query.search" style="width: 350px;" class="mb-5"
                        placeholder="Search Checks" :loading="isFetching" />

                </div>
                <a-table bordered :pagination="false" :loading="isloadingtable" :data-source="data.data"
                    :columns="columns" size="small">
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'action'">
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
                <pagination class="mt-6" :datarecords="data" />
            </a-card>
        </div>
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
    <a-modal v-model:open="openModalCash" width="1000px" title="Replaced Cash Details" :footer="null"
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
    <a-modal v-model:open="openModalCashAndCheck" width="1000px" title="Replaced Cash Details"
        :footer="null" style="top: 50px">
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
    <a-modal v-model:open="openModalReDeposit" width="1000px" title="Replaced Cash Details"
        :footer="null" style="top: 50px">
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
import Pagination from "@/Components/Pagination.vue";
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import debounce from "lodash/debounce";

export default {
    layout: TreasuryLayout,
    data() {
        return {
            query: {
                search: ''
            },
            isFetching: false,
            isloadingtable: false,
            getMode: this.getModeProps,
            openModalCheck: false,
            openModalCash: false,
            openModalCashAndCheck: false,
            openModalPartial: false,
            openModalReDeposit: false,
            selectDataDetails: [],
            partialData: {},
            partialColumns: [
                {
                    title: "Date",
                    dataIndex: "date",
                    key: "name",
                },
                {
                    title: "Pending Amount",
                    dataIndex: "balanced",
                    key: "age",
                },
                {
                    title: "Cash Paid",
                    dataIndex: "cash",
                    key: "address",
                },
                {
                    title: "Check Paid",
                    dataIndex: "check_paid",
                    key: "address",
                },
                {
                    title: "Balance",
                    dataIndex: "balance",
                    key: "address",
                },
                {
                    title: "Penalty",
                    dataIndex: "penalty",
                    key: "address",
                },
                {
                    title: "Check No",
                    dataIndex: "checkNumber",
                    key: "address",
                },
                {
                    title: "Ar# and #Ds",
                    dataIndex: "ar_ds",
                    key: "address",
                },
                {
                    title: "Treasury",
                    dataIndex: "name",
                    key: "address",
                },
            ],
        };
    },
    props: {
        data: Array,
        columns: Array,
        pagination: Array,
        getModeProps: Object,
    },
    methods: {
        handleChangeNode() {
            this.$inertia.get(
                route("replace.checks"),
                {
                    getMode: this.getMode,
                },
                {
                    preserveScroll: true,
                }
            );
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
    watch: {
        query: {
            deep: true,
            handler: debounce(async function () {
                this.isFetching = true,
                    this.$inertia.get(route("replace.checks"), {
                        searchQuery: this.query.search,
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
