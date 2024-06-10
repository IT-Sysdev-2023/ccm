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
                        <div>
                            <a-input-search v-model:value="form.search" block class="mb-5" placeholder="Search Checks"
                                :loading="isFetching" />
                        </div>
                        <a-row :gutter="[16, 16]" class="mt-2">
                            <a-col :span="8">
                                <a-tooltip :open="isTooltipVisibleNo" title="Ds Number is required">
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
                                <a-tooltip  :open="isTooltipVisibleDt" title="Return Date is required ">
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
            <a-table :data-source="data.data" :pagination="false" :columns="columns" size="small"
                :scroll="{ x: 100, y: 470 }" class="components-table-demo-nested" bordered :row-class-name="(_record, index) =>
                    _record.type === 'POST-DATED'
                        ? 'POST-DATED'
                        : 'DATED'
                    ">

                <template #bodyCell="{ column, record }">
                    <!-- <p style="color: red;">{{ column.dataIndex }}</p> -->
                    <template v-if="column.dataIndex">
                        <span v-html="highlightText(record[column.dataIndex], form.search)
                            ">
                        </span>
                    </template>
                    <template v-if="column.key === 'select'">
                        <a-switch v-model:checked="record.done" @change="handleSwitchChange(record)" size="small">
                            <template #checkedChildren><check-outlined /></template>

                            <template #unCheckedChildren><close-outlined /></template>
                        </a-switch>
                    </template>
                </template>
            </a-table>
            <pagination class="mt-6" :datarecords="data" />
        </div>
    </div>
</template>

<script>
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import { Head } from "@inertiajs/vue3";
import { message } from "ant-design-vue";
import dayjs from "dayjs";
import Pagination from "@/Components/Pagination.vue"
import debounce from "lodash/debounce";
import { highlighten } from "@/Mixin/highlighten.js";

export default {
    layout: TreasuryLayout,
    setup() {
        const { highlightText } = highlighten();
        return { highlightText };
    },
    data() {
        return {
            form: {
                search: this.filters.search,
            },
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
        };
    },
    props: {
        pagination: Object,
        columns: Array,
        data: Object,
        type: Object,
        total: Object,
        due_dates: Number,
        filters: Object,
    },
    computed: {},
    methods: {
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
                    data.done ? message.success('Check Successfully') : message.info('UnCheck Successfully');
                },
                preserveState: true,
                preserveScroll: true,
            });
        },
    },
    // created() {
    //     this.switchValues = this.data.data.map((value) =>
    //         value.done === "" ? false : true
    //     );
    //     console.log(this.switchValues)
    // },
    watch: {
        form: {
            deep: true,
            handler: debounce(async function () {
                this.isFetching = true,
                    this.$inertia.get(route("ds_tagging"), {
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
            }, 500),
        },
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
