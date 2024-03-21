<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
</script>

<template>

    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">This is treasury Dashboard</h2>
        </template>

        <div class="py-2">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mb-2 mt-2">
                    <a-breadcrumb-item href="">
                        <HomeOutlined />
                    </a-breadcrumb-item>
                    <a-breadcrumb-item href="">
                        <span>Dashboard</span>
                    </a-breadcrumb-item>
                    <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    <a-breadcrumb-item>Check Manual Entry</a-breadcrumb-item>
                </a-breadcrumb>
                <a-card>
                    <div class="flex justify-between">
                        <a-button class="mb-3" @click="modalAddChecksManual" type="primary" style="width: 350px">

                            <template #icon>
                                <PlusSquareOutlined />
                            </template>
                            Add manual check
                        </a-button>
                        <a-input-search v-model:value="query.search" placeholder="input search text"
                            style="width: 400px" />
                    </div>
                    <a-table :loading="isloadingtable" bordered size="small" :dataSource="data.data" :columns="columns"
                        :pagination="false">

                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'action'">
                                <a-button size="small" class="mx-2" @click="openUpDetails(record)">
                                    <template #icon>
                                        <SettingOutlined />
                                    </template>
                                </a-button>
                                <a-button size="small" style="background-color: rgb(58, 168, 58); color: white;">

                                    <template #icon>
                                        <TagOutlined />
                                    </template>
                                </a-button>
                            </template>
                        </template>
                    </a-table>
                    <pagination class="mt-6" :datarecords="data" />
                </a-card>
            </div>
        </div>
        <a-modal v-model:open="openModal" title="Add manual checks Modal" :footer="null" width="90%"
            :after-close="() => manual_check_form.clearErrors()" wrap-class-name="full-modal" @ok="handleOk">
            <a-card>
                <div class="p-5 text-center">
                    <h3>
                        Adding Manual Check in database
                    </h3>
                </div>
                <a-row :gutter="[16, 16]">
                    <a-col :span="8">
                        <a-breadcrumb>
                            <a-breadcrumb-item href="">
                                <AccountBookOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Account Number</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-input v-model:value="manual_check_form.accountnumber" placeholder="Enter here...">

                            <template #prefix>

                            </template>

                            <template #suffix>
                                <a-tooltip title="Enter account number here">
                                    <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                </a-tooltip>
                            </template>
                        </a-input>
                        <div v-if="manual_check_form.errors.accountnumber" class="text-white mb-3"
                            style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                            {{
                            manual_check_form.errors.accountnumber }}</div>
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <CalendarOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Check Date</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-date-picker v-model:value="manual_check_form.checkdate" style="width: 100%;">

                        </a-date-picker>
                        <div v-if="manual_check_form.errors.checkdate" class="text-white mb-3"
                            style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                            {{
                            manual_check_form.errors.checkdate }}</div>
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <MoneyCollectOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Currency</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-select placeholder="Select Currency" v-model:value="manual_check_form.currency"
                            style="width: 100%">
                            <a-select-option v-for="(item, key) in currency" v-model:value="item.currency_id">{{
                            item.currency_name
                        }}</a-select-option>
                        </a-select>
                        <div v-if="manual_check_form.errors.currency" class="text-white mb-3"
                            style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                            {{
                            manual_check_form.errors.currency }}</div>
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">

                                <BankOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Check From</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-select show-search placeholder="Search Check From" :default-active-first-option="false"
                            v-model:value="manual_check_form.checkfrom" style="width: 100%" :show-arrow="false"
                            :filter-option="false" :not-found-content="isRetrieving ? undefined : null"
                            :options="checkOption" @search="handleSearchCheckFrom">
                            <template v-if="isRetrieving" #notFoundContent>
                                <a-spin size="small" />
                            </template>
                        </a-select>
                        <div v-if="manual_check_form.errors.checkfrom" class="text-white mb-3"
                            style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                            {{
                            manual_check_form.errors.checkfrom }}</div>
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <UsergroupAddOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Account Name</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-select show-search placeholder="Search Account name" :default-active-first-option="false"
                            v-model:value="manual_check_form.accountname" style="width: 100%" :show-arrow="false"
                            :filter-option="false" :not-found-content="isRetrieving ? undefined : null"
                            :options="accountOption" @search="handleSearchAccountName">
                            <template v-if="isRetrieving" #notFoundContent>
                                <a-spin size="small" />
                            </template>
                        </a-select>
                        <div v-if="manual_check_form.errors.accountname" class="text-white mb-3"
                            style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                            {{
                            manual_check_form.errors.accountname }}</div>
                    </a-col>
                    <a-col :span="8">
                        <a-breadcrumb>
                            <a-breadcrumb-item href="">
                                <UserOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Customer Name</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-select show-search placeholder="Search customer name" :default-active-first-option="false"
                            v-model:value="manual_check_form.customer" style="width: 100%" :show-arrow="false"
                            :filter-option="false" :not-found-content="isRetrieving ? undefined : null"
                            :options="customerOption" @search="handleSearchCustomer">
                            <template v-if="isRetrieving" #notFoundContent>
                                <a-spin size="small" />
                            </template>
                        </a-select>
                        <div v-if="manual_check_form.errors.customer" class="text-white mb-3"
                            style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                            {{
                            manual_check_form.errors.customer }}</div>

                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <MoneyCollectOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Check Amount</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-input v-model:value="manual_check_form.checkamount" placeholder="Enter here...">

                            <template #prefix>

                            </template>

                            <template #suffix>
                                <a-tooltip title="Enter check amount here...">
                                    <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                </a-tooltip>
                            </template>
                        </a-input>
                        <div v-if="manual_check_form.errors.checkamount" class="text-white mb-3"
                            style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                            {{
                            manual_check_form.errors.checkamount }}</div>
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <BankOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Check Class</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-select placeholder="Select Currency" v-model:value="manual_check_form.checkclass"
                            style="width: 100%">
                            <a-select-option v-for="(item, key) in check_class" v-model:value="item.check_class">{{
                            item.check_class
                        }}</a-select-option>
                        </a-select>
                        <div v-if="manual_check_form.errors.checkclass" class="text-white mb-3"
                            style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                            {{
                            manual_check_form.errors.checkclass }}</div>
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <MoneyCollectOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Check Number</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-input v-model:value="manual_check_form.checknumber" placeholder="Enter here...">

                            <template #prefix>

                            </template>

                            <template #suffix>
                                <a-tooltip title="Enter check number here...">
                                    <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                </a-tooltip>
                            </template>
                        </a-input>
                        <div v-if="manual_check_form.errors.checknumber" class="text-white mb-3"
                            style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                            {{
                            manual_check_form.errors.checknumber }}</div>
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <BankOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Bank Name</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-select show-search placeholder="Search bank name" :default-active-first-option="false"
                            v-model:value="manual_check_form.bankname" style="width: 100%" :show-arrow="false"
                            :filter-option="false" :not-found-content="isRetrieving ? undefined : null"
                            :options="bankOption" @search="handleSearchBank">
                            <template v-if="isRetrieving" #notFoundContent>
                                <a-spin size="small" />
                            </template>
                        </a-select>
                        <div v-if="manual_check_form.errors.bankname" class="text-white mb-3"
                            style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                            {{
                            manual_check_form.errors.bankname }}</div>
                    </a-col>
                    <a-col :span="8">
                        <a-breadcrumb>
                            <a-breadcrumb-item href="">
                                <CalendarOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Check Received</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-date-picker v-model:value="manual_check_form.checkreceived" style="width: 100%;">
                        </a-date-picker>
                        <div v-if="manual_check_form.errors.checkreceived" class="text-white mb-3"
                            style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                            {{
                            manual_check_form.errors.checkreceived }}</div>
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <HomeOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Check Category</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-select placeholder="Select Currency" v-model:value="manual_check_form.checkcategory"
                            style="width: 100%">
                            <a-select-option v-for="(item, key) in category" v-model:value="item.check_category">{{
                            item.check_category
                        }}</a-select-option>
                        </a-select>
                        <div v-if="manual_check_form.errors.checkcategory" class="text-white mb-3"
                            style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                            {{
                            manual_check_form.errors.checkcategory }}</div>
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <UsergroupAddOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Approving Officer</a-breadcrumb-item>
                        </a-breadcrumb>

                        <a-select show-search placeholder="Search bank name" :default-active-first-option="false"
                            v-model:value="manual_check_form.approvingOfficer" style="width: 100%" :show-arrow="false"
                            :filter-option="false" :not-found-content="isRetrieving ? undefined : null"
                            :options="appoffOption" @search="handleSearchEmployee">
                            <template v-if="isRetrieving" #notFoundContent>
                                <a-spin size="small" />
                            </template>
                        </a-select>
                        <div v-if="manual_check_form.errors.approvingOfficer" class="text-white mb-3"
                            style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                            {{
                            manual_check_form.errors.approvingOfficer }}</div>
                        <a-button class="mt-5" block type="primary" @click="submitManualCheckEntry"
                            :loading="manual_check_form.processing">

                            <template #icon>
                                <SaveOutlined />
                            </template>
                            {{ manual_check_form.processing ? 'saving checks...' : 'save manual checks?' }}
                        </a-button>
                        <a-button class="mt-2" block type="primary" danger @click="() => manual_check_form.reset()">

                            <template #icon>
                                <ClearOutlined></ClearOutlined>
                            </template>
                            clear input fields?
                        </a-button>
                    </a-col>
                </a-row>
            </a-card>
        </a-modal>
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
    </TreasuryLayout>
