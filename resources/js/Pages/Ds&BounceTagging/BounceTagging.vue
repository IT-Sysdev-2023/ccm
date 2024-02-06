<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
import { Head } from '@inertiajs/vue3';
import { SmileOutlined, QuestionOutlined, TagsOutlined, UploadOutlined, BarsOutlined, HomeOutlined } from '@ant-design/icons-vue';
import axios from 'axios';
</script>

<template>
    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
        </template>

        <div class="py-5">

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                    <div>
                        <a-date-picker @change="onDateChange" v-model:value="dtYear" picker="year" style=" width: 200px;" />
                    </div>
                </div>

                <a-table :dataSource="dataSource" :columns="columns" :pagination="false" :loading="loading"
                    class="components-table-demo-nested" bordered>
                    <template #bodyCell="{ column, record }">

                        <template v-if="column.key === 'check_am'">
                            {{ formattedPrice(record.check_amount) }}
                        </template>
                        <template v-else-if="column.key === 'checks_r'">
                            {{ formattedDate(record.check_received) }}
                        </template>
                        <template v-else-if="column.key === 'check_dep'">
                            {{ formattedDate(record.check_date) }}
                        </template>
                        <template v-else-if="column.key === 'check_d'">
                            {{ formattedDate(record.date_deposit) }}
                        </template>
                        <template v-else-if="column.key === 'action'">
                            <a-popconfirm title="Continue for tagging?" @confirm="confirm" @cancel="cancel">
                                <a-button style="margin-right: 5px; background: #356e00; color: white;">
                                    <TagsOutlined />
                                </a-button>
                            </a-popconfirm>

                            <a-button v-on:click="openModalDetails(record)"
                                style="margin-right: 5px; background: #cccccc; color: black;">
                                <BarsOutlined />
                            </a-button>
                        </template>

                    </template>

                </a-table>
                <div v-if="showPag" class="mt-3 mb-5"
                    style="border: 1px solid rgb(224, 224, 224); border-radius: 10px; padding: 10px">
                    <div class="flex justify-end">
                        <a-pagination class="mt-0 mb-0" v-model:current="pagination.current"
                            v-model:page-size="pagination.pageSize" :show-size-changer="false" :total="pagination.total"
                            :show-total="(total, range) => `${range[0]}-${range[1]} of ${total} reports`"
                            @change="handleTableChange" />
                    </div>
                </div>
            </div>
            <a-modal v-model:open="openDetails" style="top: 25px" width="1000px" title="Details" @ok="handleOk">
                <table class="min-w-full divide-y divide-gray-200">

                    <tbody>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l  border-t border-gray-200">
                                Customer Name</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-t border-gray-200">{{
                                selectDataDetails.fullname }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check From</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.department }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Number</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.check_no }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Approving Officer</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.approving_officer }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Class</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.check_class }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Status</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.check_status }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Date</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.check_date }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Account No</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.account_no }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Received</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.check_received }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Account Name</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.account_name }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Received As</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.check_type }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Bank Name</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.bankbranchname }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Category</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.check_category }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Amount</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.check_amount }}</td>
                        </tr>
                    </tbody>
                </table>
                <!-- From	{ "checks_id": 195446, "checksreceivingtransaction_id": 
                17764, "customer_id": 48637, "businessunit_id": 4, "department_from": 15, 
                "leasing_docno": null, "check_bounced_id": 0, "check_no": "83037813", 
                "check_class": "COMPANY", "check_category": "LOCAL", "check_expiry": null, 
                "check_date": "2022-12-26", "check_received": "2022-12-21", "check_type": "POST DATED", 
                "account_no": "500717082", "account_name": "FA RONG FOODS", "bank_id": 7785, 
                "check_amount": "175245.72", "cash": null, "currency_id": 1, 
                "approving_officer": "MERCY", "check_status": "CLEARED", 
                "date_deposit": "2023-07-17", "batch_date": null, "is_exist": 1, "is_manual_entry": 0, 
                "deleted_at": null, "user": "20", "date_time": "2023-07-15 16:01:30", "cus_code": 10047262, 
                "fullname": ". FAN RONG FOODS INC.", "id": 20, "is_blacklist": 0, "created_at": "2019-03-02 14:49:36", 
                "updated_at": "2019-10-10 08:27:14", "empid": "01000001225", "name": "Apale, Candida Patron", 
                "username": "candie", "password": "$2y$10$J6g5ifJ.pMDZO30DIDzzO.0pEqSeCwvTQgI9rgZDNmVKq3g.vsmnK", 
                "ContactNo": "81+3820", "company_id": 1, "department_id": 8, "usertype_id": 9, "user_status": "active",
                 "user_ipaddress": "", "remember_token": "BUfL7kYnujWFAwds9hcKHag2psrAxc9wvC17oUku3REbszgzjGKuNesbNx4I", 
                 "ds_no": "3114", "department": "ATP" } -->
            </a-modal>
        </div>
    </TreasuryLayout>
</template>

<script>
import dayjs from 'dayjs';

export default {
    data() {
        return {
            dataSource: [],
            loading: false,
            dtYear: dayjs(),
            showPag: false,
            c_page: '',
            openDetails: false,
            selectDataDetails: [],
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
                },
                {
                    title: 'Check date',
                    dataIndex: 'check_date',
                    key: 'check_dep',
                },
                {
                    title: 'Date Deposited',
                    dataIndex: 'date_deposit',
                    key: 'check_d',
                },
                {
                    title: 'Customer name',
                    dataIndex: 'fullname',
                    key: 'fullname',
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
                },
                {
                    title: 'Actions',
                    dataIndex: 'action',
                    key: 'action',
                },
            ],
        };
    },
    methods: {
        onDateChange(dateObj, dateStr) {
            this.dtYear = dateObj;
            this.getBounceTagging()
        },
        async getBounceTagging(page = 1) {
            this.loading = true;
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
            }
        },
        formattedDate(event) {
            const date = new Date(event);
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            };
            return date.toLocaleDateString('en-US', options);
        },
        formattedPrice(value) {
            // You can implement your own formatting logic here
            const formatted = new Intl.NumberFormat('en-PH', {
                style: 'currency',
                currency: 'PHP',
            }).format(value);

            return formatted;

        },
        handleTableChange(pagination) {
            this.getBounceTagging(pagination);
        },

        openModalDetails(data) {
            this.openDetails = true;
            this.selectDataDetails = data;

            console.log(this.selectDataDetails);
        }


    },

    mounted() {

    }
};
</script>