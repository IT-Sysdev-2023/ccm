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
                    <a-breadcrumb-item>Partial Payments</a-breadcrumb-item>
                </a-breadcrumb>
                <a-card>
                    <a-table
                        :data-source="data.data"
                        :pagination="false"
                        :columns="columns"
                        size="small"
                        bordered
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'action'">
                                <a-button
                                    size="small"
                                    class="mx-1"
                                    @click="openUpDetails(record)"
                                >
                                    <template #icon>
                                        <SettingOutlined />
                                    </template>
                                </a-button>
                                <a-button
                                    size="small"
                                    class="mx-1"
                                    @click="partialPaymentNotNull(record)"
                                    v-if="record.bounce_id != 0"
                                >
                                    <template #icon>
                                        <IdcardOutlined />
                                    </template>
                                </a-button>
                                <a-button
                                    size="small"
                                    class="mx-1"
                                    @click="openUpDetails(record)"
                                    v-else
                                >
                                    <template #icon>
                                        <IdcardFilled />
                                    </template>
                                </a-button>
                                <a-button
                                    size="small"
                                    class="mx-1"
                                    @click="openUpDetails(record)"
                                >
                                    <template #icon>
                                        <CreditCardFilled />
                                    </template>
                                </a-button>
                            </template>
                        </template>
                    </a-table>
                    <pagination class="mt-6" :datarecords="data"></pagination>
                </a-card>
            </div>
        </div>
    </TreasuryLayout>
    <a-modal
        v-model:open="openModalPPayment"
        title="Partial payment modal"
        width="100%"
        style="top: 10px"
        :footer="null"
        wrap-class-name="full-modal"
    >
        <!-- {{ selectDataDetails }}, {{ grandTotal }} ,{{ days }} -->
        <a-row :gutter="[16, 16]">
            <a-col :span="8">
                <a-card style="background-color: #f5f7f8">
                    <a-card>
                        <div class="flex justify-between">
                            <p class="text-gray-600 font-bold">
                                Bounce check amout:
                            </p>
                            <p class="font-bold">
                                {{ selectedPartialData.check_amount }}
                            </p>
                        </div>
                        <div class="flex justify-between mt-2">
                            <p class="text-gray-600 font-bold">
                                Bounce check balance:
                            </p>
                            <p class="font-bold">
                                {{ grandTotal.toLocaleString() }}
                            </p>
                        </div>
                    </a-card>
                    <a-card class="mt-1">
                        <h4 class="text-center mb-9 text-blue-800">
                            Please fill the input fields
                        </h4>
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <DollarOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Cash amount</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-input
                            v-model:value="partial_pay_form.checkAmount"
                            placeholder="input with clear icon"
                            allow-clear
                        />
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <DollarOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Penalty</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-input
                            v-model:value="partial_pay_form.parPenalty"
                            placeholder="input with clear icon"
                            allow-clear
                        />
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <NumberOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Ar# and Ds#</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-input
                            v-model:value="partial_pay_form.parArDs"
                            placeholder="input with clear icon"
                            allow-clear
                        />

                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <CalendarOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item
                                >Replacement Date</a-breadcrumb-item
                            >
                        </a-breadcrumb>
                        <a-date-picker
                            v-model:value="partial_pay_form.parRepDate"
                            style="width: 100%"
                        />
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <home-outlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item
                                >Reason of replace</a-breadcrumb-item
                            >
                        </a-breadcrumb>
                        <a-textarea
                            v-model:value="partial_pay_form.parReason"
                            placeholder="Enter here"
                            :rows="4"
                        />
                        <div class="flex justify-between mt-5">
                            <a-button
                                style="width: 100%; background-color: gainsboro"
                                @click="
                                    () =>
                                        partial_pay_form.reset(
                                            'parArDs',
                                            'parPenalty',
                                            'parReason',
                                            'parRepDate'
                                        )
                                "
                                class="mr-2"
                            >
                                <template #icon>
                                    <ClearOutlined />
                                </template>
                                clear input
                            </a-button>
                            <a-button
                                style="width: 100%"
                                type="primary"
                                @click="saveChangesPartialPayment"
                                :loading="partial_pay_form.processing"
                            >
                                <template #icon>
                                    <SaveOutlined></SaveOutlined>
                                </template>
                                {{
                                    partial_pay_form.processing
                                        ? "Submitting checks..."
                                        : "Save changes"
                                }}
                            </a-button>
                        </div>
                    </a-card>
                </a-card>
            </a-col>
            <a-col :span="16">
                <a-card style="background-color: #f5f7f8">
                    <h4 class="text-center mb-10">Payment history List</h4>
                    <a-table
                        bordered
                        size="small"
                        :data-source="selectDataDetails"
                        :columns="payParColumns"
                    >
                    </a-table>
                </a-card>
            </a-col>
        </a-row>
    </a-modal>
