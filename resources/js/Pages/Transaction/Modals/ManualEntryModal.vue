<template>
    <a-modal title="Add manual checks Modal" :footer="null" width="90%" style="top: 50px"
        :after-close="() => manual_check_form.clearErrors()" wrap-class-name="full-modal">
        <a-card>
            <a-row :gutter="[16, 16]">
                <a-col :span="8">
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <AccountBookOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Account Number</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="manual_check_form.accountnumber" placeholder="Enter here...">
                        <template #prefix> </template>

                        <template #suffix>
                            <a-tooltip title="Enter account number here">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <div v-if="manual_check_form.errors.accountnumber" class="text-white mb-3" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                        {{ manual_check_form.errors.accountnumber }}
                    </div>
                    <a-breadcrumb class="mt-3">
                        <a-breadcrumb-item href="">
                            <CalendarOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Check Date</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-date-picker v-model:value="manual_check_form.checkdate" style="width: 100%">
                    </a-date-picker>
                    <div v-if="manual_check_form.errors.checkdate" class="text-white mb-3" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                        {{ manual_check_form.errors.checkdate }}
                    </div>
                    <a-breadcrumb class="mt-3">
                        <a-breadcrumb-item href="">
                            <MoneyCollectOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Currency</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-select placeholder="Select Currency" v-model:value="manual_check_form.currency" style="width: 100%">
                        <a-select-option v-for="(item, key) in currency" v-model:value="item.currency_id">{{
                            item.currency_name
                            }}</a-select-option>
                    </a-select>
                    <div v-if="manual_check_form.errors.currency" class="text-white mb-3" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                        {{ manual_check_form.errors.currency }}
                    </div>
                    <a-breadcrumb class="mt-3">
                        <a-breadcrumb-item href="">
                            <BankOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Check From</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-select show-search placeholder="Search Check From" :default-active-first-option="false"
                        v-model:value="manual_check_form.checkfrom" style="width: 100%" :show-arrow="false" :filter-option="false"
                        :not-found-content="isRetrieving ? undefined : null" :options="checkOption"
                        @search="handleSearchCheckFrom">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                    <div v-if="manual_check_form.errors.checkfrom" class="text-white mb-3" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                        {{ manual_check_form.errors.checkfrom }}
                    </div>
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
                    <div v-if="manual_check_form.errors.accountname" class="text-white mb-3" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                        {{ manual_check_form.errors.accountname }}
                    </div>
                </a-col>
                <a-col :span="8">
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <UserOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Customer Name</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-select show-search placeholder="Search customer name" :default-active-first-option="false"
                        v-model:value="manual_check_form.customer" style="width: 100%" :show-arrow="false" :filter-option="false"
                        :not-found-content="isRetrieving ? undefined : null" :options="customerOption"
                        @search="handleSearchCustomer">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                    <div v-if="manual_check_form.errors.customer" class="text-white mb-3" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                        {{ manual_check_form.errors.customer }}
                    </div>

                    <a-breadcrumb class="mt-3">
                        <a-breadcrumb-item href="">
                            <MoneyCollectOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Check Amount</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="manual_check_form.checkamount" placeholder="Enter here...">
                        <template #prefix> </template>

                        <template #suffix>
                            <a-tooltip title="Enter check amount here...">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <div v-if="manual_check_form.errors.checkamount" class="text-white mb-3" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                        {{ manual_check_form.errors.checkamount }}
                    </div>
                    <a-breadcrumb class="mt-3">
                        <a-breadcrumb-item href="">
                            <BankOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Check Class</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-select placeholder="Select Currency" v-model:value="manual_check_form.checkclass" style="width: 100%">
                        <a-select-option v-for="(item, key) in checkClass" v-model:value="item.check_class">{{
                            item.check_class
                            }}</a-select-option>
                    </a-select>
                    <div v-if="manual_check_form.errors.checkclass" class="text-white mb-3" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                        {{ manual_check_form.errors.checkclass }}
                    </div>
                    <a-breadcrumb class="mt-3">
                        <a-breadcrumb-item href="">
                            <MoneyCollectOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Check Number</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="manual_check_form.checknumber" placeholder="Enter here...">
                        <template #prefix> </template>

                        <template #suffix>
                            <a-tooltip title="Enter check number here...">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <div v-if="manual_check_form.errors.checknumber" class="text-white mb-3" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                        {{ manual_check_form.errors.checknumber }}
                    </div>
                    <a-breadcrumb class="mt-3">
                        <a-breadcrumb-item href="">
                            <BankOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Bank Name</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-select show-search placeholder="Search bank name" :default-active-first-option="false"
                        v-model:value="manual_check_form.bankname" style="width: 100%" :show-arrow="false" :filter-option="false"
                        :not-found-content="isRetrieving ? undefined : null" :options="bankOption"
                        @search="handleSearchBank">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                    <div v-if="manual_check_form.errors.bankname" class="text-white mb-3" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                        {{ manual_check_form.errors.bankname }}
                    </div>
                </a-col>
                <a-col :span="8">
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <CalendarOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Check Received</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-date-picker v-model:value="manual_check_form.checkreceived" style="width: 100%">
                    </a-date-picker>
                    <div v-if="manual_check_form.errors.checkreceived" class="text-white mb-3" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                        {{ manual_check_form.errors.checkreceived }}
                    </div>
                    <a-breadcrumb class="mt-3">
                        <a-breadcrumb-item href="">
                            <HomeOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Check Category</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-select placeholder="Select Currency" v-model:value="manual_check_form.checkcategory" style="width: 100%">
                        <a-select-option v-for="(item, key) in category" v-model:value="item.check_category">{{
                            item.check_category
                            }}</a-select-option>
                    </a-select>
                    <div v-if="manual_check_form.errors.checkcategory" class="text-white mb-3" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                        {{ manual_check_form.errors.checkcategory }}
                    </div>
                    <a-breadcrumb class="mt-3">
                        <a-breadcrumb-item href="">
                            <UsergroupAddOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Approving Officer</a-breadcrumb-item>
                    </a-breadcrumb>

                    <a-select show-search placeholder="Search Apporving Officer" :default-active-first-option="false"
                        v-model:value="manual_check_form.approvingOfficer" style="width: 100%" :show-arrow="false"
                        :filter-option="false" :not-found-content="isRetrieving ? undefined : null"
                        :options="appoffOption" @search="handleSearchEmployee">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                    <div v-if="manual_check_form.errors.approvingOfficer" class="text-white mb-3" style="
                                font-size: 12px;
                                border: 1px solid #ff5c58;
                                border-radius: 5px;
                                background: rgba(255, 99, 71, 0.6);
                            ">
                        {{ manual_check_form.errors.approvingOfficer }}
                    </div>
                    <a-button class="mt-5" block type="primary" @click="submitManualCheckEntry"
                        :loading="manual_check_form.processing">
                        <template #icon>
                            <SaveOutlined />
                        </template>
                        {{
                            manual_check_form.processing
                                ? "saving checks..."
                                : "save manual checks?"
                        }}
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
</template>

