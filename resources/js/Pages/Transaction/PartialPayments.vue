<template>

    <Head title="Partial Payments" />

    <div class="py-0">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <a-breadcrumb class="mt-2 mb-3">
                <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                <a-breadcrumb-item><a href="">Transaction </a></a-breadcrumb-item>
                <a-breadcrumb-item>Partial Payments</a-breadcrumb-item>
            </a-breadcrumb>
            <a-card>
                <div class="flex justify-end">
                    <a-input-search v-model:value="query.search" style="width: 350px;" class="mb-5"
                        placeholder="Search Checks" :loading="isFetching" />
                </div>
                <a-table :data-source="data.data" :pagination="false" :columns="columns" size="small" bordered>
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'action'">
                            <a-button size="small" class="mx-1" @click="openUpDetails(record)">
                                <template #icon>
                                    <SettingOutlined />
                                </template>
                            </a-button>
                            <a-button size="small" class="mx-1" @click="partialPaymentNotNull(record)"
                                v-if="record.bounce_id != 0">
                                <template #icon>
                                    <IdcardOutlined />
                                </template>
                            </a-button>
                            <a-button size="small" class="mx-1" @click="partialPaymentNull(record)" v-else>
                                <template #icon>
                                    <IdcardFilled />
                                </template>
                            </a-button>
                            <a-button v-if="record.bounce_id != 0" size="small" class="mx-1"
                                @click="partialPaymentCheckNotNull(record)">
                                <template #icon>
                                    <CreditCardFilled />
                                </template>
                            </a-button>
                            <a-button v-else size="small" class="mx-1" @click="partialPaymentCheckNull(record)">
                                <template #icon>
                                    <CreditCardFilled />
                                </template>
                            </a-button>
                        </template>
                    </template>
                </a-table>
                <pagination class="mt-6" :datarecords="data"></pagination>
            </a-card>
        </div>
    </div>
    <a-modal v-model:open="openModalPPayment" title="Partial payment modal" width="100%" style="top: 10px"
        :footer="null" wrap-class-name="full-modal" :afterClose="afterCloseModal">
        <a-row :gutter="[16, 16]">
            <a-col :span="8">
                <a-card style="background-color: #f5f7f8">
                    <a-card>
                        <div class="flex justify-between">
                            <p class="text-gray-600 font-bold">
                                Bounce check amout:
                            </p>
                            <p class="font-bold underline text-blue-600">
                                {{ selectedPartialData.check_amount }}
                            </p>
                        </div>
                        <div class="flex justify-between mt-2">
                            <p class="text-gray-600 font-bold">
                                Bounce check balance:
                            </p>
                            <p class="font-bold underline text-blue-600">
                                {{ grandTotal.toLocaleString() }}
                            </p>
                        </div>
                    </a-card>
                    <a-card class="mt-1">
                        <h4 class="text-center mb-9">
                            Please fill the input fields
                        </h4>
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <DollarOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Cash amount</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-input v-model:value="partial_pay_form.checkAmount" placeholder="input with clear icon"
                            allow-clear />
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <DollarOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Penalty</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-input v-model:value="partial_pay_form.parPenalty" placeholder="input with clear icon"
                            allow-clear />
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <NumberOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Ar# and Ds#</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-input v-model:value="partial_pay_form.parArDs" placeholder="input with clear icon"
                            allow-clear />
                        <div v-if="partial_pay_form.errors.parArDs" class="text-white" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                            {{ partial_pay_form.errors.parArDs }}
                        </div>
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <CalendarOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Replacement Date</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-date-picker v-model:value="partial_pay_form.parRepDate" style="width: 100%" />
                        <div v-if="partial_pay_form.errors.parRepDate" class="text-white" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                            {{ partial_pay_form.errors.parRepDate }}
                        </div>
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <home-outlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Reason of replace</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-textarea v-model:value="partial_pay_form.parReason" placeholder="Enter here" :rows="4" />
                        <div v-if="partial_pay_form.errors.parReason" class="text-white" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                            {{ partial_pay_form.errors.parReason }}
                        </div>
                        <div class="flex justify-between mt-5">
                            <a-button style="width: 100%; background-color: gainsboro" @click="() =>
                                partial_pay_form.reset(
                                    'parArDs',
                                    'parReason',
                                    'parRepDate'
                                )
                                " class="mr-2">
                                <template #icon>
                                    <ClearOutlined />
                                </template>
                                clear input
                            </a-button>
                            <a-button style="width: 100%" type="primary" @click="saveChangesPartialPayment"
                                :loading="partial_pay_form.processing">
                                <template #icon>
                                    <SaveOutlined></SaveOutlined>
                                </template>
                                {{
                                    partial_pay_form.processing
                                        ? "Submitting checks..."
                                        : "Save changes"
                                }}
                            </a-button>
                        </div>
                    </a-card>
                </a-card>
            </a-col>
            <a-col :span="16">
                <a-card style="background-color: #f5f7f8">
                    <h4 class="text-center mb-10">Payment history List</h4>
                    <a-table bordered size="small" :data-source="selectDataDetails" :columns="payParColumns">
                    </a-table>
                </a-card>
            </a-col>
        </a-row>
    </a-modal>
    <a-modal v-model:open="openModalPPaymentCheck" title="Partial payment modal" width="100%" style="top: 10px"
        :footer="null" wrap-class-name="full-modal" :afterClose="afterCloseModal">
        <a-row :gutter="[16, 16]">
            <a-col :span="8">
                <a-card style="background-color: #f5f7f8">
                    <a-card>
                        <div class="flex justify-between">
                            <p class="text-gray-600 font-bold">
                                Bounce check amout:
                            </p>
                            <p class="font-bold underline text-blue-600">
                                {{ selectedPartialData.check_amount }}
                            </p>
                        </div>
                        <div class="flex justify-between mt-2">
                            <p class="text-gray-600 font-bold">
                                Bounce check balance:
                            </p>
                            <p class="font-bold underline text-blue-600">
                                {{ grandTotal.toLocaleString() }}
                            </p>
                        </div>
                    </a-card>
                    <a-card class="mt-1">
                        <h4 class="text-center mb-9">
                            Please fill the input fields
                        </h4>
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <DollarOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Cash amount</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-input v-model:value="par_pay_check_form.rep_check_amount" placeholder="input with clear icon"
                            allow-clear />
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <DollarOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Penalty</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-input v-model:value="par_pay_check_form.rep_check_penalty"
                            placeholder="input with clear icon" allow-clear />
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <NumberOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Ar# and Ds#</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-input v-model:value="par_pay_check_form.rep_ar_ds" placeholder="input with clear icon"
                            allow-clear />
                        <div v-if="par_pay_check_form.errors.rep_ar_ds" class="text-white" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                            {{ par_pay_check_form.errors.rep_ar_ds }}
                        </div>

                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <CalendarOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Replacement Date</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-date-picker v-model:value="par_pay_check_form.rep_date" style="width: 100%" />
                        <div v-if="par_pay_check_form.errors.rep_date" class="text-white" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                            {{ par_pay_check_form.errors.rep_date }}
                        </div>
                        <a-breadcrumb class="mt-3">
                            <a-breadcrumb-item href="">
                                <home-outlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Reason of replace</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-textarea v-model:value="par_pay_check_form.rep_reason" placeholder="Enter here" :rows="4" />
                        <div v-if="par_pay_check_form.errors.rep_reason" class="text-white" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                            {{ par_pay_check_form.errors.rep_reason }}
                        </div>
                    </a-card>
                </a-card>
            </a-col>
            <a-col :span="16">
                <a-card style="background-color: #f5f7f8">
                    <a-collapse v-model:activeKey="activeKey" style="background-color: white">
                        <a-collapse-panel key="1" header="Click this to see the payment history list">
                            <a-table bordered size="small" :data-source="selectDataDetails" :columns="payParColumns">
                            </a-table>
                        </a-collapse-panel>
                    </a-collapse>
                </a-card>
                <a-card style="background-color: #f5f7f8" class="mt-1">
                    <a-card>
                        <a-row :gutter="[16, 16]">
                            <a-col :span="12" style="width: 600px">
                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Account Number</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-input class="hidden" v-model:value="par_pay_check_form.rep_check_id
                                    ">
                                </a-input>
                                <a-input v-model:value="par_pay_check_form.accountnumber
                                    " placeholder="Enter Account Number" style="width: 100%">
                                    <template #prefix>
                                        <NumberOutlined />
                                    </template>
                                    <template #suffix>
                                        <a-tooltip title="Account Number here">
                                            <info-circle-outlined style="
                                                    color: rgba(0, 0, 0, 0.45);
                                                " />
                                        </a-tooltip>
                                    </template>
                                </a-input>
                                <div v-if="
                                    par_pay_check_form.errors.accountnumber
                                " class="text-white" style="
                                        font-size: 12px;
                                        border: 1px solid #ff5c58;
                                        border-radius: 5px;
                                        background: rgba(255, 99, 71, 0.6);
                                    ">
                                    {{
                                        par_pay_check_form.errors.accountnumber
                                    }}
                                </div>
                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Account Name</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-select show-search placeholder="Search acount name"
                                    :default-active-first-option="false" v-model:value="par_pay_check_form.accountname
                                        " style="width: 100%" :show-arrow="false" :filter-option="false"
                                    :not-found-content="isRetrieving ? undefined : null
                                        " :options="accountOption" @search="handleSearchAccountName">
                                    <template v-if="isRetrieving" #notFoundContent>
                                        <a-spin size="small" />
                                    </template>
                                </a-select>
                                <div v-if="par_pay_check_form.errors.accountname" class="text-white" style="
                                        font-size: 12px;
                                        border: 1px solid #ff5c58;
                                        border-radius: 5px;
                                        background: rgba(255, 99, 71, 0.6);
                                    ">
                                    {{ par_pay_check_form.errors.accountname }}
                                </div>
                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Check Number</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-input v-model:value="par_pay_check_form.checkNumber
                                    " placeholder="Enter Check number" style="width: 100%">
                                    <template #prefix>
                                        <NumberOutlined />
                                    </template>
                                    <template #suffix>
                                        <a-tooltip title="Check number here">
                                            <info-circle-outlined style="
                                                    color: rgba(0, 0, 0, 0.45);
                                                " />
                                        </a-tooltip>
                                    </template>
                                </a-input>
                                <div v-if="par_pay_check_form.errors.checkNumber" class="text-white" style="
                                        font-size: 12px;
                                        border: 1px solid #ff5c58;
                                        border-radius: 5px;
                                        background: rgba(255, 99, 71, 0.6);
                                    ">
                                    {{ par_pay_check_form.errors.checkNumber }}
                                </div>

                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Check Date</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-date-picker v-model:value="par_pay_check_form.rep_check_date
                                    " style="width: 100%">
                                </a-date-picker>
                                <div v-if="
                                    par_pay_check_form.errors.rep_check_date
                                " class="text-white" style="
                                        font-size: 12px;
                                        border: 1px solid #ff5c58;
                                        border-radius: 5px;
                                        background: rgba(255, 99, 71, 0.6);
                                    ">
                                    {{
                                        par_pay_check_form.errors.rep_check_date
                                    }}
                                </div>
                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Check Received</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-date-picker v-model:value="par_pay_check_form.rep_check_received
                                    " style="width: 100%">
                                </a-date-picker>
                                <div v-if="
                                    par_pay_check_form.errors
                                        .rep_check_received
                                " class="text-white" style="
                                        font-size: 12px;
                                        border: 1px solid #ff5c58;
                                        border-radius: 5px;
                                        background: rgba(255, 99, 71, 0.6);
                                    ">
                                    {{
                                        par_pay_check_form.errors
                                            .rep_check_received
                                    }}
                                </div>
                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Check Amount</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-input v-model:value="par_pay_check_form.checkAmount
                                    " placeholder="Basic usage" style="width: 100%">
                                    <template #prefix>
                                        <MoneyCollectOutlined />
                                    </template>
                                    <template #suffix>
                                        <a-tooltip title="Check Amount">
                                            <info-circle-outlined style="
                                                    color: rgba(0, 0, 0, 0.45);
                                                " />
                                        </a-tooltip>
                                    </template>
                                </a-input>
                                <div v-if="par_pay_check_form.errors.checkAmount" class="text-white" style="
                                        font-size: 12px;
                                        border: 1px solid #ff5c58;
                                        border-radius: 5px;
                                        background: rgba(255, 99, 71, 0.6);
                                    ">
                                    {{ par_pay_check_form.errors.checkAmount }}
                                </div>
                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Approving Officer</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-select show-search placeholder="Search approving officer"
                                    :default-active-first-option="false" v-model:value="par_pay_check_form.approvingOfficer
                                        " style="width: 100%" :show-arrow="false" :filter-option="false"
                                    :not-found-content="isRetrieving ? undefined : null
                                        " :options="appoffOption" @search="handleSearchEmployee">
                                    <template v-if="isRetrieving" #notFoundContent>
                                        <a-spin size="small" />
                                    </template>
                                </a-select>
                                <div v-if="
                                    par_pay_check_form.errors
                                        .approvingOfficer
                                " class="text-white" style="
                                        font-size: 12px;
                                        border: 1px solid #ff5c58;
                                        border-radius: 5px;
                                        background: rgba(255, 99, 71, 0.6);
                                    ">
                                    {{
                                        par_pay_check_form.errors
                                            .approvingOfficer
                                    }}
                                </div>
                            </a-col>
                            <a-col :span="12">
                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Check From</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-select show-search placeholder="Search check from"
                                    :default-active-first-option="false" v-model:value="par_pay_check_form.checkFrom_id
                                        " style="width: 100%" :show-arrow="false" :filter-option="false"
                                    :not-found-content="isRetrieving ? undefined : null
                                        " :options="checkOption" @search="handleSearchCheckFrom">
                                    <template v-if="isRetrieving" #notFoundContent>
                                        <a-spin size="small" />
                                    </template>
                                </a-select>
                                <div v-if="
                                    par_pay_check_form.errors.checkFrom_id
                                " class="text-white" style="
                                        font-size: 12px;
                                        border: 1px solid #ff5c58;
                                        border-radius: 5px;
                                        background: rgba(255, 99, 71, 0.6);
                                    ">
                                    {{ par_pay_check_form.errors.checkFrom_id }}
                                </div>

                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Bank Name</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-select show-search placeholder="Search bank name"
                                    :default-active-first-option="false" v-model:value="par_pay_check_form.bank_id"
                                    style="width: 100%" :show-arrow="false" :filter-option="false" :not-found-content="isRetrieving ? undefined : null
                                        " :options="bankOption" @search="handleSearchBank">
                                    <template v-if="isRetrieving" #notFoundContent>
                                        <a-spin size="small" />
                                    </template>
                                </a-select>
                                <div v-if="par_pay_check_form.errors.bank_id" class="text-white" style="
                                        font-size: 12px;
                                        border: 1px solid #ff5c58;
                                        border-radius: 5px;
                                        background: rgba(255, 99, 71, 0.6);
                                    ">
                                    {{ par_pay_check_form.errors.bank_id }}
                                </div>

                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Customer Name</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-select show-search placeholder="Search customer name"
                                    :default-active-first-option="false" v-model:value="par_pay_check_form.customerId
                                        " style="width: 100%" :show-arrow="false" :filter-option="false"
                                    :not-found-content="isRetrieving ? undefined : null
                                        " :options="customerOption" @search="handleSearchCustomer">
                                    <template v-if="isRetrieving" #notFoundContent>
                                        <a-spin size="small" />
                                    </template>
                                </a-select>
                                <div v-if="par_pay_check_form.errors.customerId" class="text-white" style="
                                        font-size: 12px;
                                        border: 1px solid #ff5c58;
                                        border-radius: 5px;
                                        background: rgba(255, 99, 71, 0.6);
                                    ">
                                    {{ par_pay_check_form.errors.customerId }}
                                </div>

                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Currency</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-select placeholder="Select Currency" v-model:value="par_pay_check_form.currency_id
                                    " style="width: 100%">
                                    <a-select-option v-for="(item, key) in currency" v-model:value="item.currency_id">{{
                                        item.currency_name
                                        }}</a-select-option>
                                </a-select>
                                <div v-if="par_pay_check_form.errors.currency_id" class="text-white" style="
                                        font-size: 12px;
                                        border: 1px solid #ff5c58;
                                        border-radius: 5px;
                                        background: rgba(255, 99, 71, 0.6);
                                    ">
                                    {{ par_pay_check_form.errors.currency_id }}
                                </div>
                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Check Category</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-select ref="select" placeholder="Select category"
                                    v-model:value="par_pay_check_form.category" style="width: 100%">
                                    <a-select-option v-for="(catItem, key) in category"
                                        v-model:value="catItem.check_category">{{
                                            catItem.check_category
                                        }}</a-select-option>
                                </a-select>
                                <div v-if="par_pay_check_form.errors.category" class="text-white" style="
                                        font-size: 12px;
                                        border: 1px solid #ff5c58;
                                        border-radius: 5px;
                                        background: rgba(255, 99, 71, 0.6);
                                    ">
                                    {{ par_pay_check_form.errors.category }}
                                </div>
                                <a-breadcrumb class="mt-2 ml-1">
                                    <a-breadcrumb-item>Check class</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-select ref="select" placeholder="Select category" v-model:value="par_pay_check_form.checkClass
                                    " style="width: 100%">
                                    <a-select-option v-for="(chclass, key) in check_class"
                                        v-model:value="chclass.check_class">{{
                                            chclass.check_class
                                        }}</a-select-option>
                                </a-select>
                                <div v-if="par_pay_check_form.errors.checkClass" class="text-white" style="
                                        font-size: 12px;
                                        border: 1px solid #ff5c58;
                                        border-radius: 5px;
                                        background: rgba(255, 99, 71, 0.6);
                                    ">
                                    {{ par_pay_check_form.errors.checkClass }}
                                </div>
                                <a-row :gutter="[16, 16]" class="mt-4">
                                    <a-col :span="12">
                                        <a-button block class="mt-2" type="primary" danger @click="() =>
                                            par_pay_check_form.reset(
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
                                                'accountname',
                                                'accountnumber',
                                                'checkNumber'
                                            )
                                            ">
                                            <template #icon>
                                                <ClearOutlined />
                                            </template>
                                            Clear all inputs
                                        </a-button>
                                    </a-col>
                                    <a-col :span="12" class="mb-7">
                                        <a-button block class="mt-2" type="primary" @click="saveChangesPartialCheck"
                                            :loading="par_pay_check_form.processing
                                                ">
                                            <template #icon>
                                                <SaveOutlined />
                                            </template>
                                            {{
                                                par_pay_check_form.processing
                                                    ? "Submitting check type..."
                                                    : "Submit check type"
                                            }}
                                        </a-button>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>

                        <a-card>
                            <InfoCircleTwoTone />
                            If check amount exceeds balance please select pdc or
                            bounced check for partial continutaion.

                            <a-row :gutter="[16, 16]" class="mt-4">
                                <a-col :span="8">
                                    <a-select v-model:value="par_pay_check_form.conType
                                        " :disabled="!isValid()" ref="select" placeholder="Choose a type "
                                        style="width: 100%" @change="handleChangeCheckType">
                                        <a-select-option value="1">Bounce check type</a-select-option>
                                        <a-select-option value="2">Redeem check type</a-select-option>
                                    </a-select>
                                </a-col>
                                <a-col :span="16" class="mb-7">
                                    <a-select placeholder="Please choose a check type" v-model:value="par_pay_check_form.conCheckPartial
                                        " style="width: 100%" :disabled="dataCheckType.length <= 0">
                                        <a-select-option v-for="(data, key) in dataCheckType" :key="key"
                                            v-model:value="data.checkCred">{{ data.customer }} - Check amount:
                                            {{ data.amount }}</a-select-option></a-select></a-col>
                            </a-row>
                        </a-card>
                    </a-card>
                </a-card>
            </a-col>
        </a-row>
    </a-modal>

    <a-modal v-model:open="openDetails" style="top: 25px" width="1000px" title="Details" @ok="handleOk"
        :ok-button-props="{ hidden: true }" :cancel-button-props="{ hidden: true }" :footer="null">
        <div class="product-table">
            <table class="min-w-full divide-y divide-gray-200">
                <tbody>
                    <tr>
                        <td
                            class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-t border-gray-200">
                            Customer Name
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-t border-gray-200">
                            {{ selectDataDetails.fullname }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Check From
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.department }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Check Number
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_no }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Approving Officer
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.approving_officer }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Check Class
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_class }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Check Status
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_status }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Check Date
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_date }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Account No
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.account_no }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Check Received
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_received }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Account Name
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.account_name }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Received As
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_type }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Bank Name
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.bankbranchname }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                            Check Category
                        </td>
                        <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                            {{ selectDataDetails.check_category }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
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
</template>

<script>
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import axios from "axios";
import { useForm } from "@inertiajs/vue3";
import dayjs from "dayjs";
import { message } from "ant-design-vue";
import debounce from "lodash/debounce";
export default {
    layout: TreasuryLayout,
    props: {
        data: Object,
        columns: Array,
        currency: Object,
        check_class: Object,
        category: Object,
    },
    data() {
        return {
            query: {
                start: '',
            },
            isFetching: false,
            openModalPPayment: false,
            openModalPPaymentCheck: false,
            selectDataDetails: {},
            selectedPartialData: {},
            openDetails: false,
            grandTotal: 0,
            dataCheckType: [],
            days: 0,
            activeKey: null,
            allData: [],
            checkOption: [],
            bankOption: [],
            appoffOption: [],
            customerOption: [],
            accountOption: [],
            isRetrieving: false,
            payParColumns: [
                {
                    title: "No",
                    dataIndex: "count",
                    key: "name",
                },
                {
                    title: "Cash amount",
                    dataIndex: "cash",
                    key: "name",
                },
                {
                    title: "Check amount",
                    dataIndex: "cashAmountPaid",
                    key: "name",
                },
                {
                    title: "Penalty amount",
                    dataIndex: "penalty",
                    key: "name",
                },
                {
                    title: "Ar Ds",
                    dataIndex: "arDs",
                    key: "name",
                },
                {
                    title: "Reason",
                    dataIndex: "reason",
                    key: "name",
                },
                {
                    title: "Treasury",
                    dataIndex: "name",
                    key: "name",
                },
                {
                    title: "Date",
                    dataIndex: "dateTime",
                    key: "dateTime",
                },
            ],

            partial_pay_form: useForm({
                checksId: null,
                bouncedId: null,
                checkAmount: null,
                parPenalty: null,
                parArDs: null,
                parReason: null,
                parRepDate: "",
                checkAmountBalance: null,
            }),
            par_pay_check_form: useForm({
                conCheckPartial: null,
                conType: null,
                parCheckAmount: null,
                checkAmount: "",
                checksId: null,
                bouncedId: null,
                rep_check_id: "",
                checkFrom_id: null,
                bank_id: null,
                customerId: null,
                approvingOfficer: null,
                currency_id: null,
                category: null,
                rep_reason: null,
                checkClass: null,
                rep_date: "",
                rep_check_date: "",
                rep_check_received: "",
                rep_check_penalty: "",
                rep_check_amount: "",
                accountname: null,
                accountnumber: "",
                checkNumber: "",
                rep_ar_ds: null,
            }),
        };
    },

    methods: {
        openUpDetails(dataIn) {
            this.selectDataDetails = dataIn;
            this.openDetails = true;
        },
        isValid() {
            let numericValue = parseFloat(this.par_pay_check_form.checkAmount);
            let inputAmount = numericValue.toFixed(2);

            let currencyValue = this.selectedPartialData.check_amount;

            currencyValue = currencyValue.replace("₱", "");

            currencyValue = currencyValue.replace(/,/g, "");

            return currencyValue === inputAmount;
        },
        async partialPaymentNotNull(payParNotnull) {
            this.selectedPartialData = payParNotnull;
            this.partial_pay_form.checksId = payParNotnull.checks_id;
            this.partial_pay_form.bouncedId = payParNotnull.bounce_id;

            await axios
                .get(route("partialpaynotnull.partials"), {
                    params: {
                        checksId: payParNotnull.checks_id,
                        bouncedId: payParNotnull.bounce_id,
                    },
                })
                .then((response) => {
                    const { data, grandTotal, days } = response.data;

                    this.selectDataDetails = data;
                    this.grandTotal = grandTotal;
                    this.days = days;
                    this.openModalPPayment = true;

                    this.partial_pay_form.checkAmount = grandTotal;
                    this.partial_pay_form.checkAmountBalance = grandTotal;

                    if (this.partial_pay_form.bouncedId != 0) {
                        let grandTotalPar = grandTotal;
                        let penalty =
                            parseFloat(grandTotalPar) * parseFloat(0.02);
                        let monthlyPenalty =
                            parseFloat(grandTotalPar) *
                            parseFloat(0.02) *
                            (days / 30);
                        let newPenalty =
                            parseFloat(penalty) + parseFloat(monthlyPenalty);

                        this.partial_pay_form.parPenalty =
                            newPenalty.toFixed(2);
                    }
                });
        },

        async partialPaymentNull(parPayNull) {
            this.selectedPartialData = parPayNull;
            this.partial_pay_form.checksId = parPayNull.checks_id;

            await axios
                .get(route("partialpaynull.partials"), {
                    params: {
                        checksId: parPayNull.checks_id,
                    },
                })
                .then((response) => {
                    const { data, grandTotal, days } = response.data;

                    this.selectDataDetails = data;
                    this.grandTotal = grandTotal;
                    this.days = days;
                    this.openModalPPayment = true;

                    this.partial_pay_form.checkAmount = grandTotal;
                    this.partial_pay_form.checkAmountBalance = grandTotal;

                    if (days > 0) {
                        let grandTotalPar = grandTotal;
                        let penalty =
                            parseFloat(grandTotalPar) * parseFloat(0.02);
                        let monthlyPenalty =
                            parseFloat(grandTotalPar) *
                            parseFloat(0.02) *
                            (days / 30);
                        let newPenalty =
                            parseFloat(penalty) + parseFloat(monthlyPenalty);

                        this.partial_pay_form.parPenalty =
                            newPenalty.toFixed(2);
                    }
                });
        },

        saveChangesPartialPayment() {
            this.partial_pay_form
                .transform((data) => ({
                    ...data,
                    parRepDate: dayjs(data.parRepDate).format("YYYY-MM-DD"),
                }))
                .post(route("submit.partials"), {
                    onSuccess: () => {
                        this.openModalPPayment = false;
                        this.partial_pay_form.reset();
                        message.success({
                            content: "Successfully Save checks!",
                            duration: 3,
                        });
                    },
                });
        },
        saveChangesPartialCheck() {
            this.par_pay_check_form
                .transform((data) => ({
                    ...data,
                    rep_date: dayjs(data.rep_date).format("YYYY-MM-DD"),
                    rep_check_date: dayjs(data.rep_check_date).format(
                        "YYYY-MM-DD"
                    ),
                    rep_check_received: dayjs(data.rep_check_received).format(
                        "YYYY-MM-DD"
                    ),
                }))
                .post(route("submitcheck.partials"), {
                    onSuccess: () => {
                        this.openModalPPaymentCheck = false;
                        this.par_pay_check_form.reset();
                        message.success({
                            content: "Successfully Save checks!",
                            duration: 3,
                        });
                    },
                });
        },
        async partialPaymentCheckNotNull(parPayCheckNotNull) {
            this.selectedPartialData = parPayCheckNotNull;
            this.par_pay_check_form.checksId = parPayCheckNotNull.checks_id;
            this.par_pay_check_form.bouncedId = parPayCheckNotNull.bounce_id;
            this.par_pay_check_form.rep_check_id = parPayCheckNotNull.checks_id;
            this.par_pay_check_form.parCheckAmount =
                parPayCheckNotNull.check_amount;

            await axios
                .get(route("partialpaynotnull.partials"), {
                    params: {
                        checksId: parPayCheckNotNull.checks_id,
                        bouncedId: parPayCheckNotNull.bounce_id,
                    },
                })
                .then((response) => {
                    const { data, grandTotal, days } = response.data;

                    this.selectDataDetails = data;
                    this.grandTotal = grandTotal;
                    this.days = days;
                    this.openModalPPaymentCheck = true;

                    this.par_pay_check_form.rep_check_amount = grandTotal;
                    this.partial_pay_form.checkAmountBalance = grandTotal;

                    if (this.partial_pay_form.bouncedId != 0) {
                        let grandTotalPar = grandTotal;
                        let penalty =
                            parseFloat(grandTotalPar) * parseFloat(0.02);
                        let monthlyPenalty =
                            parseFloat(grandTotalPar) *
                            parseFloat(0.02) *
                            (days / 30);
                        let newPenalty =
                            parseFloat(penalty) + parseFloat(monthlyPenalty);

                        this.par_pay_check_form.rep_check_penalty =
                            newPenalty.toFixed(2);
                    }
                });
        },
        async partialPaymentCheckNull(parPayCheckNotNull) {
            this.selectedPartialData = parPayCheckNotNull;
            this.par_pay_check_form.checksId = parPayCheckNotNull.checks_id;
            this.par_pay_check_form.bouncedId = parPayCheckNotNull.bounce_id;
            this.par_pay_check_form.rep_check_id = parPayCheckNotNull.checks_id;
            this.par_pay_check_form.parCheckAmount =
                parPayCheckNotNull.check_amount;
            await axios
                .get(route("partialpaynull.partials"), {
                    params: {
                        checksId: parPayCheckNotNull.checks_id,
                        bouncedId: parPayCheckNotNull.bounce_id,
                    },
                })
                .then((response) => {
                    const { data, grandTotal, days } = response.data;

                    this.selectDataDetails = data;
                    this.grandTotal = grandTotal;
                    this.days = days;
                    this.openModalPPaymentCheck = true;

                    this.par_pay_check_form.rep_check_amount = grandTotal;
                    this.partial_pay_form.checkAmountBalance = grandTotal;

                    if (days > 0) {
                        let grandTotalPar = grandTotal;
                        let penalty =
                            parseFloat(grandTotalPar) * parseFloat(0.02);
                        let monthlyPenalty =
                            parseFloat(grandTotalPar) *
                            parseFloat(0.02) *
                            (days / 30);
                        let newPenalty =
                            parseFloat(penalty) + parseFloat(monthlyPenalty);

                        this.par_pay_check_form.rep_check_penalty =
                            newPenalty.toFixed(2);
                    }
                });
        },

        async handleChangeCheckType(value) {
            await axios
                .get(route("bounceCheckType.checks"), {
                    params: {
                        type: value,
                    },
                })
                .then((response) => {
                    const data = response.data;
                    this.dataCheckType = data;
                });
        },

        afterCloseModal() {
            this.partial_pay_form.reset();
        },

        handleSearchCheckFrom: debounce(async function (query) {
            try {
                if (query.trim().length) {
                    this.isRetrieving = true;
                    this.allData = [];
                    this.checkOption = [];
                    const { data } = await axios.get(
                        route("search.checkfrom", { search: query })
                    );
                    this.allData = data;
                    this.checkOption = this.allData.map((checkfrom) => ({
                        title: checkfrom.department,
                        value: checkfrom.department_id,
                        label: checkfrom.department,
                    }));
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                this.isRetrieving = false;
            }
        }, 1000),
        handleSearchBank: debounce(async function (query) {
            try {
                if (query.trim().length) {
                    this.isRetrieving = true;
                    this.allData = [];
                    this.bankOption = [];
                    const { data } = await axios.get(
                        route("search.bankName", { search: query })
                    );
                    this.allData = data;
                    this.bankOption = this.allData.map((bank) => ({
                        title: bank.bankbranchname,
                        value: bank.bank_id,
                        label: bank.bankbranchname,
                    }));
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                this.isRetrieving = false;
            }
        }, 1000),
        handleSearchCustomer: debounce(async function (query) {
            try {
                if (query.trim().length) {
                    this.isRetrieving = true;
                    this.allData = [];
                    this.customerOption = [];
                    const { data } = await axios.get(
                        route("search.customerName", { search: query })
                    );
                    this.allData = data;
                    this.customerOption = this.allData.map((customer) => ({
                        title: customer.fullname,
                        value: customer.customer_id,
                        label: customer.fullname,
                    }));
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                this.isRetrieving = false;
            }
        }, 1000),
        handleSearchAccountName: debounce(async function (query) {
            try {
                if (query.trim().length) {
                    this.isRetrieving = true;
                    this.allData = [];
                    this.accountOption = [];
                    const { data } = await axios.get(
                        route("search.customerName", { search: query })
                    );
                    this.allData = data;
                    this.accountOption = this.allData.map((account) => ({
                        title: account.fullname,
                        value: account.fullname,
                        label: account.fullname,
                    }));
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                this.isRetrieving = false;
            }
        }, 1000),
        handleSearchEmployee: debounce(async function (query) {
            try {
                if (query.trim().length) {
                    this.isRetrieving = true;
                    this.allData = [];
                    this.appoffOption = [];
                    const { data } = await axios.get(
                        route("search.employeeName", { search: query })
                    );
                    this.allData = data;
                    this.appoffOption = this.allData.map((employee) => ({
                        title: employee.name,
                        value: employee.name,
                        label: employee.name,
                    }));
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                this.isRetrieving = false;
            }
        }, 1000),
    },
    watch: {
        query: {
            deep: true,
            handler: debounce(async function () {
                this.isFetching = true,
                    this.$inertia.get(route("partial_payments.checks"), {
                        searchQuery: this.query.search,
                    }, {
                        preserveState: true,
                        onSuccess: () => {
                            this.isFetching = false;
                        },
                        onError: () => {
                            this.isFetching = false; // Ensure the flag is reset on error
                        }
                    }
                    );
            }, 600),
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
