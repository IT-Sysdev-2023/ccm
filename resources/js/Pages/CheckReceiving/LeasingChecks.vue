<template>

    <Head title="Leasing Checks" />


    <div class="py-2">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <a-breadcrumb class="mt-1 mb-3">
                <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                <a-breadcrumb-item><a href="">Check Receiving </a></a-breadcrumb-item>
                <a-breadcrumb-item>Leasing Checks</a-breadcrumb-item>
            </a-breadcrumb>
            <a-card>
                <div class="flex justify-between">
                    <div>
                        <a-date-picker v-model:value="form.generateDate" class="mb-3 text-center"
                            style="width: 150px" />
                        <a-select ref="select" class="mx-2 text-center" v-model:value="form.checkStatus"
                            placeholder="Legend Here" style="width: 170px">
                            <a-select-option style="
                                        background-color: rgba(
                                            129,
                                            252,
                                            48,
                                            0.185
                                        );
                                        color: black;
                                    " class="text-center" value="ALL">ALL</a-select-option>
                            <a-select-option style="
                                        background-color: rgba(
                                            153,
                                            171,
                                            189,
                                            0.185
                                        );
                                        color: black;
                                    " class="text-center mt-1" value="PENDING">Pending</a-select-option>
                            <a-select-option style="
                                        background-color: rgba(
                                            121,
                                            224,
                                            255,
                                            0.274
                                        );
                                        color: black;
                                    " class="text-center mt-1" value="CLEARED">Confirmed</a-select-option>
                            <a-select-option style="
                                        background-color: rgba(
                                            255,
                                            121,
                                            121,
                                            0.274
                                        );
                                        color: black;
                                    " class="text-center mt-1" value="BOUNCE">Bounce</a-select-option>
                            <a-select-option style="
                                        background-color: rgba(
                                            123,
                                            85,
                                            212,
                                            0.301
                                        );
                                        color: black;
                                    " class="text-center mt-1" value="CASH">Replace Cash</a-select-option>
                        </a-select>
                    </div>
                    <div>
                        <a-input-search v-model:value="form.search" class="mx-2" placeholder="Input Check Number"
                            style="width: 350px" />
                        <a-button type="primary" style="width: 300px" @click="savedLeasingChecks" :disabled="dataFn.find(
                            (item) =>
                                item.is_exist === true &&
                                item.check_status === 'PENDING'
                        )
                            ? false
                            : true
                            ">
                            <template #icon>
                                <SaveOutlined />
                            </template>
                            {{ isLoading ? 'Saving Leasing Checks...' : 'Save Leasing Checks' }}
                        </a-button>
                    </div>
                </div>
                <a-table :loading="isloadingTbl" :dataSource="data.data" :pagination="false" bordered size="small"
                    :columns="columns" :row-class-name="(_record, index) =>
                        _record.check_status == 'PENDING'
                            ? 'PENDING'
                            : _record.check_status == 'CASH'
                                ? 'CASH'
                                : _record.check_status === 'BOUNCE'
                                    ? 'BOUNCE'
                                    : 'CLEARED'
                        ">
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex">
                            <span v-html="highlightText(record[column.dataIndex], form.search)
                                ">
                            </span>
                        </template>
                        <template v-if="column.key === 'check_box'">
                            <template v-if="record.check_status === 'PENDING'">
                                <a-switch v-model:checked="record.is_exist" @change="handleSwitchChange(record)"
                                    size="small">
                                    <template #checkedChildren><check-outlined /></template>
                                    <template #unCheckedChildren><close-outlined /></template>
                                </a-switch>
                            </template>
                        </template>
                        <template v-if="column.key === 'details'">
                            <a-button size="small" @click="datedDetails(record)">
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

    <CheckModalDetail v-model:open="showModalDetails" :datarecords="selectDataDetails"></CheckModalDetail>


</template>
<script>
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import { Head } from "@inertiajs/vue3";
import debounce from "lodash/debounce";
import Pagination from "@/Components/Pagination.vue";
import dayjs from "dayjs";
import { message } from "ant-design-vue";
import { highlighten } from "@/Mixin/highlighten.js";

export default {
    layout: TreasuryLayout,
    setup() {
        const { highlightText } = highlighten();
        return { highlightText };
    },
    props: {
        data: Object,
        columns: Array,
        date: String,
        value: Object,
        pagination: Object,
        dataFn: Object,
        filters: Object
    },
    data() {
        return {
            isloadingTbl: false,
            showModalDetails: false,
            selectDataDetails: {},
            isLoading: false,
            form: {
                search: this.filters.search,
                generateDate: dayjs(this.filters.date),
                checkStatus: this.filters.status,
            },
        };
    },
    methods: {

        datedDetails(inData) {
            this.showModalDetails = true;
            this.selectDataDetails = inData;
        },
        handleSwitchChange(dataRecord) {
            this.$inertia.get(
                route("checkUncheck.checks"),
                {
                    is_exist: dataRecord.is_exist,
                    checksId: dataRecord.checks_id,
                },
                {
                    onSuccess: (e) => {
                        const messageLabel = e.props.flash?.success;
                        if (e.props.flash?.style === "red") {
                            message.error(messageLabel);
                        } else {
                            message.success(messageLabel);
                        }
                    },
                }
            );
        },
        savedLeasingChecks() {
            const selected = this.dataFn.filter((value) => value.is_exist);
            this.$inertia.post(
                route("datedleaspdc.checks"),
                {
                    selected,
                },
                {
                    onSuccess: (e) => {
                        const messageLabel = e.props.flash?.success;
                        message.success(messageLabel);
                    },
                }
            );
        },
    },
    watch: {
        form: {
            deep: true,
            handler: debounce(async function () {
                this.$inertia.get(route("leasing.checks"), {
                    search: this.form.search,
                    date: dayjs(this.form.generateDate).format('YYYY-MM-DD') ?? dayjs(),
                    status: this.form.checkStatus
                },
                    { preserveState: true }
                );

            }, 400),
        },
    },
};
</script>

<style>
.PENDING {
    background-color: rgba(153, 171, 189, 0.185);
    /* Set background color for DATED type */
}

.BOUNCE {
    background-color: rgba(255, 121, 121, 0.274);
    /* Set background color for POST-DATED type */
}

.CLEARED {
    background-color: rgba(121, 224, 255, 0.274);
    /* Set background color for POST-DATED type */
}

.CASH {
    background-color: rgba(206, 121, 255, 0.274);
}
</style>
