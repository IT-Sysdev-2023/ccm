<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dated & Post Dated Checks Report" />

    <AdminLayout>
        <template #header>
        </template>

        <div class="py-4">

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-page-header
                    style=" margin-bottom: 2px; border: 1px solid rgb(235, 237, 240) ;border-radius: 10px; height: 50px; display: flex; align-items: center; "
                    title="Dated/Postdated Checks Report"
                    :avatar="{ src: 'https://png.pngtree.com/png-vector/20221013/ourmid/pngtree-calendar-icon-logo-2023-date-time-png-image_6310337.png' }">
                </a-page-header>
                <div class="" style="display: flex; justify-content: space-between;">
                    <div class="flex">
                        <a-range-picker v-model:value="dateRange" class="mt-2 mr-2" style="width: 250px;" />
                        <div class="mr-2">
                            <a-select class="mt-2" ref="select" v-model:value="bunitCode" style="width: 160px"
                                placeholder="Business Unit" @change="handleChange">
                                <a-select-option v-for="bu in bunit" v-model:value="bu.businessunit_id">{{ bu.bname
                                }}</a-select-option>

                            </a-select>
                        </div>
                        <div class="mr-2">
                            <a-select class="mt-2" ref="select" v-model:value="pdcdatedChecks" style="width: 150px"
                                placeholder="Check Type" @change="handleChange">
                                <a-select-option value="2">PDC</a-select-option>
                                <a-select-option value="1">DATED CHECKS</a-select-option>
                            </a-select>
                        </div>
                        <div class="mr-2">
                            <a-select class="mt-2" ref="select" v-model:value="repportType" style="width: 150px"
                                placeholder="Deposit Status" @change="handleChange">
                                <a-select-option value="0">VIEW ALL</a-select-option>
                                <a-select-option value="1">PENDING</a-select-option>
                                <a-select-option value="2">DEPOSITED</a-select-option>
                            </a-select>
                        </div>
                    </div>

                    <div class="mt-2">
                        <a-button style="background-color: rgb(207, 250, 225); margin-right: 10px; width: 200px"
                            v-on:click="generateReport">
                            Generate to Excel
                        </a-button>
                        <a-button style="background-color: rgb(193, 224, 253); margin-right: 10px; width: 200px"
                            v-on:click="fetchData">
                            Fetch Data
                        </a-button>
                    </div>

                </div>
                <div class="flex mt-5 justify-end mx-0">
                    <a-input placeholder="Search Cheques" v-model:value="query.search" style="width: 420px; ">
                        <template #prefix>
                            <FileSearchOutlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Please input name or check number to filter ">
                                <InfoCircleOutlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                </div>
                <div class="mt-4">
                    <a-table :total="85" :dataSource="dataSource" class="components-table-demo-nested" :pagination="false"
                        :columns="columns" :loading="loading" bordered @change="handleTableChange">
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'checks_r'">
                                {{ formattedDate(record.check_received) }}
                            </template>
                            <template v-else-if="column.key === 'check_date'">
                                {{ formattedDate(record.check_date) }}
                            </template>
                            <template v-else-if="column.key === 'check_a'">
                                {{ formattedPrice(record.check_amount) }}
                            </template>
                            <template v-else-if="column.key === 'details'">
                                <a-button size="small">
                                    <BarsOutlined />
                                </a-button>
                            </template>

                        </template>

                    </a-table>
                    <div v-if="showPagination"  class="mt-3 mb-5" style="border: 1px solid rgb(224, 224, 224); border-radius: 10px; padding: 10px">
                        <div class="flex justify-end">
                            <a-pagination class="mt-0 mb-0"  
                                v-model:current="pagination.current"
                                v-model:page-size="pagination.pageSize"
                                :show-size-changer="false"
                                :total="pagination.total" 
                                :show-total="(total, range) => `${range[0]}-${range[1]} of ${total} reports`"
                                @change="handleTableChange" />
                        </div>
                    </div>



                </div>

            </div>
        </div>
    </AdminLayout>
</template>