</template>

<script>
import { message } from 'ant-design-vue';
import debounce from "lodash/debounce";
import Pagination from "@/Components/Pagination.vue"
import { useForm } from '@inertiajs/vue3';
import dayjs from 'dayjs';
export default {
    data() {
        return {
            isloadingtable: false,
            query: {
                search: ''
            },
            openModal: false,
            openDetails: false,
            selectDataDetails: {},
            isRetrieving: false,
            allData: [],
            checkOption: [],
            bankOption: [],
            customerOption: [],
            accountOption: [],
            appoffOption: [],

            manual_check_form: useForm({
                accountnumber: '',
                checkdate: '',
                currency: null,
                checkfrom: null,
                accountname: null,
                customer: null,
                checkamount: '',
                checkclass: null,
                checknumber: '',
                bankname: null,
                checkreceived: '',
                checkcategory: null,
                approvingOfficer: '',
            }),

        }
    },
    props: {
        data: Array,
        columns: Array,
        currency: Array,
        category: Array,
        check_class: Array,

    },
    methods: {
        handleTableChange(page = 1) {
            this.isloadingtable = true;
            this.$inertia.get(route("manual_entry.checks"), {
                page: page,
            }, {
                preserveScroll: true,
            })
        },
        modalAddChecksManual() {
            this.openModal = true;
        },
        openUpDetails(dataIn) {
            this.openDetails = true;
            this.selectDataDetails = dataIn;

        },
        submitManualCheckEntry() {
            this.manual_check_form.transform((data) => ({
                ...data,
                checkreceived: dayjs(data.checkreceived).format('YYYY-MM-DD'),
                checkdate: dayjs(data.checkdate).format('YYYY-MM-DD'),
            })).post(route("manual_entry_store.checks"), {
                onSuccess: () => {
                    this.manual_check_form.reset();
                    this.openModal = false;
                    message.success({
                        content: "Check Successfully save",
                        duration: 3,
                    });
                }
            });
        },
        handleSearchCheckFrom: debounce(async function (query) {
            try {
                if (query.trim().length) {
                    this.isRetrieving = true
                    this.allData = []
                    this.checkOption = []
                    const { data } = await axios.get(route('search.checkfrom', { search: query }))
                    this.allData = data
                    this.checkOption = this.allData.map(checkfrom => ({ title: checkfrom.department, value: checkfrom.department_id, label: checkfrom.department }))
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            } finally {
                this.isRetrieving = false;
            }
        }, 1000),
        handleSearchBank: debounce(async function (query) {
            try {
                if (query.trim().length) {
                    this.isRetrieving = true
                    this.allData = []
                    this.bankOption = []
                    const { data } = await axios.get(route('search.bankName', { search: query }))
                    this.allData = data
                    this.bankOption = this.allData.map(bank => ({ title: bank.bankbranchname, value: bank.bank_id, label: bank.bankbranchname }))
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            } finally {
                this.isRetrieving = false;
            }
        }, 1000),
        handleSearchCustomer: debounce(async function (query) {
            try {
                if (query.trim().length) {
                    this.isRetrieving = true
                    this.allData = []
                    this.customerOption = []
                    const { data } = await axios.get(route('search.customerName', { search: query }))
                    this.allData = data
                    this.customerOption = this.allData.map(customer => ({ title: customer.fullname, value: customer.customer_id, label: customer.fullname }))
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            } finally {
                this.isRetrieving = false;
            }
        }, 1000),
        handleSearchAccountName: debounce(async function (query) {
            try {
                if (query.trim().length) {
                    this.isRetrieving = true
                    this.allData = []
                    this.accountOption = []
                    const { data } = await axios.get(route('search.customerName', { search: query }))
                    this.allData = data
                    this.accountOption = this.allData.map(account => ({ title: account.fullname, value: account.fullname, label: account.fullname }))
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            } finally {
                this.isRetrieving = false;
            }
        }, 1000),
        handleSearchEmployee: debounce(async function (query) {
            try {
                if (query.trim().length) {
                    this.isRetrieving = true
                    this.allData = []
                    this.appoffOption = []
                    const { data } = await axios.get(route('search.employeeName', { search: query }))
                    this.allData = data
                    this.appoffOption = this.allData.map(employee => ({ title: employee.name, value: employee.name, label: employee.name }))
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            } finally {
                this.isRetrieving = false;
            }
        }, 1000),
    },
    watch: {
        query: {
            deep: true,
            handler: debounce(async function () {
                this.$inertia.get(route("manual_entry.checks"), {
                    searchQuery: this.query.search,
                }, { preserveState: true }, {
                });

            }, 600),
        }
    }
}
</script>

<style>
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