

<template>

    <Head title="Dashboard" />

        <div class="py-4">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mb-5">
                    <a-breadcrumb-item href="">
                        <HomeOutlined />
                    </a-breadcrumb-item>
                    <a-breadcrumb-item href="">
                        <user-outlined />
                        <span>Ds&Bounce Tagging</span>
                    </a-breadcrumb-item>
                    <a-breadcrumb-item>Ds Taggings</a-breadcrumb-item>
                    <a-breadcrumb-item></a-breadcrumb-item>
                </a-breadcrumb>
                <a-row :gutter="[16, 16]">
                    <a-col :span="4">
                        <p class="text-center mb-1 font-bold">check count</p>
                        <a-card style="width: 100%; height: 90px" class="mb-5">
                            <div class="flex justify-center items-center">
                                <a-badge count="0" style="
                                        display: flex;
                                        justify-content: center;
                                    ">
                                    <img src="../../../../public/icons/abacus.png" alt="" style="
                                                height: 40px;
                                                display: flex;
                                                justify-content: center;
                                            " />
                                    <!-- <a-avatar shape="square" size="large" src="../../../../public/icons/abacus.png" /> -->
                                </a-badge>
                                <p class="ml-10 font-bold">
                                    {{ total.count }}
                                </p>
                            </div>
                        </a-card>
                    </a-col>
                    <a-col :span="5">
                        <p class="text-center mb-1 font-bold">total amount</p>
                        <a-card style="width: 100%; height: 90px" class="mb-5">
                            <div class="flex justify-center items-center">
                                <a-badge count="0" style="
                                        display: flex;
                                        justify-content: center;
                                    ">
                                    <img src="../../../../public/icons/calculator.png" alt="" style="
                                                height: 40px;
                                                display: flex;
                                                justify-content: center;
                                            " />
                                </a-badge>
                                <p class="ml-10 font-bold">
                                    ₱
                                    {{ total.totalSum.toLocaleString() }}
                                </p>
                            </div>
                        </a-card>
                    </a-col>
                    <a-col :span="4">
                        <p class="text-center mb-1 font-bold">due dates</p>
                        <a-card style="width: 100%; height: 90px" class="mb-5">
                            <div class="flex justify-center items-center">

                                <a-badge count="0" style="
                                        display: flex;
                                        justify-content: center;
                                    ">
                                    <img src="../../../../public/icons/due-date(1).png" alt="" style="
                                                height: 40px;
                                                display: flex;
                                                justify-content: center;
                                            " />
                                </a-badge>
                                <p class="ml-10 font-bold">{{ due_dates }}</p>
                            </div>
                        </a-card>
                    </a-col>
                    <a-col :span="11">
                        <a-card style="width: 100%" class="mb-5 mt-5">
                            <a-row :gutter="[16, 16]" class="mt-2">
                                <a-col :span="8">
                                    <a-tooltip :color="colors" :open="isTooltipVisibleNo" title="Ds Number is required">
                                        <a-input placeholder="Ds Number" v-model:value="dsNo">

                                            <template #suffix>
                                                <a-tooltip title="Please Enter a Ds Number">
                                                    <info-circle-outlined style="
                                                            color: rgba(
                                                                0,
                                                                0,
                                                                0,
                                                                0.45
                                                            );
                                                        " />
                                                </a-tooltip>
                                            </template>
                                        </a-input>
                                    </a-tooltip>
                                </a-col>
                                <a-col :span="8">
                                    <a-tooltip :color="colors" :open="isTooltipVisibleDt"
                                        title="Return Date is required ">
                                        <a-date-picker v-model:value="dateDeposit" style="width: 100%" />
                                    </a-tooltip>
                                </a-col>
                                <a-col :span="8">

                                    <a-button :loading="isLoadingbutton" style="width: 100%;" type="primary"
                                        @click="submitToConButton()">
                                        <template #icon>
                                            <SaveOutlined />
                                        </template>
                                        submit ds number</a-button>
                                </a-col>
                            </a-row>
                        </a-card>
                    </a-col>
                </a-row>
                <a-table :data-source="ds_c_table.data" :pagination="false" :columns="columns" size="small"
                    :scroll="{ x: 100, y: 470 }" class="components-table-demo-nested" bordered :row-class-name="(_record, index) =>
                        _record.type === 'POST-DATED'
                            ? 'POST-DATED'
                            : 'DATED'
                        ">

                    <template #bodyCell="{ column, record, index }">
                        <template v-if="column.key === 'select'">
                            <a-switch v-model:checked="record.done" @change="handleSwitchChange(record)" size="small">
                                <template #checkedChildren><check-outlined /></template>

                                <template #unCheckedChildren><close-outlined /></template>
                            </a-switch>
                        </template>
                    </template>
                </a-table>
                <pagination class="mt-6" :datarecords="ds_c_table" />
            </div>
        </div>
</template>

<script>
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import { Head } from "@inertiajs/vue3";
import { message } from "ant-design-vue";
import dayjs from "dayjs";
import Pagination from "@/Components/Pagination.vue"
import axios from "axios";