<script>
import { BarsOutlined, InfoCircleOutlined, FileSearchOutlined } from '@ant-design/icons-vue';
import debounce from 'lodash/debounce'
import axios from 'axios';
export default {
    props: {
        bunit: Array,

    },
    data() {
        return {
            showPagination: false,
            dateRange: [],
            bunitCode: null,
            repportType: null,
            dataSource: [],
            pdcdatedChecks: null,
            loading: false,
            searchBar: null,
            pagination: {
                current: 1,
                total: '',
                pageSize: 10,
            },
            query: {
                search: '',
            },
            columns: [
                {
                    title: 'Checkreceived',
                    dataIndex: 'check_received',
                    key: 'checks_r',
                },
                {
                    title: 'Checkdate',
                    dataIndex: 'check_date',
                    key: 'check_date',
                },
                {
                    title: 'Bankname',
                    dataIndex: 'bankbranchname',
                    key: 'b_name',
                },
                {
                    title: 'Payee/Indorser',
                    dataIndex: 'fullname',
                    key: 'address',
                },
                {
                    title: 'Checkno',
                    dataIndex: 'check_no',
                    key: 'check_no',
                },
                {
                    title: 'Amount',
                    dataIndex: 'check_amount',
                    key: 'check_a',
                },
                {
                    title: 'Details',
                    key: 'details',
                    align: 'center',
                },
            ],
            pageCustom: 1,
        };
    },
    watch: {
        query: {
            deep: true,
            handler: debounce(async function () {
                const res = await axios.get(`get_dated_pdc_checks_rep?page=${this.pageCustom}`, {
                    params: {
                        dt_from: this.dateRange[0].toISOString(),
                        dt_to: this.dateRange[1].toISOString(),
                        bu: this.bunitCode,
                        ch_type: this.pdcdatedChecks,
                        repporttype: this.repportType,
                        search: this.query.search,
                    },
                });
                this.dataSource = res.data.data;
                this.pagination = res.data.pagination;
            }, 500)
        },
    },

    methods: {
        async fetchData(page = 1) {
            this.loading = true;
            this.pageCustom = page;
            try {
                const response = await axios.get(`get_dated_pdc_checks_rep?page=${page}`, {
                    params: {
                        dt_from: this.dateRange[0].toISOString(),
                        dt_to: this.dateRange[1].toISOString(),
                        bu: this.bunitCode,
                        ch_type: this.pdcdatedChecks,
                        repporttype: this.repportType,
                        search: this.searchBar,
                    },
                });

                this.showPagination = true;
                this.dataSource = response.data.data;
                this.pagination = response.data.pagination;

            } catch (error) {
                console.error('Error fetching data:', error);
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
        handleChange(value) {
            console.log(`selected ${value}`);
        },


        handleTableChange(pagination) {
            this.fetchData(pagination);
        },

        searchQ() {
            this.fetchData(this.searchBar);
        },
        generateReport() {
            const params = {
                dt_from: this.dateRange[0].toISOString(),
                dt_to: this.dateRange[1].toISOString(),
                bu: this.bunitCode,
                ch_type: this.pdcdatedChecks,
                repporttype: this.repportType,
            }
            const urlWithParams = '/generate_reps_to_excel?' + new URLSearchParams(params).toString();

            // Navigate to the URL
            window.location.href = urlWithParams;

            //   axios.get('generate_reps_to_excel', {
            //             params: {
            //                 dt_from: this.dateRange[0].toISOString(),
            //             dt_to: this.dateRange[1].toISOString(),
            //             bu: this.bunitCode,
            //             ch_type: this.pdcdatedChecks,
            //             repporttype: this.repportType,
            //             }
            //         })

            // console.log(data.data)
        }

    },


};
</script>

<!-- id": 28
      +"checks_id": 15998
      +"check_type": "POST DATED"
      +"remarks": null
      +"status": "REDEEM"
      +"ds_status": ""
      +"receive_status": ""
      +"done": ""
      +"user": "28"
      +"date_time": "2019-06-11"
      +"checksreceivingtransaction_id": 0
      +"customer_id": 8745
      +"businessunit_id": 2
      +"department_from": 47
      +"leasing_docno": null
      +"check_bounced_id": 0
      +"check_no": "0001791"
      +"check_class": "PERSONAL"
      +"check_category": "LOCAL"
      +"check_expiry": null
      +"check_date": "2019-07-13"
      +"check_received": "2019-06-10"
      +"account_no": "05801-000050-2"
      +"account_name": "NO ACCOUNT NAME"
      +"bank_id": 7880
      +"check_amount": "120000.00"
      +"cash": "120000.00"
      +"currency_id": 1
      +"approving_officer": "4A"
      +"check_status": "CASH"
      +"date_deposit": null
      +"batch_date": null
      +"is_exist": 0
      +"is_manual_entry": 0
      +"deleted_at": null
      +"cus_code": 10008659 -->
      <!-- +"fullname": "SALOME A. VITORILLO" hello world . com how are you today i will make you the best ion of the al little brown
      fox jumps in the tree and he was so fast that the rabbit couldnt be the on who are to make the best out of it and he will be the best if the 
      people is the best if the giant not and the coconut nut hi hello where and the she was where who was when  -->
