<template>
    <a-card>
        <a-select ref="select" class="mb-3" v-model:value="bunitSelected" style="width: 300px"
            @change="handlerBunitChange" placeholder="Select Business Unit">
            <a-select-option v-for="item in bunit" v-model:value="item.businessunit_id">{{ item.bname
                }}</a-select-option>
        </a-select>
        <a-table :data-source="data.data" :columns="columns" size="small" bordered :pagination="false">
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

        <pagination :datarecords="data" class="mt-10"></pagination>
    </a-card>
    <a-modal v-model:open="openModalCash" style="width: 50%;" title="Replacement Checks" :footer="false">
        <table class="tableModal">
            <thead>
                <th colspan="2">CHECK REPLACED TO CASH</th>
            </thead>
            <tbody>
                <tr>
                    <td>BOUNCED CHECK AMOUNT</td>
                    <td>{{ recordCash.check_amount }}</td>
                </tr>
                <tr>
                    <td>REPLACEMENT AMOUNT</td>
                    <td>{{ recordCash.check_amount_paid }}</td>
                </tr>
            </tbody>

        </table>
        <a-input class="mt-3" v-model:value.lazy="recordCash.penalty" autofocus placeholder="Lazy usage" />
        <a-input class="mt-3" v-model:value.lazy="recordCash.ar_ds" autofocus placeholder="Lazy usage" />
        <a-textarea class="mt-2" v-model:value="recordCash.reason" placeholder="Basic usage" :rows="4" />
        <div class="flex mt-2">
            <a-button block type="primary" danger>
                <template #icon>
                    <DisconnectOutlined />
                </template>
                Cancel Replacement
            </a-button>
            <a-button block class="ml-2" type="primary">
                <template #icon>
                    <SaveOutlined />
                </template>
                Save Changes
            </a-button>
        </div>

    </a-modal>
    <a-modal v-model:open="openModalReDeposit" style="width: 50%;" title="Replacement Checks" :footer="false">
        <table class="tableModal">
            <!-- {{ dataSelected }} -->
            <thead>
                <th colspan="2">CHECK RE-DEPOSIT</th>
            </thead>
            <tbody>
                <tr>
                    <td>REPLACEMENT CHECK AMOUNT</td>
                    <td>{{ recordDeposit.check_no }}</td>
                </tr>
                <tr>
                    <td>BOUNCED CHECK AMOUNT</td>
                    <td>{{ recordDeposit.check_amount }}</td>
                </tr>
                <tr>
                    <td>REPLACEMENT AMOUNT</td>
                    <td>{{ recordDeposit.check_amount_paid }}</td>
                </tr>

            </tbody>

        </table>
        <a-input class="mt-3" v-model:value.lazy="recordDeposit.penalty" autofocus placeholder="Lazy usage" />
        <a-input class="mt-3" v-model:value.lazy="recordDeposit.ar_ds" autofocus placeholder="Lazy usage" />
        <a-textarea class="mt-2" v-model:value="recordDeposit.reason" placeholder="Basic usage" :rows="4" />
        <div class="flex mt-2">
            <a-button block type="primary" danger>
                <template #icon>
                    <DisconnectOutlined />
                </template>
                Cancel Replacement
            </a-button>
            <a-button block class="ml-2" type="primary">
                <template #icon>
                    <SaveOutlined />
                </template>
                Save Changes
            </a-button>
        </div>

    </a-modal>
    <a-modal v-model:open="openModalCashAndCheck" style="width: 50%;" title="Replacement Checks" :footer="false">
        <table class="tableModal">
            <!-- {{ dataSelected }} -->
            <thead>
                <th colspan="2" class="" >CHECK REPLACED TO NEW CHECK AND CASH</th>
            </thead>
        </table>
        <p class="mt-2 ml-2  text-gray-600">Replace Check No.</p>
        <a-input class="mb-3" v-model:value.lazy="recordCheckCash.check_no" autofocus placeholder="Lazy usage" />
        <p class="mt-1 ml-2  text-gray-600">Replace Check Amount.</p>
        <a-input class="mb-3" v-model:value.lazy="recordCheckCash.check_amount" autofocus placeholder="Lazy usage" />
        <p class="mt-1 ml-2  text-gray-600">Replace Cash Amount.</p>
        <a-input class="mb-3" v-model:value.lazy="recordCheckCash.cash" autofocus placeholder="Lazy usage" />
        <p class="mt-1 ml-2  text-gray-600">Ar Ds.</p>
        <a-input class="mb-3" v-model:value.lazy="recordCheckCash.ar_ds" autofocus placeholder="Lazy usage" />
        <p class="mt-1 ml-2  text-gray-600">Penalty.</p>
        <a-input class="mb-3" v-model:value.lazy="recordCheckCash.penalty" autofocus placeholder="Lazy usage" />
        <p class="mt-1 ml-2  text-gray-600">Reason for return.</p>
        <a-textarea class="mb-3" v-model:value="recordCheckCash.reason" placeholder="Basic usage" :rows="4" />
        <div class="flex mt-2">
            <a-button block type="primary" danger>
                <template #icon>
                    <DisconnectOutlined />
                </template>
                Cancel Replacement
            </a-button>
            <a-button block class="ml-2" type="primary">
                <template #icon>
                    <SaveOutlined />
                </template>
                Save Changes
            </a-button>
        </div>

    </a-modal>
</template>
<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { SaveOutlined } from "@ant-design/icons-vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
export default {
    layout: AdminLayout,
    props: {
        data: Object,
        columns: Array,
        bunit: Object,
        bunitBackend: Number,

    },
    data() {
        return {
            openModalCheck: false,
            openModalCash: false,
            openModalCashAndCheck: false,
            openModalReDeposit: false,
            openModalPartial: false,
            partialData: [],
            bunitSelected: this.bunitBackend,
            recordCash: useForm({
                penalty: '',
                ar_ds: '',
                reason: ''
            }),
            recordDeposit: useForm({
                penalty: '',
                ar_ds: '',
                reason: ''
            }),
            recordCheckCash: useForm({
                penalty: '',
                check_no: '',
                ar_ds: '',
                reason: ''
            })
        }
    },
    methods: {
        handlerBunitChange(value) {
            this.$inertia.get(route('replace.checks.adjustments'), {
                bunit: value,
            }, {
                preserveState: true
            })
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
                    this.recordCash = response.data;
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
                    this.recordCash = useForm(response.data);
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
                    this.recordCheckCash = useForm(response.data);

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
                    this.recordDeposit = useForm(response.data);

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
    }
}
</script>

<style>
.tableModal {
    width: 100%;
    margin: 0 auto;
    border-collapse: collapse;
}

.tableModal th {
    padding: 8px;
    border: 1px solid #ddd;
    background: #e2e2e2;
    text-align: center;
}

.tableModal td {
    padding: 7px;
    border: 1px solid #ddd;
    text-align: left;
}
</style>