<script>

import debounce from "lodash/debounce";
import { useForm } from "@inertiajs/vue3";
import dayjs from "dayjs";
import { message } from "ant-design-vue";

export default {
    props: {
        currency: Array,
        category: Array,
        checkClass: Array,
    },
    data() {
        return {
            allData: [],
            checkOption: [],
            bankOption: [],
            customerOption: [],
            accountOption: [],
            appoffOption: [],
            manual_check_form: useForm({
                accountnumber: "",
                checkdate: "",
                currency: null,
                checkfrom: null,
                accountname: null,
                customer: null,
                checkamount: "",
                checkclass: null,
                checknumber: "",
                bankname: null,
                checkreceived: "",
                checkcategory: null,
                approvingOfficer: null,
            }),
        }
    },
    methods: {
        submitManualCheckEntry() {
            this.manual_check_form
                .transform((data) => ({
                    ...data,
                    checkreceived: dayjs(data.checkreceived).format(
                        "YYYY-MM-DD"
                    ),
                    checkdate: dayjs(data.checkdate).format("YYYY-MM-DD"),
                }))
                .post(route("manual_entry_store.checks"), {
                    onSuccess: () => {
                        this.manual_check_form.reset();
                        this.openModal = false;
                        message.success({
                            content: "Check Successfully save",
                            duration: 3,
                        });
                    },
                    preserveState: false
                });
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
    }
}
</script>
