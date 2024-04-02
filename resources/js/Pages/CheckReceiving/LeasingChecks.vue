<script setup>
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                This is treasury Dashboard
            </h2>
        </template>

        <div class="py-2">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mt-1 mb-3">
                    <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                    <a-breadcrumb-item
                        ><a href="">Check Receiving </a></a-breadcrumb-item
                    >
                    <a-breadcrumb-item>Leasing Checks</a-breadcrumb-item>
                </a-breadcrumb>
                <a-card>
                    <div class="flex justify-between">
                        <div>
                            <a-date-picker
                                v-model:value="generateDate"
                                class="mb-3 text-center"
                                style="width: 150px"
                                @change="handleGenerateTable"
                            />
                            <a-select
                                ref="select"
                                class="mx-2 text-center"
                                v-model:value="checkStatus"
                                placeholder="Legend Here"
                                style="width: 170px"
                                @change="handleChangeStatus"
                            >
                                <a-select-option
                                    style="
                                        background-color: rgba(
                                            129,
                                            252,
                                            48,
                                            0.185
                                        );
                                        color: black;
                                    "
                                    class="text-center"
                                    value="ALL"
                                    >ALL</a-select-option
                                >
                                <a-select-option
                                    style="
                                        background-color: rgba(
                                            153,
                                            171,
                                            189,
                                            0.185
                                        );
                                        color: black;
                                    "
                                    class="text-center mt-1"
                                    value="PENDING"
                                    >Pending</a-select-option
                                >
                                <a-select-option
                                    style="
                                        background-color: rgba(
                                            121,
                                            224,
                                            255,
                                            0.274
                                        );
                                        color: black;
                                    "
                                    class="text-center mt-1"
                                    value="CLEARED"
                                    >Confirmed</a-select-option
                                >
                                <a-select-option
                                    style="
                                        background-color: rgba(
                                            255,
                                            121,
                                            121,
                                            0.274
                                        );
                                        color: black;
                                    "
                                    class="text-center mt-1"
                                    value="BOUNCE"
                                    >Bounce</a-select-option
                                >
                                <a-select-option
                                    style="
                                        background-color: rgba(
                                            123,
                                            85,
                                            212,
                                            0.301
                                        );
                                        color: black;
                                    "
                                    class="text-center mt-1"
                                    value="CASH"
                                    >Replace Cash</a-select-option
                                >
                            </a-select>
                        </div>
                        <div>
                            <a-input-search
                                v-model:value="query.search"
                                class="mx-2"
                                placeholder="Input Check Number"
                                style="width: 350px"
                            />
                            <a-button
                                type="primary"
                                style="width: 300px"
                                @click="savedLeasingChecks"
                                :disabled="
                                    dataFn.find(
                                        (item) =>
                                            item.is_exist === true &&
                                            item.check_status === 'PENDING'
                                    )
                                        ? false
                                        : true
                                "
                            >
                                <template #icon>
                                    <SaveOutlined />
                                </template>
                                save leasing checks
                            </a-button>
                        </div>
                    </div>
                    <a-table
                        :loading="isloadingTbl"
                        :dataSource="data.data"
                        :pagination="false"
                        bordered
                        size="small"
                        :columns="columns"
                        :row-class-name="
                            (_record, index) =>
                                _record.check_status == 'PENDING'
                                    ? 'PENDING'
                                    : _record.check_status == 'CASH'
                                    ? 'CASH'
                                    : _record.check_status === 'BOUNCE'
                                    ? 'BOUNCE'
                                    : 'CLEARED'
                        "
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'check_box'">
                                <template
                                    v-if="record.check_status === 'PENDING'"
                                >
                                    <a-switch
                                        v-model:checked="record.is_exist"
                                        @change="handleSwitchChange(record)"
                                        size="small"
                                    >
                                        <template #checkedChildren
                                            ><check-outlined
                                        /></template>
                                        <template #unCheckedChildren
                                            ><close-outlined
                                        /></template>
                                    </a-switch>
                                </template>
                            </template>
                            <template v-if="column.key === 'details'">
                                <a-button
                                    size="small"
                                    @click="datedDetails(record)"
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
            v-model:open="showModalDetails"
            style="top: 25px"
            width="1000px"
            title="Details"
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
import debounce from "lodash/debounce";
import Pagination from "@/Components/Pagination.vue";
import dayjs from "dayjs";
import { message } from "ant-design-vue";

export default {
    props: {
        data: Object,
        columns: Array,
        date: String,
        value: Object,
        pagination: Object,
        dataFn: Object,
    },
    data() {
        return {
            generateDate: dayjs(this.date),
            checkStatus: this.value,
            isloadingTbl: false,
            showModalDetails: false,
            selectDataDetails: {},
            query: {
                search: "",
            },
        };
    },
    methods: {
        handleGenerateTable(obj, str) {
            this.$inertia.get(route("leasing.checks"), {
                generate_date: str,
            });
        },
        handlePaginate(page = 1) {
            this.isloadingTbl = true;
            this.$inertia.get(
                route("leasing.checks"),
                {
                    page: page,
                    generate_date: this.generateDate.format("YYYY-MM-DD"),
                    check_status: this.checkStatus,
                },
                {
                    preserveScroll: true,
                }
            );
        },
        handleChangeStatus(page = 1) {
            this.isloadingTbl = true;
            this.$inertia.get(route("leasing.checks"), {
                page: page,
                generate_date: this.generateDate.format("YYYY-MM-DD"),
                check_status: this.checkStatus,
            });
        },
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
        query: {
            deep: true,
            handler: debounce(async function () {
                try {
                    this.isloadingTbl = true;
                    this.$inertia.get(
                        route("leasing.checks"),
                        {
                            page: this.page,
                            generate_date:
                                this.generateDate.format("YYYY-MM-DD"),
                            check_status: this.checkStatus,
                            searchQuery: this.query.search,
                        },
                        { preserveState: true }
                    );
                } catch (error) {
                } finally {
                    this.isloadingTbl = false;
                }
            }, 600),
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
