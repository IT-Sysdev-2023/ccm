<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
import { h } from 'vue';

import axios from 'axios';

const indicator = h(LoadingOutlined, {
    style: {
        fontSize: '18px',
    },
    spin: true,
});
const colors = 'red';
</script>

<template>

    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
        </template>

        <div class="py-5">

            <div class="max-full mx-auto sm:px-6 lg:px-8">
                <div class="text-xl mb-5 text-center" style="display: flex; justify-content: space-between;">
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
                <div style="display: flex; justify-content: space-between;" class="mb-3">

                    <a-date-picker @change="onDateChange" v-model:value="dtYear" picker="year" style=" width: 250px;" />
                    <a-input placeholder="Search Cheques" style="width: 250px; " v-model:value="query.search">

                        <template #suffix>
                            <a-tooltip title="Please input name or check number to filter ">
                                <a-spin size="small" v-if="isSearchLoading" :indicator="indicator" />
                            </a-tooltip>
                        </template>
                    </a-input>

                </div>

                <a-card>
                    <a-table :dataSource="dataSource" :columns="columns" :pagination="false" :loading="loading"
                        class="components-table-demo-nested" bordered size="small">

                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'action'">
                                <a-button class="mx-1" shape="square" ref="ref4"
                                    style="background-color: rgba(115, 236, 91, 0.685);"
                                    v-on:click="confirmBounceTagg(record.checks_id)">

                                    <template #icon>
                                        <TagsOutlined />
                                    </template>
                                </a-button>

                                <a-button shape="square" v-on:click="openModalDetails(record)">

                                    <template #icon>
                                        <SettingOutlined />
                                    </template>
                                </a-button>
                            </template>

                        </template>

                    </a-table>
                    <div v-if="showPag" class="mt-3 mb-5"
                        style="border: 1px solid rgb(224, 224, 224); border-radius: 10px; padding: 10px">
                        <div class="flex justify-end">
                            <a-pagination class="mt-0 mb-0" v-model:current="pagination.current"
                                v-model:page-size="pagination.pageSize" :show-size-changer="false"
                                :total="pagination.total"
                                :show-total="(total, range) => `${range[0]}-${range[1]} of ${total} reports`"
                                @change="handleTableChange" />
                        </div>
                    </div>
                </a-card>
            </div>
            <a-modal v-model:open="openDetails" style="top: 25px" width="1000px" title="Details" @ok="handleOk"
                :ok-button-props="{ hidden: true }" :cancel-button-props="{ hidden: true }" :footer="null">
                <div class="product-table">
                    <table class="min-w-full divide-y divide-gray-200">

                        <tbody>
                            <tr>
                                <td
                                    class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l  border-t border-gray-200">
                                    Customer Name</td>
                                <td
                                    class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-t border-gray-200">
                                    {{
                        selectDataDetails.fullname }}</td>
                            </tr>
                            <tr>
                                <td
                                    class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                    Check From</td>
                                <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                        selectDataDetails.department }}</td>
                            </tr>
                            <tr>
                                <td
                                    class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                    Check Number</td>
                                <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                        selectDataDetails.check_no }}</td>
                            </tr>
                            <tr>
                                <td
                                    class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                    Approving Officer</td>
                                <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                        selectDataDetails.approving_officer }}</td>
                            </tr>
                            <tr>
                                <td
                                    class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                    Check Class</td>
                                <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                        selectDataDetails.check_class }}</td>
                            </tr>
                            <tr>
                                <td
                                    class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                    Check Status</td>
                                <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                        selectDataDetails.check_status }}</td>
                            </tr>
                            <tr>
                                <td
                                    class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                    Check Date</td>
                                <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                        selectDataDetails.check_date }}</td>
                            </tr>
                            <tr>
                                <td
                                    class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                    Account No</td>
                                <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                        selectDataDetails.account_no }}</td>
                            </tr>
                            <tr>
                                <td
                                    class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                    Check Received</td>
                                <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                        selectDataDetails.check_received }}</td>
                            </tr>
                            <tr>
                                <td
                                    class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                    Account Name</td>
                                <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                        selectDataDetails.account_name }}</td>
                            </tr>
                            <tr>
                                <td
                                    class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                    Received As</td>
                                <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                        selectDataDetails.check_type }}</td>
                            </tr>
                            <tr>
                                <td
                                    class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                    Bank Name</td>
                                <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                        selectDataDetails.bankbranchname }}</td>
                            </tr>
                            <tr>
                                <td
                                    class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                    Check Category</td>
                                <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                        selectDataDetails.check_category }}</td>
                            </tr>
                            <tr>
                                <td
                                    class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                    Amount</td>
                                <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                        selectDataDetails.check_amount }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </a-modal>

            <a-modal class="mb-5" v-model:open="tagAsBounced" title="Tag as Bounce" :ok-button-props="false"
                :cancel-button-props="{ hidden: true }" :footer="null">
                <div class="mb-4">
                    <a-tooltip :color="colors" :open="showAtootltip" title="Return Date is required"> <a-date-picker
                            @change="onDateChangeReturn" v-model:value="returnDate"
                            style="width: 63%; margin-right: 5px;" class="mt-3" /></a-tooltip>
                    <a-button :loading="isLoadingbutton" style="width: 35%; background: #b5fcb2d0;"
                        v-on:click="continueToTagg">Continue
                        tagging?</a-button>
                </div>
            </a-modal>
        </div>
    </TreasuryLayout>
