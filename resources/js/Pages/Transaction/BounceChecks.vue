<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
</script>

<template>

    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">This is treasury Dashboard</h2>
        </template>

        <div class="py-0">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mb-2 mt-2">
                    <a-breadcrumb-item href="">
                        <HomeOutlined />
                    </a-breadcrumb-item>
                    <a-breadcrumb-item href="">
                        <span>Dashboard</span>
                    </a-breadcrumb-item>
                    <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    <a-breadcrumb-item>Bounced Checks

                    </a-breadcrumb-item>
                </a-breadcrumb>
                <a-card>
                    <a-table :loading="isLoadingTable" :dataSource="data.data" :columns="columns" size="small" bordered
                        :pagination="false">
                        <template #bodyCell="{ column, record, index }">
                            <template v-if="column.key === 'check_box'">
                                <a-switch size="small" v-model:checked="record.isChecked"
                                    @change="computedAmount(record)">
                                    <template #checkedChildren><check-outlined /></template>
                                    <template #unCheckedChildren><close-outlined /></template>
                                </a-switch>
                            </template>
                            <template v-if="column.key === 'action'">
                                <a-button size="small" class="" @click="openUpDetails(record)">
                                    <template #icon>
                                        <SettingOutlined />
                                    </template>
                                </a-button>
                                <a-button size="small" class="mx-1" @click="openUpDetailsFolder(record)">
                                    <template #icon>
                                        <FolderOutlined />
                                    </template>
                                </a-button>
                            </template>
                        </template>
                    </a-table>
                    <pagination class="mt-6" :datarecords="data" />
                </a-card>
            </div>
        </div>
        <a-modal v-model:open="openModalReplace" title="Replacement Checks Configuration" :footer="null"
            :after-close="afterClose" style="top: 20px; width: 100%;" wrap-class-name="full-modal">
            <a-row :gutter="[16, 16]">
                <a-col :span="7">
                    <a-card>
                        <table class="table">
                            <thead>
                                <th class="thh" colspan="2">Check Information</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Bounce check no.</th>
                                    <td>{{ selectDataDetails.check_no }}</td>
                                </tr>
                                <tr>
                                    <th>Bounce check amount</th>
                                    <td>{{ selectDataDetails.check_amount }}</td>
                                </tr>
                                <tr>
                                    <th>Bounce check date</th>
                                    <td>{{ selectDataDetails.check_date }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </a-card>
                    <a-card class="mt-1">
                        <p class="text-center font-bold">Please Select Type Of Payment</p>
                        <a-button class="mt-2" block @click="cashButtonType" :class="{ 'active': isActive === 'cash' }">
                            Cash
                        </a-button>
                        <a-button class="mt-2" block @click="checkButtonType"
                            :class="{ 'active': isActive === 'check' }">
                            Check
                        </a-button>
                        <a-button class="mt-2" block @click="cashCheckButtonType"
                            :class="{ 'active': isActive === 'check&cash' }">
                            Check and cash
                        </a-button>
                        <a-button class="mt-2" block @click="partialPayCashButtton"
                            :class="{ 'active': isActive === 'partialpaycash' }">
                            Partial Payment Cash
                        </a-button>
                        <a-button class="mt-2" block @click="partialPayCheckButtton"
                            :class="{ 'active': isActive === 'partialpaycheck' }">
                            Partial Payment Check
                        </a-button>
                    </a-card>
                </a-col>
                <a-col :span="17">
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
                            <div v-if="cash_form.errors.rep_cash_penalty" class="text-white"
                                style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
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
                            <div v-if="cash_form.errors.rep_ar_ds" class="text-white"
                                style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                {{
                        cash_form.errors.rep_ar_ds }}
                            </div>
                            <a-textarea v-model:value="cash_form.rep_reason" placeholder="Your reason of replace"
                                :rows="3" />
                            <div v-if="cash_form.errors.rep_reason" class="text-white"
                                style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                {{
                        cash_form.errors.rep_reason }}
                            </div>
                            <a-breadcrumb class="mt-2 ml-1">
                                <a-breadcrumb-item>Replacement date</a-breadcrumb-item>
                            </a-breadcrumb>
                            <a-date-picker style="width: 100%;" v-model:value="cash_form.rep_date">
                            </a-date-picker>
                            <div v-if="cash_form.errors.rep_date" class="text-white"
                                style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
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
                                        Clear all inputs
                                    </a-button>
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
                                    <div v-if="check_form.errors.accountnumber" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        check_form.errors.accountnumber }}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Account Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search acount name"
                                        :default-active-first-option="false" v-model:value="check_form.accountname"
                                        style="width: 100%" :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="accountOption"
                                        @search="handleSearchAccountName">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>
                                    <div v-if="check_form.errors.accountname   " class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        check_form.errors.accountname }}</div>
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
                                    <div v-if="check_form.errors.checkNumber   " class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        check_form.errors.checkNumber }}</div>

                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Date</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker v-model:value="check_form.rep_check_date" style="width: 100%">
                                    </a-date-picker>
                                    <div v-if="check_form.errors.rep_check_date   " class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        check_form.errors.rep_check_date }}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Received</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker v-model:value="check_form.rep_check_received" style="width: 100%">
                                    </a-date-picker>
                                    <div v-if="check_form.errors.rep_check_received" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        check_form.errors.rep_check_received}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Amount</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="check_form.rep_check_amount" placeholder="Basic usage"
                                        readonly style="width: 100%">
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
                                    <div v-if="check_form.errors.rep_check_penalty" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        check_form.errors.rep_check_penalty}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Approving Officer</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search approving officer"
                                        :default-active-first-option="false" v-model:value="check_form.approvingOfficer"
                                        style="width: 100%" :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="appoffOption"
                                        @search="handleSearchEmployee">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>
                                    <div v-if="check_form.errors.approvingOfficer" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        check_form.errors.approvingOfficer}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Replacement date</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker style="width: 100%;" v-model:value="check_form.rep_date">
                                    </a-date-picker>
                                    <div v-if="check_form.errors.rep_date" class="text-white mb-5"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        check_form.errors.rep_date}}</div>
                                </a-col>
                                <a-col :span="12">
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check From</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search check from"
                                        :default-active-first-option="false" v-model:value="check_form.checkFrom_id"
                                        style="width: 100%" :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="checkOption"
                                        @search="handleSearchCheckFrom">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>
                                    <div v-if="check_form.errors.checkFrom_id" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        check_form.errors.checkFrom_id}}</div>

                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Bank Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search bank name"
                                        :default-active-first-option="false" v-model:value="check_form.bank_id"
                                        style="width: 100%" :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="bankOption"
                                        @search="handleSearchBank">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>
                                    <div v-if="check_form.errors.bank_id" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        check_form.errors.bank_id}}</div>


                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Customer Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search customer name"
                                        :default-active-first-option="false" v-model:value="check_form.customerId"
                                        style="width: 100%" :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="customerOption"
                                        @search="handleSearchCustomer">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>
                                    <div v-if="check_form.errors.customerId" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        check_form.errors.customerId
                                        }}</div>


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
                                    <div v-if="check_form.errors.currency_id" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        check_form.errors.currency_id}}</div>
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
                                    <div v-if="check_form.errors.category" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        check_form.errors.category}}</div>
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
                                    <div v-if="check_form.errors.checkClass" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        check_form.errors.checkClass}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Reason for return</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-textarea v-model:value="check_form.rep_reason" placeholder="Input text heres"
                                        :rows="4" />
                                    <div v-if="check_form.errors.rep_reason" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        check_form.errors.rep_reason}}</div>

                                    <a-row :gutter="[16, 16]" class="mt-4">
                                        <a-col :span="12">
                                            <a-button block class="mt-2" type="primary" danger @click="() => check_form.reset(
                                                    'checkFrom_id',
                                                    'bank_id',
                                                    'customerId',
                                                    'approvingOfficer',
                                                    'currency_id',
                                                    'category',
                                                    'rep_reason',
                                                    'checkClass',
                                                    'rep_date',
                                                    'rep_check_date',
                                                    'rep_check_recieved',
                                                    'rep_check_penalty',
                                                    'accountname',
                                                    'accountnumber',
                                                    'checkNumber',
                                                    )">
                                                <template #icon>
                                                    <ClearOutlined />
                                                </template>
                                                Clear all inputs
                                            </a-button>
                                        </a-col>
                                        <a-col :span="12" class="mb-7">
                                            <a-button block class="mt-2" type="primary" @click="submitReplacementCheck"
                                                :loading="check_form.processing">
                                                <template #icon>
                                                    <SaveOutlined />
                                                </template>
                                                {{ check_form.processing
                                                ? 'Submitting check type...'
                                                : 'Submit check type'
                                                }}
                                            </a-button>
                                        </a-col>
                                    </a-row>
                                </a-col>
                            </a-row>
                        </template>
                        <template #extra v-else-if="cashCheckShow">
                            <p class="text-center font-bold py-5"> CHECK AND CASH TYPE
                            </p>

                            <a-row :gutter="[16, 16]">
                                <a-col :span="12" style="width: 600px">
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Account Number</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input class="hidden" v-model:value="cash_check_form.rep_check_id">
                                    </a-input>
                                    <a-input v-model:value="cash_check_form.accountnumber"
                                        placeholder="Enter Account Number" style="width: 100%">
                                        <template #prefix>
                                            <NumberOutlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Account Number here">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <div v-if="cash_check_form.errors.accountnumber" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.accountnumber }}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Account Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search acount name"
                                        :default-active-first-option="false" v-model:value="cash_check_form.accountname"
                                        style="width: 100%" :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="accountOption"
                                        @search="handleSearchAccountName">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>
                                    <div v-if="cash_check_form.errors.accountname   " class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.accountname }}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Number</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="cash_check_form.checkNumber"
                                        placeholder="Enter Check number" style="width: 100%">
                                        <template #prefix>
                                            <NumberOutlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Check number here">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <div v-if="cash_check_form.errors.checkNumber   " class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.checkNumber }}</div>

                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Date</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker v-model:value="cash_check_form.rep_check_date" style="width: 100%">
                                    </a-date-picker>
                                    <div v-if="cash_check_form.errors.rep_check_date   " class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.rep_check_date }}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Received</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker v-model:value="cash_check_form.rep_check_received"
                                        style="width: 100%">
                                    </a-date-picker>
                                    <div v-if="cash_check_form.errors.rep_check_received" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.rep_check_received}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Amount</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="cash_check_form.rep_check_amount" placeholder="Basic usage"
                                        readonly style="width: 100%">
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
                                        <a-breadcrumb-item>Cash Amount</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="cash_check_form.rep_cash_amount" placeholder="cash amount"
                                        style="width: 100%">
                                        <template #prefix>
                                            <MoneyCollectOutlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Cash Amount">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <div v-if="cash_check_form.errors.rep_cash_amount" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.rep_cash_amount}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>AR# and DS#</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="cash_check_form.rep_ar_ds" placeholder="ar and ds"
                                        style="width: 100%">
                                        <template #prefix>
                                            <MoneyCollectOutlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="AR and DS">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <div v-if="cash_check_form.errors.rep_ar_ds" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.rep_ar_ds}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Penalty</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="cash_check_form.rep_check_penalty"
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
                                    <div v-if="cash_check_form.errors.rep_check_penalty" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.rep_check_penalty}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Approving Officer</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search approving officer"
                                        :default-active-first-option="false"
                                        v-model:value="cash_check_form.approvingOfficer" style="width: 100%"
                                        :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="appoffOption"
                                        @search="handleSearchEmployee">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>
                                    <div v-if="cash_check_form.errors.approvingOfficer" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.approvingOfficer}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Replacement date</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker style="width: 100%;" v-model:value="cash_check_form.rep_date">
                                    </a-date-picker>
                                    <div v-if="cash_check_form.errors.rep_date" class="text-white mb-5"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.rep_date}}</div>
                                </a-col>
                                <a-col :span="12">
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check From</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search check from"
                                        :default-active-first-option="false"
                                        v-model:value="cash_check_form.checkFrom_id" style="width: 100%"
                                        :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="checkOption"
                                        @search="handleSearchCheckFrom">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>
                                    <div v-if="cash_check_form.errors.checkFrom_id" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.checkFrom_id}}</div>

                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Bank Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search bank name"
                                        :default-active-first-option="false" v-model:value="cash_check_form.bank_id"
                                        style="width: 100%" :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="bankOption"
                                        @search="handleSearchBank">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>
                                    <div v-if="cash_check_form.errors.bank_id" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.bank_id}}</div>


                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Customer Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search customer name"
                                        :default-active-first-option="false" v-model:value="cash_check_form.customerId"
                                        style="width: 100%" :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="customerOption"
                                        @search="handleSearchCustomer">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>
                                    <div v-if="cash_check_form.errors.customerId" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.customerId
                                        }}</div>


                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Currency</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select placeholder="Select Currency" v-model:value="cash_check_form.currency_id"
                                        style="width: 100%">

                                        <a-select-option v-for="(item , key) in currency"
                                            v-model:value="item.currency_id">{{
                                            item.currency_name
                                            }}</a-select-option>


                                    </a-select>
                                    <div v-if="cash_check_form.errors.currency_id" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.currency_id}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Category</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select ref="select" placeholder="Select category"
                                        v-model:value="cash_check_form.category" style="width: 100%">
                                        <a-select-option v-for="(catItem , key) in category"
                                            v-model:value="catItem.check_category">{{
                                            catItem.check_category
                                            }}</a-select-option>

                                    </a-select>
                                    <div v-if="cash_check_form.errors.category" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.category}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check class</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select ref="select" placeholder="Select category"
                                        v-model:value="cash_check_form.checkClass" style="width: 100%">
                                        <a-select-option v-for="(chclass , key) in check_class"
                                            v-model:value="chclass.check_class">{{
                                            chclass.check_class
                                            }}</a-select-option>
                                    </a-select>
                                    <div v-if="cash_check_form.errors.checkClass" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.checkClass}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Reason for return</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-textarea v-model:value="cash_check_form.rep_reason"
                                        placeholder="Input text heres" :rows="4" />
                                    <div v-if="cash_check_form.errors.rep_reason" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        cash_check_form.errors.rep_reason}}</div>

                                    <a-row :gutter="[16, 16]" class="mt-4">
                                        <a-col :span="12">
                                            <a-button block class="mt-2" type="primary" danger @click="() => cash_check_form.reset(
                                                    'checkFrom_id',
                                                    'bank_id',
                                                    'customerId',
                                                    'approvingOfficer',
                                                    'currency_id',
                                                    'category',
                                                    'rep_reason',
                                                    'checkClass',
                                                    'rep_date',
                                                    'rep_check_date',
                                                    'rep_check_recieved',
                                                    'rep_check_penalty',
                                                    'accountname',
                                                    'accountnumber',
                                                    'checkNumber',
                                                    )">
                                                <template #icon>
                                                    <ClearOutlined />
                                                </template>
                                                Clear all inputs
                                            </a-button>
                                        </a-col>
                                        <a-col :span="12" class="mb-7">
                                            <a-button block class="mt-2" type="primary"
                                                @click="submitReplacementCashCheck"
                                                :loading="cash_check_form.processing">
                                                <template #icon>
                                                    <SaveOutlined />
                                                </template>
                                                {{ cash_check_form.processing
                                                ? 'Submitting cash check type...'
                                                : 'Submit cash check type'
                                                }}
                                            </a-button>
                                        </a-col>
                                    </a-row>
                                </a-col>
                            </a-row>
                        </template>
                        <template #extra v-else-if="partialPayCash">
                            <div style="width: 500px">
                                <p class="text-center font-bold">PARTIAL PAYMENTS CASH TYPE
                                </p>
                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Cash Amount</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-input v-model:value="partial_cash_form.rep_check_id" class="hidden">
                                </a-input>
                                <a-input v-model:value="partial_cash_form.rep_cash_amount" class="mb-2" readonly
                                    placeholder="Enter cash amount">
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
                                <a-input v-model:value="partial_cash_form.rep_cash_penalty" class="mb-2"
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
                                <div v-if="partial_cash_form.errors.rep_cash_penalty" class="text-white"
                                    style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                    {{
                                    partial_cash_form.errors.rep_cash_penalty }}
                                </div>
                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>AR # & DS #</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-input v-model:value="partial_cash_form.rep_ar_ds" class="mb-2"
                                    placeholder="Enter AR# and DS#">
                                    <template #prefix>
                                        <NumberOutlined />
                                    </template>
                                    <template #suffix>
                                        <a-tooltip title="Enter AR and DS # here">
                                            <InfoCircleOutlined />
                                        </a-tooltip>
                                    </template>
                                </a-input>
                                <div v-if="partial_cash_form.errors.rep_ar_ds" class="text-white"
                                    style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                    {{
                                    partial_cash_form.errors.rep_ar_ds }}
                                </div>
                                <a-textarea v-model:value="partial_cash_form.rep_reason"
                                    placeholder="Your reason of replace" :rows="3" />
                                <div v-if="partial_cash_form.errors.rep_reason" class="text-white"
                                    style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                    {{
                                    partial_cash_form.errors.rep_reason }}
                                </div>
                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Replacement date</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-date-picker style="width: 100%;" v-model:value="partial_cash_form.rep_date">
                                </a-date-picker>
                                <div v-if="partial_cash_form.errors.rep_date" class="text-white"
                                    style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                    {{
                                    partial_cash_form.errors.rep_date }}
                                </div>
                                <a-row :gutter="[16, 16]">

                                    <a-col :span="12">
                                        <a-button block class="mb-10 mt-5"
                                            @click="() => partial_cash_form.reset('rep_cash_penalty', 'rep_ar_ds', 'repDatePicker', 'rep_date', 'rep_reason')"
                                            type="primary" danger>
                                            <template #icon>
                                                <ClearOutlined />
                                            </template>
                                            Clear all inputs
                                        </a-button>
                                    </a-col>
                                    <a-col :span="12">
                                        <a-button block type="primary" @click="submitReplacementCashPartial"
                                            class="mt-5" :loading="partial_cash_form.processing">
                                            <template #icon>
                                                <SaveOutlined />
                                            </template>
                                            {{ partial_cash_form.processing ?
                                            "Submitting... " : "Submit partial cash type" }}
                                        </a-button>
                                    </a-col>

                                </a-row>
                            </div>
                        </template>
                        <template #extra v-else-if="partialPayCheck">
                            <p class="text-center font-bold py-5"> PARTIAL PAYMENT CHECK
                            </p>
                            <a-row :gutter="[16, 16]">
                                <a-col :span="12" style="width: 600px">
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Account Number</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input class="hidden" v-model:value="partial_check_form.rep_check_id">
                                    </a-input>
                                    <a-input v-model:value="partial_check_form.accountnumber"
                                        placeholder="Enter Account Number" style="width: 100%">
                                        <template #prefix>
                                            <NumberOutlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Account Number here">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <div v-if="partial_check_form.errors.accountnumber" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        partial_check_form.errors.accountnumber }}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Account Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search acount name"
                                        :default-active-first-option="false"
                                        v-model:value="partial_check_form.accountname" style="width: 100%"
                                        :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="accountOption"
                                        @search="handleSearchAccountName">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>
                                    <div v-if="partial_check_form.errors.accountname   " class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        partial_check_form.errors.accountname }}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Number</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="partial_check_form.checkNumber"
                                        placeholder="Enter Check number" style="width: 100%">
                                        <template #prefix>
                                            <NumberOutlined />
                                        </template>
                                        <template #suffix>
                                            <a-tooltip title="Check number here">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                    <div v-if="partial_check_form.errors.checkNumber   " class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        partial_check_form.errors.checkNumber }}</div>

                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Date</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker v-model:value="partial_check_form.rep_check_date"
                                        style="width: 100%">
                                    </a-date-picker>
                                    <div v-if="partial_check_form.errors.rep_check_date   " class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        partial_check_form.errors.rep_check_date }}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Received</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker v-model:value="partial_check_form.rep_check_received"
                                        style="width: 100%">
                                    </a-date-picker>
                                    <div v-if="partial_check_form.errors.rep_check_received" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        partial_check_form.errors.rep_check_received}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Amount</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-input v-model:value="partial_check_form.rep_check_amount"
                                        placeholder="Basic usage" readonly style="width: 100%">
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
                                    <a-input v-model:value="partial_check_form.rep_check_penalty"
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
                                    <div v-if="partial_check_form.errors.rep_check_penalty" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        partial_check_form.errors.rep_check_penalty}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Approving Officer</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search approving officer"
                                        :default-active-first-option="false"
                                        v-model:value="partial_check_form.approvingOfficer" style="width: 100%"
                                        :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="appoffOption"
                                        @search="handleSearchEmployee">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>
                                    <div v-if="partial_check_form.errors.approvingOfficer" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        partial_check_form.errors.approvingOfficer}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Replacement date</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-date-picker style="width: 100%;" v-model:value="partial_check_form.rep_date">
                                    </a-date-picker>
                                    <div v-if="partial_check_form.errors.rep_date" class="text-white mb-5"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        partial_check_form.errors.rep_date}}</div>
                                </a-col>
                                <a-col :span="12">
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check From</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search check from"
                                        :default-active-first-option="false"
                                        v-model:value="partial_check_form.checkFrom_id" style="width: 100%"
                                        :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="checkOption"
                                        @search="handleSearchCheckFrom">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>
                                    <div v-if="partial_check_form.errors.checkFrom_id" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        partial_check_form.errors.checkFrom_id}}</div>

                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Bank Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search bank name"
                                        :default-active-first-option="false" v-model:value="partial_check_form.bank_id"
                                        style="width: 100%" :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="bankOption"
                                        @search="handleSearchBank">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>
                                    <div v-if="partial_check_form.errors.bank_id" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        partial_check_form.errors.bank_id}}</div>


                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Customer Name</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select show-search placeholder="Search customer name"
                                        :default-active-first-option="false"
                                        v-model:value="partial_check_form.customerId" style="width: 100%"
                                        :show-arrow="false" :filter-option="false"
                                        :not-found-content="isRetrieving ? undefined : null" :options="customerOption"
                                        @search="handleSearchCustomer">
                                        <template v-if="isRetrieving" #notFoundContent>
                                            <a-spin size="small" />
                                        </template>
                                    </a-select>
                                    <div v-if="partial_check_form.errors.customerId" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        partial_check_form.errors.customerId
                                        }}</div>


                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Currency</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select placeholder="Select Currency"
                                        v-model:value="partial_check_form.currency_id" style="width: 100%">

                                        <a-select-option v-for="(item , key) in currency"
                                            v-model:value="item.currency_id">{{
                                            item.currency_name
                                            }}</a-select-option>


                                    </a-select>
                                    <div v-if="partial_check_form.errors.currency_id" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        partial_check_form.errors.currency_id}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check Category</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select ref="select" placeholder="Select category"
                                        v-model:value="partial_check_form.category" style="width: 100%">
                                        <a-select-option v-for="(catItem , key) in category"
                                            v-model:value="catItem.check_category">{{
                                            catItem.check_category
                                            }}</a-select-option>

                                    </a-select>
                                    <div v-if="partial_check_form.errors.category" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        partial_check_form.errors.category}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Check class</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-select ref="select" placeholder="Select category"
                                        v-model:value="partial_check_form.checkClass" style="width: 100%">
                                        <a-select-option v-for="(chclass , key) in check_class"
                                            v-model:value="chclass.check_class">{{
                                            chclass.check_class
                                            }}</a-select-option>
                                    </a-select>
                                    <div v-if="partial_check_form.errors.checkClass" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        partial_check_form.errors.checkClass}}</div>
                                    <a-breadcrumb class="mt-2 ml-1">
                                        <a-breadcrumb-item>Reason for return</a-breadcrumb-item>
                                    </a-breadcrumb>
                                    <a-textarea v-model:value="partial_check_form.rep_reason"
                                        placeholder="Input text heres" :rows="4" />
                                    <div v-if="partial_check_form.errors.rep_reason" class="text-white"
                                        style="font-size: 12px; border: 1px solid #FF5C58; border-radius: 5px; background: rgba(255, 99, 71, 0.6);">
                                        {{
                                        partial_check_form.errors.rep_reason}}</div>

                                    <a-row :gutter="[16, 16]" class="mt-4">
                                        <a-col :span="12">
                                            <a-button block class="mt-2" type="primary" danger @click="() => partial_check_form.reset(
                                                    'checkFrom_id',
                                                    'bank_id',
                                                    'customerId',
                                                    'approvingOfficer',
                                                    'currency_id',
                                                    'category',
                                                    'rep_reason',
                                                    'checkClass',
                                                    'rep_date',
                                                    'rep_check_date',
                                                    'rep_check_recieved',
                                                    'rep_check_penalty',
                                                    'accountname',
                                                    'accountnumber',
                                                    'checkNumber',
                                                    )">
                                                <template #icon>
                                                    <ClearOutlined />
                                                </template>
                                                Clear all inputs
                                            </a-button>
                                        </a-col>
                                        <a-col :span="12" class="mb-7">
                                            <a-button block class="mt-2" type="primary"
                                                @click="submitReplacementCheckPartial"
                                                :loading="partial_check_form.processing">
                                                <template #icon>
                                                    <SaveOutlined />
                                                </template>
                                                {{ partial_check_form.processing
                                                ? 'Submitting check type...'
                                                : 'Submit check type'
                                                }}
                                            </a-button>
                                        </a-col>
                                    </a-row>
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
import Pagination from "@/Components/Pagination.vue";
import { useForm } from '@inertiajs/vue3';
import dayjs from 'dayjs';
export default {
    props: {
        data: Array,
        columns: Array,
        pagination: Array,
    },
    data() {
        return {
            openModalReplace: false,
            isActive: null,
            openDetails: false,
            checkShow: false,
            cashShow: false,
            cashCheckShow: false,
            partialPayCheck: false,
            partialPayCash: false,
            defaultShow: true,
            selectDataDetails: {},
            cash_form: useForm({
                rep_bounce_id: '',
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
                customerId: null,
                approvingOfficer: null,
                currency_id: null,
                category: null,
                rep_reason: null,
                checkClass: null,
                rep_date: '',
                rep_check_date: '',
                rep_check_received: '',
                rep_check_penalty: '',
                rep_check_amount: '',
                accountname: null,
                accountnumber: '',
                checkNumber: '',
            }),
            cash_check_form: useForm({
                rep_check_id: '',
                checkFrom_id: null,
                bank_id: null,
                customerId: null,
                approvingOfficer: null,
                currency_id: null,
                category: null,
                rep_reason: null,
                checkClass: null,
                rep_date: '',
                rep_check_date: '',
                rep_check_received: '',
                rep_check_penalty: '',
                rep_check_amount: '',
                rep_cash_amount: '',
                rep_ar_ds: '',
                accountname: null,
                accountnumber: '',
                checkNumber: '',
            }),
            partial_cash_form: useForm({
                rep_check_id: '',
                rep_cash_amount: '',
                rep_cash_penalty: '',
                rep_ar_ds: '',
                rep_reason: '',
                rep_date: '',
            }),

            partial_check_form: useForm({
                rep_check_id: '',
                checkFrom_id: null,
                bank_id: null,
                customerId: null,
                approvingOfficer: null,
                currency_id: null,
                category: null,
                rep_reason: null,
                checkClass: null,
                rep_date: '',
                rep_check_date: '',
                rep_check_received: '',
                rep_check_penalty: '',
                rep_check_amount: '',
                accountname: null,
                accountnumber: '',
                checkNumber: '',
            }),
        }
    },
    methods: {
        openUpDetailsFolder(dataIn) {
            this.selectDataDetails = dataIn;
            this.cash_form.rep_check_id = dataIn.checks_id;
            this.cash_form.rep_bounce_id = dataIn.bounceId;
            this.check_form.rep_check_id = dataIn.checks_id;
            this.cash_form.rep_cash_amount = dataIn.check_amount;
            this.check_form.rep_check_amount = dataIn.check_amount;
            this.cash_check_form.rep_check_id = dataIn.checks_id;
            this.cash_check_form.rep_check_amount = dataIn.check_amount;
            this.partial_cash_form.rep_cash_amount = dataIn.check_amount;
            this.partial_cash_form.rep_check_id = dataIn.checks_id;
            this.partial_check_form.rep_check_amount = dataIn.check_amount;
            this.partial_check_form.rep_check_id = dataIn.checks_id;
            this.openModalReplace = true;
        },
        openUpDetails(dataIn) {
            console.log(dataIn);
            this.openDetails = true;
        },
        submit_cash_replacement() {
            this.cash_form.transform((data) => ({
                ...data,
                rep_date: dayjs(data.rep_date).format('YYYY-MM-DD')
            })).
                post(route('bounceCash.replace'), {
                    onSuccess: () => {
                        this.cash_form.reset();
                        this.openModalReplace = false;
                        message.success({
                            content: "Successfully replaced the cash!",
                            duration: 3,
                        });
                    }
                })
        },
        checkButtonType() {
            this.isActive = 'check';
            this.checkShow = true;
            this.defaultShow = false;
            this.cashShow = false;
            this.cashChecShow = false;
            this.partialPayCheck = false;
            this.partialPayCash = false;
        },
        cashButtonType() {
            this.isActive = 'cash';
            this.checkShow = false;
            this.defaultShow = false;
            this.cashShow = true;
            this.cashChecShow = false;
            this.partialPayCheck = false;
            this.partialPayCash = false;
        },
        cashCheckButtonType() {
            this.isActive = 'check&cash';
            this.checkShow = false;
            this.defaultShow = false;
            this.cashShow = false;
            this.cashCheckShow = true;
            this.partialPayCheck = false;
            this.partialPayCash = false;
        },
        partialPayCashButtton() {
            this.isActive = 'partialpaycash';
            this.checkShow = false;
            this.defaultShow = false;
            this.cashShow = false;
            this.cashCheckShow = false;
            this.partialPayCheck = false;
            this.partialPayCash = true;
        },
        partialPayCheckButtton() {
            this.isActive = 'partialpaycheck';
            this.checkShow = false;
            this.defaultShow = false;
            this.cashShow = false;
            this.cashCheckShow = false;
            this.partialPayCheck = true;
            this.partialPayCash = false;
        },
    }
}
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

td {
    border: 1px solid rgb(172, 172, 172);
    padding: 7px;
    text-align: left;
    /* border-radius: 10px; */
}

table {
    width: 100%;
}

th {
    border: 1px solid rgb(189, 189, 189);
    padding: 7px;
    text-align: left;
    width: 50%
        /* border-radius: 10px; */
}

.thh {
    background-color: #d3d3d3;
}

.active {
    background-color: rgb(10, 79, 143);
    color: white;

}
</style>