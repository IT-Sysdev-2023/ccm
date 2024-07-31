<template>

    <Head title="Ds Tagging" />

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
                    <Statistics v-if="tab == '2'" :title="'Check Count'" :count="total.count" />
                </a-col>
                <a-col :span="5">
                    <Statistics v-if="tab == '2'" :title="'Total.'" :count="total.totalSum" />
                </a-col>
                <a-col :span="3">
                    <Statistics v-if="tab == '2'" :title="'Due Dates.'" :count="due_dates" />
                </a-col>
                <a-col :span="12">
                    <a-row :gutter="[16, 16]">
                        <a-col :span="8">
                            <a-tooltip :open="isTooltipVisibleNo" title="Ds Number is required">
                                <a-input placeholder="Ds Number" v-model:value="dsNo">

                                    <template #suffix>
                                        <a-tooltip title="Please Enter a Ds Number">
                                            <info-circle-outlined style="color: rgba(0, 0, 0, 0.45);" />
                                        </a-tooltip>
                                    </template>
                                </a-input>
                            </a-tooltip>
                        </a-col>
                        <a-col :span="8">
                            <a-tooltip :open="isTooltipVisibleDt" title="Return Date is required ">
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

                </a-col>
            </a-row>
            <a-tabs v-model:activeKey="activeKey" type="card" @change="changeTab">
                <a-tab-pane key="1">
                    <template #tab>
                        <span>
                            <apple-outlined />
                            To be Check Checks
                        </span>
                    </template>

                    <NotDone :filters="filters" :records="data" :columns="columns" :total="total"
                        :def-total="defaultTotal" />

                </a-tab-pane>
                <a-tab-pane key="2">
                    <template #tab>
                        <a-badge :count="total.count" :number-style="{ backgroundColor: '#52c41a' }">
                            <span>
                                <android-outlined />
                                Checked Total
                            </span>
                        </a-badge>
                    </template>
                    <DoneTable :filters="filters" :records="data" :columns="columns" :total="total"
                        :def-total="defaultTotal" />
                </a-tab-pane>
            </a-tabs>

        </div>
    </div>
</template>

<script>
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import { Head } from "@inertiajs/vue3";
import { message } from "ant-design-vue";
import dayjs from "dayjs";
import DoneTable from "./Tables/DoneTable.vue";
export default {
    layout: TreasuryLayout,

    data() {
        return {
            countDs: 0,
            totalAmount: 0,
            dataAmount: [],
            defaultTotal: this.total,
            dateDeposit: null,
            dsNo: "",
            isTooltipVisibleDt: false,
            isTooltipVisibleNo: false,
            isLoadingbutton: false,
            isFetching: false,
            activeKey: this.tab,
        };
    },
    props: {
        columns: Array,
        data: Object,
        type: Object,
        total: Object,
        due_dates: Number,
        filters: Object,
        tab: String,
    },
    computed: {},
    methods: {
        changeTab(tab) {
            this.$inertia.get(route('ds_tagging'), {
                tab
            })
        },
        async submitToConButton() {
            this.isLoadingbutton = true;
            const selected = this.data.data.filter((value) => value.done);

            if (
                !this.data.data.some((value) => value.done === true) &&
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
                !this.data.data.some((value) => value.done === true) &&
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
                !this.data.data.some((value) => value.done === true) &&
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
                this.data.data.some((value) => value.done === true) &&
                !this.dsNo &&
                this.dateDeposit == null
            ) {
                this.isLoadingbutton = false;
                this.isTooltipVisibleDt = true;
                this.isTooltipVisibleNo = true;
            } else if (
                this.data.data.some((value) => value.done === true) &&
                this.dsNo &&
                this.dateDeposit == null
            ) {
                this.isTooltipVisibleDt = true;
                this.isTooltipVisibleNo = false;
                this.isLoadingbutton = false;
            } else if (
                !this.data.data.some((value) => value.done === true) &&
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
                this.data.data.some((value) => value.done === true) &&
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


    },

};
</script>