</template>

<script>
import { LoadingOutlined } from "@ant-design/icons-vue";
import dayjs from 'dayjs';
import { message } from 'ant-design-vue';
import debounce from 'lodash/debounce'
export default {
    data() {
        return {
            dataSource: [],
            isSearchLoading: false,
            loading: false,
            dtYear: dayjs(),
            showPag: false,
            c_page: '',
            openDetails: false,
            selectDataDetails: [],
            tagAsBounced: false,
            returnDate: dayjs(),
            checksId: null,
            isLoadingbutton: false,
            showAtootltip: false,
            query: {
                search: '',
            },
            pagination: {
                current: 1,
                total: 0,
                pageSize: 10,

            },
            columns: [
                {
                    title: 'Check receive',
                    dataIndex: 'check_received',
                    key: 'checks_r',
                    width: '12%'
                },
                {
                    title: 'Check date',
                    dataIndex: 'check_date',
                    key: 'check_dep',
                    width: '12%'
                },
                {
                    title: 'Date Deposited',
                    dataIndex: 'date_deposit',
                    key: 'check_d',
                    width: '12%'
                },
                {
                    title: 'Customer name',
                    dataIndex: 'fullname',
                    key: 'fullname',
                    width: '30%'
                },
                {
                    title: 'Check No',
                    dataIndex: 'check_no',
                    key: 'address',
                },
                {
                    title: 'Amount',
                    dataIndex: 'check_amount',
                    key: 'check_am',
                },
                {
                    title: 'Ds No',
                    dataIndex: 'ds_no',
                    key: 'address',
                    width: '7%'
                },
                {
                    title: 'Actions',
                    dataIndex: 'action',
                    key: 'action',
                    align: 'center',
                    width: '12%'
                },
            ],
        };
    },
    watch: {
        query: {
            deep: true,
            handler: debounce(async function (page = 1) {
                this.loading = true;
                this.isSearchLoading = true;
                try {
                    const response = await axios.get(`get_bounce_tagging?page=${page}`, {
                        params: {
                            dt_year: this.dtYear.year(),
                            search: this.query.search,
                        },
                    });

                    if (!response.data.data) {
                        this.showPag = false;
                    } else {
                        this.showPag = true;
                    }


                    this.dataSource = response.data.data;
                    this.pagination = response.data.pagination;
                } catch (error) {
                    console.error("Error in watcher:", error);
                } finally {
                    this.loading = false;
                    this.isSearchLoading = false;
                }
            }, 500)
        },
    },
    methods: {
        onDateChange(dateObj, dateStr) {
            this.dtYear = dateObj;

            this.getBounceTagging()
        },
        onDateChangeReturn(dateObj, dateStr) {
            this.returnDate = dateObj;
        },
        async getBounceTagging(page = 1) {

            const key = 'updatable';

            this.loading = true;
            message.loading({
                content: 'Fetching data please wait...',
                key,
            });
            try {
                const response = await axios.get(`get_bounce_tagging?page=${page}`, {
                    params: {
                        dt_year: this.dtYear.year(),
                    },
                });

                if (!response.data.data) {
                    this.showPag = false;
                } else {
                    this.showPag = true;
                }

                this.dataSource = response.data.data;
                this.pagination = response.data.pagination;

                // console.log(response.data.pagination)
            } catch (error) {
                // console.log(error);
            } finally {
                this.loading = false;
                message.success({
                    content: 'Retrieve Successfully!',
                    key,
                    duration: 2,
                });
            }
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
            console.log(this.checksId);
            this.tagAsBounced = true;
        },
        continueToTagg() {
            this.isLoadingbutton = true;
            if (!this.returnDate) {
                setTimeout(() => {
                    this.isLoadingbutton = false;
                    this.showAtootltip = true
                }, 700);
            } else {
                this.$inertia.post(route('tag_check_bounce'), {
                    date: this.returnDate.format('YYYY-MM-DD'),
                    check_id: this.checksId
                }, {
                    onFinish: () => {
                        setTimeout(() => {
                            this.tagAsBounced = false;
                            this.isLoadingbutton = false;
                        }, 700);
                    }
                });
            }

        }


    },

    mounted() {

        this.getBounceTagging();
    }
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