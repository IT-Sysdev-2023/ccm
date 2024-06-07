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
            <div class="flex justify-between">
                <a-select class="mb-3" ref="select" placeholder="Filter table" v-model:value="getMode"
                    style="width: 300px" @focus="focus" @change="handleChangeNode">
                    <a-select-option value="1">CHECK</a-select-option>
                    <a-select-option value="2">CHECK & CASH</a-select-option>
                    <a-select-option value="3">CASH</a-select-option>
                    <a-select-option value="4">RE DEPOSIT</a-select-option>
                    <a-select-option value="5">PARTIALS</a-select-option>
                </a-select>

                <a-input-search v-model:value="form.search" style="width: 350px;" class="mb-5"
                    placeholder="Search Checks" :loading="isFetching" />

            </div>
            <a-table bordered :pagination="false" :loading="isloadingtable" :data-source="data.data" :columns="columns"
                size="small">
                <template #bodyCell="{ column, record }">
                    <template v-if="column.dataIndex">
                        <span v-html="highlightText(record[column.dataIndex], form.search)
                            ">
                        </span>
                    </template>
                    <template v-if="column.key === 'action'">
                        <template v-if="record.mode === 'CASH'">
                            <a-button class="mx-2" @click="openModalCashButton(record)">
                                <template #icon>
                                    <RedEnvelopeOutlined />
                                </template>
                            </a-button>
                        </template>

                        <template v-else-if="record.mode === 'RE-DEPOSIT'">
                            <a-button class="mx-2" @click="
                                openModalReDepositButton(record)
                                ">
                                <template #icon>
                                    <DeliveredProcedureOutlined />
                                </template>
                            </a-button>
                        </template>

                        <template v-else-if="record.mode === 'PARTIAL'">
                            <a-button class="mx-2" @click="openModalPartialButton(record)">
                                <template #icon>
                                    <BarChartOutlined />
                                </template>
                            </a-button>
                        </template>

                        <template v-else-if="record.mode === 'CHECK'">
                            <a-button class="mx-2" @click="openModalCheckButton(record)">
                                <template #icon>
                                    <AuditOutlined />
                                </template>
                            </a-button>
                        </template>

                        <template v-else>
                            <a-button class="mx-2" @click="
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
            <pagination class="mt-6 mb-6" :datarecords="data" />
        </div>
    </div>

    <ReplaceCashAndChecks v-model:open="openModalCashAndCheck" :selectDataDetails="selectDataDetails" title="Replacement Cash And Checks"/>
    <ReplaceCashDetails  v-model:open="openModalCash" :selectDataDetails="selectDataDetails" title="Replacement Cash"/>
    <ReplaceCheckDetails v-model:open="openModalCheck" :selectDataDetails="selectDataDetails" title="Replacement Check"/>
    <ReplaceDeposit v-model:open="openModalReDeposit" :selectDataDetails="selectDataDetails" title="Replacement Redeposit"/>
    <ReplacePartials v-model:open="openModalPartial" :partialData="partialData" title="Replacement Partials"/>

</template>

<script>
import Pagination from "@/Components/Pagination.vue";
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import debounce from "lodash/debounce";
import { highlighten } from "@/Mixin/highlighten.js";
import ReplaceCashAndChecks from "@/Pages/Transaction/Modals/Replacements/ReplaceCashAndChecks.vue"
import ReplaceCashDetails from "@/Pages/Transaction/Modals/Replacements/ReplaceCashDetails.vue"
import ReplaceCheckDetails from "@/Pages/Transaction/Modals/Replacements/ReplaceCheckDetails.vue"
import ReplaceDeposit from "@/Pages/Transaction/Modals/Replacements/ReplaceDeposit.vue"
import ReplacePartials from "@/Pages/Transaction/Modals/Replacements/ReplacePartials.vue"

export default {
    layout: TreasuryLayout,
    setup() {
        const { highlightText } = highlighten();
        return { highlightText };
    },
    data() {
        return {
            form: {
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
        form: {
            deep: true,
            handler: debounce(async function () {
                this.isFetching = true,
                    this.$inertia.get(route("replace.checks"), {
                        search: this.form.search,
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
