<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
import { Head } from '@inertiajs/vue3';
import {
    SmileOutlined,
    QuestionOutlined,
    TagsOutlined,
    UploadOutlined,
    BarsOutlined,
    HomeOutlined,
    SettingOutlined,
    ExclamationCircleOutlined,
    InfoCircleOutlined,
    FileSearchOutlined,
    LoadingOutlined
}
    from '@ant-design/icons-vue';
import { h } from 'vue';

import axios from 'axios';

const indicator = h(LoadingOutlined, {
    style: {
        fontSize: '18px',
    },
    spin: true,
});
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


                    <!-- <a-input-search v-model:value="value" placeholder="input search loading deault" loading /> -->

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
            </a-modal>

            <a-modal v-model:open="tagAsBounced" title="Tag as Bounce" :ok-button-props="{ hidden: true }"
                :cancel-button-props="{ hidden: true }">
                <a-date-picker @change="onDateChangeReturn" v-model:value="returnDate"
                    style="width: 63%; margin-right: 5px;" class="mt-3" />
                <a-button style="width: 35%; background: #b5fcb2d0;" v-on:click="continueToTagg">Continue
                    tagging?</a-button>
            </a-modal>
        </div>
    </TreasuryLayout>
</template>

<script>
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
        },
        confirmBounceTagg(checks_id) {
            this.checksId = checks_id;
            console.log(this.checksId);
            this.tagAsBounced = true;
        },
        continueToTagg() {
            axios.post(route('tag_check_bounce'), {
                date: this.returnDate.format('YYYY-MM-DD'),
                check_id: this.checksId
            }).then(response => {
                message.success('Successfully tag as bounce');
                this.tagAsBounced = false;
                this.returnDate = dayjs();
            }).catch(error => {
                // Handle error if needed
            });
        }


    },

    mounted() {

    }
};
</script>