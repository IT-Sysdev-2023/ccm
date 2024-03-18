<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
import { ref } from 'vue';

const size = ref('large');
</script>

<template>

    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">This is treasury Dashboard</h2>
        </template>
        <div class="py-0">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mt-3 mb-3">
                    <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                    <a-breadcrumb-item><a href="">Dated Checks/Pdc</a></a-breadcrumb-item>
                    <a-breadcrumb-item>Pending Dated Checks</a-breadcrumb-item>
                </a-breadcrumb>

                <a-card>
                    <a-table :loading="isLoadingTbl" :pagination="false" :dataSource="data.data"
                        class="components-table-demo-nested" :columns="columns" size="small" bordered>
                        <template #bodyCell="{ column, record, index }">
                            <template v-if="column.key === 'action'">
                                <a-button size="small" @click="openModaldated(record)">
                                    <template #icon>
                                        <!-- <FullscreenOutlined /> -->
                                        <SettingOutlined />
                                    </template>
                                </a-button>
                                <a-button type="primary" class="mx-2" size="small" @click="showModalReplace(record)">
                                    <template #icon>
                                        <FileSyncOutlined />
                                    </template>
                                </a-button>
                            </template>
                        </template>
                    </a-table>
                    <pagination class="mt-6" :datarecords="data" />
                </a-card>
            </div>
        </div>

        <a-modal v-model:open="isModalOpen" title="Details" style="top: 20px; width: 1000px;"
            @ok="setModal1Visible(false)" :footer="null">
            <div class="product-container">
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

        <a-modal v-model:open="openModalReplace" title="Replacement Checks Configuration" :footer="null"
            :after-close="afterClose" style="top: 20px; width: 100%;" wrap-class-name="full-modal">
            <a-row :gutter="[16, 16]">
                <a-col :span="6">
                    <a-card>
                        <table class="table">
                            <thead>
                                <th class="thh" colspan="2">Check Information</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Check Number</th>
                                    <td>{{ selectDataDetails.check_no }}</td>
                                </tr>
                                <tr>
                                    <th>Check Amount</th>
                                    <td>{{ selectDataDetails.check_amount }}</td>
                                </tr>
                                <tr>
                                    <th>Check Date</th>
                                    <td>{{ selectDataDetails.check_date }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </a-card>
                    <a-card class="mt-1">
                        <p class="text-center font-bold">Please Select Type Of Payment</p>
                        <a-button class="mt-2" block @click="cashButtonType">
                            Cash
                        </a-button>
                        <a-button class="mt-2" block @click="checkButtonType">
                            Check
                        </a-button>
                        <a-button class="mt-2" block @click="cashCheckButtonType">
                            Check and cash
                        </a-button>
                        <a-button class="mt-2" block @click="partialPayCashButtton">
                            Partial Payment Cash
                        </a-button>
                        <a-button class="mt-2" block @click="partialPayCheckButtton">
                            Partial Payment Check
                        </a-button>
                    </a-card>
                </a-col>
                <a-col :span="18">
                    <a-card class="flex justify-center" style="height: 100%; ">
                        <template # v-if="defaultShow">
                            <a-result title="Please select type you wish to replace !"
                                sub-title="And always remember dont have a good day have a great day!">
                                <template #icon>
                                    <img src="../../../../public/Logo/ccmpbng.png" alt="ccmrobot"
                                        style="height: 160px;">
                                </template>
                                <template #extra>

                                </template>
                            </a-result>
                        </template>
                        <template #extra v-if="cashShow">
                            <p class="text-center font-bold"> CASH TYPE
                            </p>
                            <a-breadcrumb class="mt-2 ml-1">
                                <a-breadcrumb-item>Cash Amount</a-breadcrumb-item>
                            </a-breadcrumb>
                            <a-input v-model:value="cash_form.rep_check_id" class="hidden">
                            </a-input>
                            <a-input v-model:value="cash_form.rep_cash_amount" class="mb-2" readonly
                                style="width: 550px;" placeholder="Enter cash amount">
                                <template #prefix>
                                    <MoneyCollectOutlined />
                                </template>
                                <template #suffix>
                                    <a-tooltip title="Enter cash amount here">
                                        <InfoCircleOutlined />
                                    </a-tooltip>
                                </template>
                            </a-input>
                            <a-breadcrumb class="mt-2 ml-1">
                                <a-breadcrumb-item>Penalty Amount</a-breadcrumb-item>
                            </a-breadcrumb>
                            <a-input v-model:value="cash_form.rep_cash_penalty" class="mb-2"
                                placeholder="Enter penalty amount">
                                <template #prefix>
                                    <MoneyCollectOutlined />
                                </template>
                                <template #suffix>
                                    <a-tooltip title="Enter penalty amount here!">
                                        <InfoCircleOutlined />
                                    </a-tooltip>
                                </template>
                            </a-input>
                            <div v-if="cash_form.errors.rep_cash_penalty" class="text-red-600" style="font-size: 12px;">
                                {{
                        cash_form.errors.rep_cash_penalty }}
                            </div>
                            <a-breadcrumb class="mt-2 ml-1">
                                <a-breadcrumb-item>AR # & DS #</a-breadcrumb-item>
                            </a-breadcrumb>
                            <a-input v-model:value="cash_form.rep_ar_ds" class="mb-2" placeholder="Enter AR# and DS#">
                                <template #prefix>
                                    <NumberOutlined />
                                </template>
                                <template #suffix>
                                    <a-tooltip title="Enter AR and DS # here">
                                        <InfoCircleOutlined />
                                    </a-tooltip>
                                </template>
                            </a-input>
                            <div v-if="cash_form.errors.rep_ar_ds" class="text-red-600" style="font-size: 12px;">
                                {{
                        cash_form.errors.rep_ar_ds }}
                            </div>
                            <a-textarea v-model:value="cash_form.rep_reason" placeholder="Your reason of replace"
                                :rows="3" />
                            <div v-if="cash_form.errors.rep_reason" class="text-red-600" style="font-size: 12px;">
                                {{
                        cash_form.errors.rep_reason }}
                            </div>
                            <a-breadcrumb class="mt-2 ml-1">
                                <a-breadcrumb-item>Replacement date</a-breadcrumb-item>
                            </a-breadcrumb>
                            <a-date-picker style="width: 100%;" v-model:value="cash_form.rep_date">
                            </a-date-picker>
                            <div v-if="cash_form.errors.rep_date" class="text-red-600" style="font-size: 12px;">
                                {{
                        cash_form.errors.rep_date }}
                            </div>
                            <a-row :gutter="[16, 16]">

                                <a-col :span="12">
                                    <a-button block class="mb-10 mt-5"
                                        @click="() => cash_form.reset('rep_cash_penalty', 'rep_ar_ds', 'repDatePicker', 'rep_date', 'rep_reason')"
                                        type="primary" danger>
                                        <template #icon>
                                            <ClearOutlined />
                                        </template>
                                        Reset
                                        form</a-button>
                                </a-col>
                                <a-col :span="12">
                                    <a-button block type="primary" @click="submit_cash_replacement" class="mt-5"
                                        :loading="cash_form.processing">
                                        <template #icon>
                                            <SaveOutlined />
                                        </template>
                                        {{ cash_form.processing ?
                                        "Submitting... " : "Submit cash type" }}
                                    </a-button>
                                </a-col>

                            </a-row>

                        </template>
                        <template #extra v-else-if="checkShow">
                            <p class="text-center font-bold py-5"> CHECK TYPE
                            </p>
                            <a-row :gutter="[16, 16]">
                                <a-col :span="12" style="width: 600px">
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Account Number</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input class="hidden" v-model:value="check_form.rep_check_id">

                                    </a-input>
                                    <a-input v-model:value="check_form.accountnumber" placeholder="Enter Account Number"
                                        style="width: 100%">
                                        <template #prefix>
                                            <NumberOutlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Account Number here">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Account Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="check_form.accountname" placeholder="Enter Account Name"
                                        style="width: 100%">
                                        <template #prefix>
                                            <user-outlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Account name">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Number</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="check_form.checkNumber" placeholder="Enter Check number"
                                        style="width: 100%">
                                        <template #prefix>
                                            <NumberOutlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Check number here">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Date</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker v-model:value="check_form.rep_check_date" style="width: 100%">
                                    </a-date-picker>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Received</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker v-model:value="check_form.rep_check_recieved" style="width: 100%">
                                    </a-date-picker>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Amount</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="check_form.rep_check_amount" placeholder="Basic usage"
                                        style="width: 100%">
                                        <template #prefix>
                                            <MoneyCollectOutlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Check Amount">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Penalty</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="check_form.rep_check_penalty"
                                        placeholder="Enter Penalty Amount" style="width: 100%">
                                        <template #prefix>
                                            <MoneyCollectOutlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Penalty amount">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Approving Officer</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search check form"
                                        :default-active-first-option="false" v-model:value="check_form.approvingOfficer"
                                        style="width: 100%" :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="allOptions"
                                        @search="handleSearchEmployee">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Replacement date</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker style="width: 100%;" class="mb-5"
                                        v-model:value="check_form.rep_date">
                                    </a-date-picker>
                                </a-col>
                                <a-col :span="12">
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check From</a-breadcrumb-item>
                                    </a-breadcrumb>


                                    <a-select show-search placeholder="Search check from"
                                        :default-active-first-option="false" v-model:value="check_form.checkFrom_id"
                                        style="width: 100%" :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="allOptions"
                                        @search="handleSearchCheckFrom">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>


                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Bank Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search bank name"
                                        :default-active-first-option="false" v-model:value="check_form.bank_id"
                                        style="width: 100%" :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="allOptions"
                                        @search="handleSearchBank">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>


                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Customer Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search customer name"
                                        :default-active-first-option="false" v-model:value="check_form.customerName"
                                        style="width: 100%" :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="allOptions"
                                        @search="handleSearchCustomer">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>


                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Currency</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select placeholder="Select Currency" v-model:value="check_form.currency_id"
                                        style="width: 100%">

                                        <a-select-option v-for="(item , key) in currency"
                                            v-model:value="item.currency_id">{{
                                            item.currency_name
                                            }}</a-select-option>

                                    </a-select>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Category</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select ref="select" placeholder="Select category"
                                        v-model:value="check_form.category" style="width: 100%">
                                        <a-select-option v-for="(catItem , key) in category"
                                            v-model:value="catItem.check_category">{{
                                            catItem.check_category
                                            }}</a-select-option>

                                    </a-select>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check class</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select ref="select" placeholder="Select category"
                                        v-model:value="check_form.checkClass" style="width: 100%">
                                        <a-select-option v-for="(chclass , key) in check_class"
                                            v-model:value="chclass.check_class">{{
                                            chclass.check_class
                                            }}</a-select-option>
                                    </a-select>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Reason for return</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-textarea v-model:value="check_form.rep_reason" placeholder="Input text heres"
                                        :rows="4" />
                                    <a-button block class="mt-2" type="primary" @click="submitReplacementCheck">
                                        Submit replacement check type
                                    </a-button>
                                </a-col>
                            </a-row>
                        </template>
                        <template #extra v-else-if="cashCheckShow">
                            <p class="text-center font-bold py-5"> CHECK AND CASH TYPE
                            </p>

                            <a-row :gutter="[16, 16]">
                                <a-col :span="12" style="width: 500px">
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Account Number</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="userName" placeholder="Enter Account Number"
                                        style="width: 100%">
                                        <template #prefix>
                                            <user-outlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Account Number here">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Account Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="userName" placeholder="Enter Account Name"
                                        style="width: 100%">
                                        <template #prefix>
                                            <user-outlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Account name">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Number</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="userName" placeholder="Enter Check number"
                                        style="width: 100%">
                                        <template #prefix>
                                            <user-outlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Check number here">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Date</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker v-model:value="userName" style="width: 100%">
                                    </a-date-picker>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Received</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker v-model:value="userName" style="width: 100%">
                                    </a-date-picker>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Amount</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="userName" placeholder="Basic usage" style="width: 100%">
                                        <template #prefix>
                                            <user-outlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Check Amount">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Penalty</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="userName" placeholder="Enter Penalty Amount"
                                        style="width: 100%">
                                        <template #prefix>
                                            <user-outlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Penalty amount">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Approving Officer</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="userName" placeholder="Enter approving officer"
                                        style="width: 100%">
                                        <template #prefix>
                                            <user-outlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Extra information">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Cash Amount</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="userName" placeholder="Enter approving officer"
                                        style="width: 100%">
                                        <template #prefix>
                                            <user-outlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Extra information">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>AR# and DS#</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="userName" placeholder="Enter approving officer"
                                        style="width: 100%">
                                        <template #prefix>
                                            <user-outlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Extra information">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Replacement date</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker style="width: 100%;" class="mb-5">

                                    </a-date-picker>
                                </a-col>
                                <a-col :span="12">
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check From</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input-search v-model:value="value" placeholder="Search Check From"
                                        style="width: 100%" @search="onSearch" />
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Bank Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input-search v-model:value="value" placeholder="Search bank" style="width: 100%"
                                        @search="onSearch" />
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Customer Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input-search v-model:value="value" placeholder="Customer name"
                                        style="width: 100%" @search="onSearch" />
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Currency</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select ref="select" placeholder="Select Currency" v-model:value="value1"
                                        style="width: 100%" @focus="focus">
                                        <a-select-option value="jack">Jack</a-select-option>
                                        <a-select-option value="lucy">Lucy</a-select-option>
                                        <a-select-option value="disabled" disabled>Disabled</a-select-option>
                                        <a-select-option value="Yiminghe">yiminghe</a-select-option>
                                    </a-select>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check From</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select ref="select" placeholder="Select Check From" v-model:value="value1"
                                        style="width: 100%" @focus="focus">
                                        <a-select-option value="jack">Jack</a-select-option>
                                        <a-select-option value="lucy">Lucy</a-select-option>
                                        <a-select-option value="disabled" disabled>Disabled</a-select-option>
                                        <a-select-option value="Yiminghe">yiminghe</a-select-option>
                                    </a-select>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check class</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select ref="select" placeholder="Select Check class" v-model:value="value1"
                                        style="width: 100%" @focus="focus">
                                        <a-select-option value="jack">Jack</a-select-option>
                                        <a-select-option value="lucy">Lucy</a-select-option>
                                        <a-select-option value="disabled" disabled>Disabled</a-select-option>
                                        <a-select-option value="Yiminghe">yiminghe</a-select-option>
                                    </a-select>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Reason for return</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-textarea v-model:value="value" placeholder="Input text heres" :rows="5" />
                                    <a-button block class="mt-2" type="primary">
                                        Submit replacement check and cash type
                                    </a-button>
                                </a-col>
                            </a-row>
                        </template>
                        <template #extra v-else-if="partialPayCash">
                            <div style="width: 500px">
                                <p class="text-center font-bold"> PARTIAL PAYMENT CASH
                                </p>
                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Cash Amount</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-input v-model:value="userName" class="mb-2" placeholder="Enter cash amount">
                                    <template #prefix>
                                        <UserOutlined />
                                    </template>
                                    <template #suffix>
                                        <a-tooltip title="Enter cash amount here">
                                            <InfoCircleOutlined />
                                        </a-tooltip>
                                    </template>
                                </a-input>
                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Penalty Amount</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-input v-model:value="userName" class="mb-2" placeholder="Enter penalty amount">
                                    <template #prefix>
                                        <UserOutlined />
                                    </template>
                                    <template #suffix>
                                        <a-tooltip title="Enter penalty amount here!">
                                            <InfoCircleOutlined />
                                        </a-tooltip>
                                    </template>
                                </a-input>
                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>AR # & DS #</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-input v-model:value="userName" class="mb-2" placeholder="Enter AR# and DS#">
                                    <template #prefix>
                                        <UserOutlined />
                                    </template>
                                    <template #suffix>
                                        <a-tooltip title="Enter AR and DS # here">
                                            <InfoCircleOutlined />
                                        </a-tooltip>
                                    </template>
                                </a-input>
                                <a-textarea v-model:value="value" placeholder="Your reason of replace" :rows="3" />
                                <a-row :gutter="[16, 16]">
                                    <a-col :span="12">
                                        <a-breadcrumb class="mt-2 ml-1">
                                            <a-breadcrumb-item>Replacement date</a-breadcrumb-item>
                                        </a-breadcrumb>
                                        <a-date-picker style="width: 100%;">

                                        </a-date-picker>
                                    </a-col>
                                    <a-col :span="12">
                                        <a-breadcrumb class="mt-2 ml-1">
                                            <a-breadcrumb-item>Proceed?</a-breadcrumb-item>
                                        </a-breadcrumb>
                                        <a-button block type="primary">
                                            Submit replacing cash
                                        </a-button>
                                    </a-col>
                                </a-row>
                            </div>
                        </template>
                        <template #extra v-else-if="partialPayCheck">
                            <p class="text-center font-bold py-5"> PARTIAL PAYMENT CHECK TYPE
                            </p>

                            <a-row :gutter="[16, 16]">
                                <a-col :span="12" style="width: 600px">
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Account Number</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="userName" placeholder="Enter Account Number"
                                        style="width: 100%">
                                        <template #prefix>
                                            <user-outlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Account Number here">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Account Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="userName" placeholder="Enter Account Name"
                                        style="width: 100%">
                                        <template #prefix>
                                            <user-outlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Account name">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Number</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="userName" placeholder="Enter Check number"
                                        style="width: 100%">
                                        <template #prefix>
                                            <user-outlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Check number here">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Date</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker v-model:value="userName" style="width: 100%">
                                    </a-date-picker>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Received</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker v-model:value="userName" style="width: 100%">
                                    </a-date-picker>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Amount</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="userName" placeholder="Basic usage" style="width: 100%">
                                        <template #prefix>
                                            <user-outlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Check Amount">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Penalty</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="userName" placeholder="Enter Penalty Amount"
                                        style="width: 100%">
                                        <template #prefix>
                                            <user-outlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Penalty amount">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Approving Officer</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="userName" placeholder="Enter approving officer"
                                        style="width: 100%">
                                        <template #prefix>
                                            <user-outlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Extra information">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Replacement date</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker style="width: 100%;" class="mb-5">

                                    </a-date-picker>
                                </a-col>
                                <a-col :span="12">
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check From</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input-search v-model:value="value" placeholder="Search Check From"
                                        style="width: 100%" @search="onSearch" />
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Bank Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input-search v-model:value="value" placeholder="Search bank" style="width: 100%"
                                        @search="onSearch" />
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Customer Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input-search v-model:value="value" placeholder="Customer name"
                                        style="width: 100%" @search="onSearch" />
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Currency</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select ref="select" placeholder="Select Currency" v-model:value="value1"
                                        style="width: 100%" @focus="focus">
                                        <a-select-option value="jack">Jack</a-select-option>
                                        <a-select-option value="lucy">Lucy</a-select-option>
                                        <a-select-option value="disabled" disabled>Disabled</a-select-option>
                                        <a-select-option value="Yiminghe">yiminghe</a-select-option>
                                    </a-select>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check From</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select ref="select" placeholder="Select Check From" v-model:value="value1"
                                        style="width: 100%" @focus="focus">
                                        <a-select-option value="jack">Jack</a-select-option>
                                        <a-select-option value="lucy">Lucy</a-select-option>
                                        <a-select-option value="disabled" disabled>Disabled</a-select-option>
                                        <a-select-option value="Yiminghe">yiminghe</a-select-option>
                                    </a-select>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check class</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select ref="select" placeholder="Select Check class" v-model:value="value1"
                                        style="width: 100%" @focus="focus">
                                        <a-select-option value="jack">Jack</a-select-option>
                                        <a-select-option value="lucy">Lucy</a-select-option>
                                        <a-select-option value="disabled" disabled>Disabled</a-select-option>
                                        <a-select-option value="Yiminghe">yiminghe</a-select-option>
                                    </a-select>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Reason for return</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-textarea v-model:value="value" placeholder="Input text heres" :rows="4" />
                                    <a-button block class="mt-2" type="primary">
                                        Submit replacement check type
                                    </a-button>
                                </a-col>
                            </a-row>
                        </template>
                    </a-card>
                </a-col>
            </a-row>
        </a-modal>
    </TreasuryLayout>
</template>
<script>
import Pagination from "@/Components/Pagination.vue"
import dayjs from 'dayjs';
import { useForm } from '@inertiajs/vue3';
import { message } from 'ant-design-vue';
import debounce from 'lodash/debounce'
export default {
    data() {
        return {
            isModalOpen: false,
            openModalReplace: false,
            selectDataDetails: [],
            isLoadingTbl: false,
            checkShow: false,
            cashShow: false,
            cashCheckShow: false,
            partialPayCheck: false,
            partialPayCash: false,
            defaultShow: true,
            allData: [],
            allOptions: [],

            cash_form: useForm({
                rep_check_id: '',
                rep_cash_amount: '',
                rep_cash_penalty: '',
                rep_ar_ds: '',
                rep_reason: '',
                rep_date: '',
            }),
            check_form: useForm({
                rep_check_id: '',
                checkFrom_id: null,
                bank_id: null,
                customerName: null,
                approvingOfficer: null,
                currency_id: null,
                category: null,
                rep_reason: null,
                checkClass: null,
                rep_date: '',
                rep_check_date: '',
                rep_check_recieved: '',
                rep_check_penalty: '',
                rep_check_amount: '',
                accountname: '',
                accountnumber: '',
                checkNumber: '',
            })
        }
    },
    props: {
        data: Array,
        columns: Array,
        currency: Array,
        category: Array,
        check_class: Array
    },
    methods: {
        openModaldated(data) {
            this.isModalOpen = true;
            this.selectDataDetails = data;
        },

        showModalReplace(dataIn) {
            this.selectDataDetails = dataIn;
            this.cash_form.rep_check_id = dataIn.checks_id;
            this.check_form.rep_check_id = dataIn.checks_id;
            this.cash_form.rep_cash_amount = dataIn.check_amount;
            this.openModalReplace = true;
        },
        handleTableChange(page) {
            this.isLoadingTbl = true;
            try {

                this.$inertia.get(route('pdc.checks'), {
                    page: page,
                },
                    {
                        preserveScroll: true,
                    })
            } catch {

            }
        },
        checkButtonType() {
            this.checkShow = true;
            this.defaultShow = false;
            this.cashShow = false;
            this.cashChecShow = false;
            this.partialPayCheck = false;
            this.partialPayCash = false;
        },
        cashButtonType() {
            this.checkShow = false;
            this.defaultShow = false;
            this.cashShow = true;
            this.cashChecShow = false;
            this.partialPayCheck = false;
            this.partialPayCash = false;
        },
        cashCheckButtonType() {
            this.checkShow = false;
            this.defaultShow = false;
            this.cashShow = false;
            this.cashCheckShow = true;
            this.partialPayCheck = false;
            this.partialPayCash = false;
        },
        partialPayCashButtton() {
            this.checkShow = false;
            this.defaultShow = false;
            this.cashShow = false;
            this.cashCheckShow = false;
            this.partialPayCheck = false;
            this.partialPayCash = true;
        },
        partialPayCheckButtton() {
            this.checkShow = false;
            this.defaultShow = false;
            this.cashShow = false;
            this.cashCheckShow = false;
            this.partialPayCheck = true;
            this.partialPayCash = false;
        },
        submit_cash_replacement() {
            this.isLoadingbutton = true;
            this.cash_form.transform((data) => ({
                ...data,
                rep_date: dayjs(data.rep_date).format('YYYY-MM-DD')
            })).
                post(route('pdc_cash.replacement'), {
                    onSuccess: () => {
                        this.openModalReplace = false;
                        this.isLoadingbutton = false;
                        this.cash_form.reset();
                        message.success({
                            content: "Successfully replaced the cash type!",
                            duration: 3,
                        });
                    }
                })
        },
        submitReplacementCheck() {
            this.check_form.transform((data) => ({
                ...data,
                rep_date: dayjs(data.rep_date).format('YYYY-MM-DD')
            })).post(route('pdc_check.replacement'), {});
        },
        afterClose() {
            this.cash_form.clearErrors();
            this.defaultShow = true;
            this.checkShow = false;
            this.cashShow = false;
            this.cashCheckShow = false;
            this.partialPayCheck = false;
            this.partialPayCash = false;
        },

        handleSearchCheckFrom: debounce(async function (query) {
            console.log('object');
            try {
                if (query.trim().length) {
                    this.isRetrieving = true
                    this.allData = []
                    this.allOptions = []
                    const { data } = await axios.get(route('search.checkfrom', { search: query }))
                    this.allData = data
                    this.allOptions = this.allData.map(checkfrom => ({ title: checkfrom.department, value: checkfrom.department_id, label: checkfrom.department }))
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
                    this.allOptions = []
                    const { data } = await axios.get(route('search.bankName', { search: query }))
                    this.allData = data
                    this.allOptions = this.allData.map(bank => ({ title: bank.bankbranchname, value: bank.bank_id, label: bank.bankbranchname }))
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
                    this.allOptions = []
                    const { data } = await axios.get(route('search.customerName', { search: query }))
                    this.allData = data
                    this.allOptions = this.allData.map(customer => ({ title: customer.fullname, value: customer.fullname, label: customer.fullname }))
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
                    this.allOptions = []
                    const { data } = await axios.get(route('search.employeeName', { search: query }))
                    this.allData = data
                    this.allOptions = this.allData.map(employee => ({ title: employee.name, value: employee.name, label: employee.name }))
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            } finally {
                this.isRetrieving = false;
            }
        }, 1000),

    }
};
</script>
<style scoped>
body {
    font-family: Arial, sans-serif;
}


.product-container {
    margin: 20px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
}

.product-container tr {
    border: 1px solid #ddd;
}

.product-container td {
    border: 1px solid #ddd;
}

.table {
    border-collapse: collapse;
    width: 100%;
}


td {
    border: 1px solid rgb(172, 172, 172);
    padding: 7px;
    text-align: left;
    /* border-radius: 10px; */
}

th {
    border: 1px solid rgb(189, 189, 189);
    padding: 7px;
    text-align: left;
    width: 40%
        /* border-radius: 10px; */
}

.thh {
    background-color: #d3d3d3;
}
</style>