export default {
    layout: TreasuryLayout,
    data() {
        return {
            switchValues: [],
            countDs: 0,
            totalAmount: 0,
            dataAmount: [],
            defaultTotal: this.total,
            dateDeposit: null,
            dsNo: "",
            isTooltipVisibleDt: false,
            isTooltipVisibleNo: false,
            isLoadingbutton: false,
        };
    },
    props: {
        pagination: Object,
        columns: Array,
        ds_c_table: Object,
        type: Object,
        total: Object,
        due_dates: Number,
    },
    computed: {},
    methods: {
        switchRecord() {
            window.alert('click');
            this.switchValues = this.ds_c_table.data.map((value) =>
                value.done === "" ? false : true
            );
        },

        handleTableChange(page) {
            try {
                this.$inertia.get(route().current(), {
                    page: page,
                });
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        },

        async submitToConButton() {
            this.isLoadingbutton = true;
            const selected = this.ds_c_table.data.filter((value) => value.done);

            if (
                !this.ds_c_table.data.some((value) => value.done === true) &&
                !this.dsNo &&
                this.dateDeposit == null
            ) {
                this.isLoadingbutton = false;
                this.isTooltipVisibleDt = true;
                this.isTooltipVisibleNo = true;
                message.error({
                    content: "Oppss Select Checks First!",
                    duration: 5,
                });
            } else if (
                !this.ds_c_table.data.some((value) => value.done === true) &&
                this.dsNo &&
                this.dateDeposit == null
            ) {
                this.isLoadingbutton = false;
                this.isTooltipVisibleDt = true;
                this.isTooltipVisibleNo = false;
                message.error({
                    content: "Oppss Select Checks First!",
                    duration: 5,
                });
            } else if (
                !this.ds_c_table.data.some((value) => value.done === true) &&
                !this.dsNo &&
                this.dateDeposit != null
            ) {
                this.isLoadingbutton = false;
                this.isTooltipVisibleDt = false;
                this.isTooltipVisibleNo = true;
                message.error({
                    content: "Oppss Select Checks First!",
                    duration: 5,
                });
            } else if (
                this.ds_c_table.data.some((value) => value.done === true) &&
                !this.dsNo &&
                this.dateDeposit == null
            ) {
                this.isLoadingbutton = false;
                this.isTooltipVisibleDt = true;
                this.isTooltipVisibleNo = true;
            } else if (
                this.ds_c_table.data.some((value) => value.done === true) &&
                this.dsNo &&
                this.dateDeposit == null
            ) {
                this.isTooltipVisibleDt = true;
                this.isTooltipVisibleNo = false;
                this.isLoadingbutton = false;
            } else if (
                !this.ds_c_table.data.some((value) => value.done === true) &&
                this.dsNo &&
                this.dateDeposit != null
            ) {
                this.isTooltipVisibleDt = false;
                this.isTooltipVisibleNo = false;
                this.isLoadingbutton = false;
                message.error({
                    content: "Oppss Select Checks First!",
                    duration: 5,
                });
            } else if (
                this.ds_c_table.data.some((value) => value.done === true) &&
                this.dsNo &&
                this.dateDeposit !== null
            ) {
                this.isTooltipVisibleDt = false;
                this.isTooltipVisibleNo = false;

                this.$inertia.post(
                    route("submit.ds.tagging"),
                    {
                        selected,
                        dsNo: this.dsNo,
                        dateDeposit: dayjs(this.dateDeposit).format(
                            "YYYY-MM-DD"
                        ),
                    },
                    {
                        onFinish: () => {
                            this.isLoadingbutton = false;
                            message.success({
                                content: "Successfully submitted",
                                duration: 5,
                            });
                            this.dsNo = "";
                            this.dateDeposit = null;
                        },
                    }
                );
            }
        },

        async handleSwitchChange(data) {
            const key = "updatable";

            this.$inertia.put(route("update.switch"), {
                id: data.checks_id,
                isCheck: data.done,
                checkAmount: data.check_amount,
                oldAmount: this.total.totalSum,
                oldCount: this.defaultTotal.count,
            }, {
                onSuccess: () => {
                    if (data.done) {
                        setTimeout(() => {
                            message.success({
                                content: "Checked Successfully!",
                                key,
                                duration: 2,
                            });
                        }, 100);
                    } else {
                        setTimeout(() => {
                            message.warning({
                                content: "Uncheck Successfully!",
                                key,
                                duration: 2,
                            });
                        }, 100);
                    }
                }
            });
        },
    },
    created() {
        this.switchValues = this.ds_c_table.data.map((value) =>
            value.done === "" ? false : true
        );
    },
};
</script>

<style>
.DATED {
    background-color: rgba(94, 169, 255, 0.274);
    /* Set background color for DATED type */
}

.POST-DATED {
    background-color: rgba(255, 121, 121, 0.274);
    /* Set background color for POST-DATED type */
}
</style>
