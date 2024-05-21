<template>

    <Head title="Dashboard" />
    <div class="py-5">
        <div class="max-full mx-auto sm:px-6 lg:px-8">
            <div class="text-xl mb-5 text-center" style="display: flex; justify-content: space-between">
                <a-breadcrumb>
                    <a-breadcrumb-item href="">
                        <HomeOutlined />
                    </a-breadcrumb-item>
                    <a-breadcrumb-item href="">
                        <user-outlined />
                        <span>Ds&Bounce Tagging</span>
                    </a-breadcrumb-item>
                    <a-breadcrumb-item>Bounce Tagging</a-breadcrumb-item>
                    <a-breadcrumb-item>Deposited Checks</a-breadcrumb-item>
                </a-breadcrumb>
            </div>

            <div style="display: flex; justify-content: space-between" class="mb-3">
                <a-date-picker @change="onDateChange" v-model:value="dtYear" picker="year" style="width: 250px" />
                <a-input-search v-model:value="query.search" class="mx-2" placeholder="Input Check Number"
                    style="width: 350px" />
            </div>
            <a-card>
                <a-table :dataSource="data.data" :columns="columns" :pagination="false" :loading="loading"
                    class="components-table-demo-nested" bordered size="small">
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'action'">
                            <a-button type="primary" class="mx-1" size="small" ref="ref4" v-on:click="
                                confirmBounceTagg(record.checks_id)
                                ">
                                <template #icon>
                                    <TagsOutlined />
                                </template>
                            </a-button>

                            <a-button size="small" v-on:click="openModalDetails(record)">
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
        <a-modal v-model:open="openDetails" style="top: 25px" width="1000px" title="Details" :footer="null">
            <div class="product-table">
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-t border-gray-200">
                                Customer Name
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-t border-gray-200">
                                {{ selectDataDetails.fullname }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check From
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.department }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Number
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_no }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Approving Officer
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.approving_officer }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Class
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_class }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Status
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_status }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Date
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_date }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Account No
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.account_no }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Received
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_received }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Account Name
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.account_name }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Received As
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_type }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Bank Name
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.bankbranchname }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Category
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_category }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Amount
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_amount }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </a-modal>

        <a-modal class="mb-5" v-model:open="tagAsBounced" title="Tag as Bounce" :footer="null">
            <div class="mb-4">
                <a-tooltip :color="colors" :open="showAtootltip" title="Return Date is required">
                    <a-date-picker @change="onDateChangeReturn" v-model:value="returnDate"
                        style="width: 63%; margin-right: 5px" class="mt-3" /></a-tooltip>
                <a-button :loading="isLoadingbutton" style="width: 35%; background: #b5fcb2d0"
                    v-on:click="continueToTagg">Continue tagging?</a-button>
            </div>
        </a-modal>
    </div>
</template>

<script>
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import dayjs from "dayjs";
import { message } from "ant-design-vue";
import debounce from "lodash/debounce";
export default {
    layout: TreasuryLayout,
    props: {
        data: Object,
        columns: Array,
        sel_year: String,
    },
    data() {
        return {
            query: {
                search: '',
            },
            colors: 'red',
            dataSource: [],
            isSearchLoading: false,
            loading: false,
            dtYear: dayjs(this.sel_year),
            c_page: "",
            openDetails: false,
            selectDataDetails: [],
            tagAsBounced: false,
            returnDate: dayjs(),
            checksId: null,
            isLoadingbutton: false,
            showAtootltip: false,
            page: 1,
            query: {
                search: "",
            },
        };
    },
    watch: {
        query: {
            deep: true,
            handler: debounce(async function () {
                try {
                    this.loading = true;
                    this.$inertia.get(
                        route("bounce.tagging"),
                        {
                            // page: this.page,
                            dt_year: this.dtYear.format("YYYY"),
                            search: this.query.search,
                        },
                        { preserveState: true }
                    );
                } catch (error) {
                    console.log(error);
                } finally {
                    this.loading = false;
                }
            }, 600),
        },
    },
    methods: {
        onDateChange(dateObj, dateStr) {
            this.$inertia.get(route("bounce.tagging"), {
                dt_year: dateStr,
            });
        },
        onDateChangeReturn(dateObj, dateStr) {
            this.returnDate = dateObj;
        },

        handleTableChange(pagination) {
            this.getBounceTagging(pagination);
        },

        openModalDetails(data) {
            this.openDetails = true;
            this.selectDataDetails = data;

            console.log(this.selectDataDetails);
        },
        confirmBounceTagg(checks_id) {
            this.checksId = checks_id;
            this.tagAsBounced = true;
        },
        continueToTagg() {
            this.isLoadingbutton = true;
            if (!this.returnDate) {
                setTimeout(() => {
                    this.isLoadingbutton = false;
                    this.showAtootltip = true;
                }, 700);
            } else {
                this.$inertia.post(
                    route("tag_check_bounce"),
                    {
                        date: this.returnDate.format("YYYY-MM-DD"),
                        check_id: this.checksId,
                    },
                    {
                        onFinish: () => {
                            setTimeout(() => {
                                this.tagAsBounced = false;
                                this.isLoadingbutton = false;
                            }, 700);
                        },
                    }
                );
            }
        },
    },
};
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
}

.product-table td {
    border: 1px solid #ddd;
}
</style>