</template>

<script>
import Pagination from "@/Components/Pagination.vue";
import {
    CalendarOutlined,
    DollarCircleFilled,
    IdcardFilled,
    MoneyCollectFilled,
    MoneyCollectTwoTone,
    SettingOutlined,
} from "@ant-design/icons-vue";
import { DollarCircleOutlined } from "@ant-design/icons";
import axios from "axios";
import { useForm } from "@inertiajs/vue3";
import dayjs from "dayjs";
import { message } from "ant-design-vue";
export default {
    props: {
        data: {},
        columns: Array,
    },
    data() {
        return {
            openModalPPayment: false,
            selectDataDetails: {},
            selectedPartialData: {},
            grandTotal: 0,
            days: 0,
            payParColumns: [
                {
                    title: "No",
                    dataIndex: "count",
                    key: "name",
                },
                {
                    title: "Cash amount",
                    dataIndex: "cash",
                    key: "name",
                },
                {
                    title: "Check amount",
                    dataIndex: "cashAmountPaid",
                    key: "name",
                },
                {
                    title: "Penalty amount",
                    dataIndex: "penalty",
                    key: "name",
                },
                {
                    title: "Ar Ds",
                    dataIndex: "arDs",
                    key: "name",
                },
                {
                    title: "Reason",
                    dataIndex: "reason",
                    key: "name",
                },
                {
                    title: "Treasury",
                    dataIndex: "name",
                    key: "name",
                },
                {
                    title: "Date",
                    dataIndex: "dateTime",
                    key: "dateTime",
                },
            ],

            partial_pay_form: useForm({
                checksId: null,
                bouncedId: null,
                checkAmount: null,
                parPenalty: null,
                parArDs: null,
                parReason: null,
                parRepDate: "",
                checkAmountBalance: null,
            }),
        };
    },
    methods: {
        async partialPaymentNotNull(payParNotnull) {
            this.selectedPartialData = payParNotnull;
            this.partial_pay_form.checksId = payParNotnull.checks_id;
            this.partial_pay_form.bouncedId = payParNotnull.bounce_id;

            await axios
                .get(route("partialpaynotnull.partials"), {
                    params: {
                        checksId: payParNotnull.checks_id,
                        bouncedId: payParNotnull.bounce_id,
                    },
                })
                .then((response) => {
                    const { data, grandTotal, days } = response.data;

                    this.selectDataDetails = data;
                    this.grandTotal = grandTotal;
                    this.days = days;
                    this.openModalPPayment = true;

                    this.partial_pay_form.checkAmount = grandTotal;
                    this.partial_pay_form.checkAmountBalance = grandTotal;

                    if (this.partial_pay_form.bouncedId != 0) {
                        let grandTotalPar = grandTotal;
                        let penalty =
                            parseFloat(grandTotalPar) * parseFloat(0.02);
                        let monthlyPenalty =
                            parseFloat(grandTotalPar) *
                            parseFloat(0.02) *
                            (days / 30);
                        let newPenalty =
                            parseFloat(penalty) + parseFloat(monthlyPenalty);

                        this.partial_pay_form.parPenalty =
                            newPenalty.toFixed(2);
                    }
                });
        },

        saveChangesPartialPayment() {
            this.partial_pay_form
                .transform((data) => ({
                    ...data,
                    parRepDate: dayjs(data.parRepDate).format("YYYY-MM-DD"),
                }))
                .post(route("submit.partials"), {
                    onSuccess: () => {
                        this.openModalPPayment = false;
                        message.success({
                            content: "Successfully Save checks!",
                            duration: 3,
                        });
                    },
                });
        },
    },
};
</script>
