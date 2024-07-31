<template>
    <a-modal :footer="null" style="width: 90%; top: 50px;">
        <a-typography-title :level="5">
            <ToolFilled /> Check Manual Entry
        </a-typography-title>
        <a-card class="mt-3">
            <a-form layout="horizontal">
                <a-row :gutter="[16, 16]">
                    <a-col :span="12">
                        <a-form-item label="Account Number" has-feedback :help="error.accountnumber"
                        :validate-status="error.accountnumber ? 'error' : ''">
                            <a-input v-model:value="form.accountnumber" placeholder="Enter here..." />
                        </a-form-item>
                        <a-form-item label="Check Date" has-feedback :help="error.checkdate"
                        :validate-status="error.checkdate ? 'error' : ''">
                            <a-date-picker style="width: 100%;" @change="dateCheck" />
                        </a-form-item>
                        <a-form-item label="Currency" has-feedback :help="error.currency"
                            :validate-status="error.currency ? 'error' : ''">
                            <a-select placeholder="Select Currency" ref="select" v-model:value="form.currency"
                                style="width: 100%">
                                <a-select-option allow-clear v-for="item in select.currency"
                                    :value="item.currency_id">{{
                                        item.currency_name
                                    }}</a-select-option>
                            </a-select>
                        </a-form-item>
                        <a-form-item label="Check From" has-feedback :help="error.checkfrom"
                            :validate-status="error.checkfrom ? 'error' : ''">
                            <a-input-group compact style="width: 100%;">
                                <a-select style="width: calc(100% - 31px)" show-search placeholder="Search here..."
                                    :default-active-first-option="false" v-model:value="form.checkfrom"
                                    :show-arrow="false" :filter-option="false" :not-found-content="isRetrieving ? undefined : null
                                        " :options="optionsDepartment" @search="debouncedDepartment">
                                    <template v-if="isRetrieving" #notFoundContent>
                                        <a-spin size="small" />
                                    </template>
                                </a-select>

                                <a-tooltip title="Add Check From?">
                                    <a-popconfirm title="Add Class" ok-text="Add" cancel-text="No" @confirm="addFrom">
                                        <template #description>
                                            <a-input v-model:value="add.checkfrom" placeholder="Enter Here..." />
                                        </template>
                                        <a-button>
                                            <template #icon>
                                                <PlusOutlined />
                                            </template>
                                        </a-button>
                                    </a-popconfirm>
                                </a-tooltip>
                            </a-input-group>
                        </a-form-item>
                        <a-form-item label="Account Name" has-feedback :help="error.accountname"
                            :validate-status="error.accountname ? 'error' : ''">
                            <a-select allow-clear show-search placeholder="Search here..."
                                :default-active-first-option="false" v-model:value="form.accountname"
                                style="width: 100%" :show-arrow="false" :filter-option="false" :not-found-content="isRetrieving ? undefined : null
                                    " :options="optionsCustomer" @search="debounceCustomer">
                                <template v-if="isRetrieving" #notFoundContent>
                                    <a-spin size="small" />
                                </template>
                            </a-select>
                        </a-form-item>
                        <a-form-item label="Customer Name" has-feedback :help="error.customer"
                            :validate-status="error.customer ? 'error' : ''">
                            <a-input-group compact style="width: 100%;">
                                <a-select allow-clear style="width: calc(100% - 31px)" show-search
                                    placeholder="Search here..." :default-active-first-option="false"
                                    v-model:value="form.customer" :show-arrow="false" :filter-option="false"
                                    :not-found-content="isRetrieving ? undefined : null
                                        " :options="optionsCustomer" @search="debounceCustomer">
                                    <template v-if="isRetrieving" #notFoundContent>
                                        <a-spin size="small" />
                                    </template>
                                </a-select>

                                <a-tooltip title="Add Customer?">
                                    <a-popconfirm title="Add Customer" ok-text="Add" cancel-text="No"
                                        @confirm="addCustomer">
                                        <template #description>
                                            <a-input v-model:value="add.customer" placeholder="Enter Here..." />
                                        </template>
                                        <a-button>
                                            <template #icon>
                                                <PlusOutlined />
                                            </template>
                                        </a-button>
                                    </a-popconfirm>
                                </a-tooltip>
                            </a-input-group>
                        </a-form-item>

                        <a-form-item label="Check Amount" has-feedback :help="error.checkamount"
                        :validate-status="error.checkamount ? 'error' : ''">
                            <a-input v-model:value="form.checkamount" placeholder="Enter here..." />
                        </a-form-item>

                    </a-col>
                    <a-col :span="12">
                        <a-form-item label="Check Class" has-feedback :help="error.checkclass"
                            :validate-status="error.checkclass ? 'error' : ''">
                            <a-select allow-clear placeholder="Select Class" ref="select"
                                v-model:value="form.checkclass" style="width: 100%">
                                <a-select-option v-for="item in select.check_class" :value="item.check_class">{{
                                    item.check_class
                                    }}</a-select-option>
                            </a-select>
                        </a-form-item>
                        <a-form-item label="Check Number" has-feedback :help="error.checknumber"
                        :validate-status="error.checknumber ? 'error' : ''">
                            <a-input v-model:value="form.checknumber" placeholder="Enter here..." />
                        </a-form-item>
                        <a-form-item label="Bank Name" has-feedback :help="error.bankname"
                            :validate-status="error.bankname ? 'error' : ''">
                            <a-input-group compact style="width: 100%;">
                                <a-select style="width: calc(100% - 31px)" show-search placeholder="Search here..."
                                    :default-active-first-option="false" v-model:value="form.bankname"
                                    :show-arrow="false" :filter-option="false" :not-found-content="isRetrieving ? undefined : null
                                        " :options="optionsBank" @search="debounceBank">
                                    <template v-if="isRetrieving" #notFoundContent>
                                        <a-spin size="small" />
                                    </template>
                                </a-select>

                                <a-tooltip title="Add Bank?">
                                    <a-popconfirm title="Add Bank" ok-text="Add" cancel-text="No" @confirm="addBank">
                                        <template #description>
                                            <a-input v-model:value="add.bankname" placeholder="Enter Here..." />
                                        </template>
                                        <a-button>
                                            <template #icon>
                                                <PlusOutlined />
                                            </template>
                                        </a-button>
                                    </a-popconfirm>
                                </a-tooltip>
                            </a-input-group>
                        </a-form-item>
                        <a-form-item label="Check Received" has-feedback :help="error.checkreceived"
                        :validate-status="error.checkreceived ? 'error' : ''">
                            <a-date-picker style="width: 100%;" @change="dateReceived" />
                        </a-form-item>
                        <a-form-item label="Check Category" has-feedback :help="error.checkcategory"
                            :validate-status="error.checkcategory ? 'error' : ''">
                            <a-select placeholder="Select Category" ref="select" v-model:value="form.checkcategory"
                                style="width: 100%">
                                <a-select-option allow-clear v-for="item in select.category"
                                    :value="item.check_category">{{
                                        item.check_category
                                    }}</a-select-option>
                            </a-select>
                        </a-form-item>
                        <a-form-item label="Approving Officer" has-feedback :help="error.approvingOfficer"
                            :validate-status="error.approvingOfficer ? 'error' : ''">
                            <a-select show-search placeholder="Search here..." :default-active-first-option="false"
                                v-model:value="form.approvingOfficer" style="width: 100%" :show-arrow="false"
                                :filter-option="false" :not-found-content="isRetrieving ? undefined : null
                                    " :options="optionsEmployee" @search="debouncedSearchEmployee">
                                <template v-if="isRetrieving" #notFoundContent>
                                    <a-spin size="small" />
                                </template>
                            </a-select>
                        </a-form-item>

                        <a-button block type="primary" @click="submit" :loading="form.processing">
                            {{form.processing ? "Submitting Checks On progress..." : "Submit Manual Check" }}
                        </a-button>
                    </a-col>
                </a-row>
            </a-form>
        </a-card>
    </a-modal>
</template>

<script>

import { useForm } from "@inertiajs/vue3";
import dayjs from "dayjs";
import { message, notification } from "ant-design-vue";
import { searchFunctionMixin } from '@/Mixin/FilterQuery';
import { pickBy } from "lodash";

export default {
    setup() {
        return searchFunctionMixin();
    },
    props: {
        select: Object,
    },
    data() {
        return {
            error: {},
            form: useForm({
                accountnumber: "",
                checkdate: null,
                currency: null,
                checkfrom: null,
                accountname: null,
                customer: null,
                checkamount: "",
                checkclass: null,
                checknumber: "",
                bankname: null,
                checkreceived: null,
                checkcategory: null,
                approvingOfficer: null,
            }),
            add: {
                checkfrom: null,
                customer: null,
                bankname: null,
            }
        }
    },
    methods: {
        dateCheck(obj, str) {
            this.form.checkdate = str;
        },
        dateReceived(obj, str) {
            this.form.checkreceived = str;
        },
        submit() {
            this.form.transform((data) => ({
                ...pickBy(data),
            })).post(route('manual_entry_store.checks'), {
                onStart: () => {
                    this.isSubmitting = true;
                    message.loading('Action in progress..', 0)
                },
                onSuccess: () => {
                    message.destroy();
                    this.$emit('close-modal');
                    notification['success']({
                        message: 'Success',
                        description: 'Check Manual Entry Added Successfully',
                    });
                },
                onError: (errors) => {
                    message.destroy();
                    this.error = errors;
                },
            });
        },

        async addCustomer() {
            await axios.post(route('add.customer'), {
                customer: this.add.customer
            }).then(response => {
                const data = response.data;
                this.form.customer = {
                    value: data.customer_id,
                    label: data.fullname,
                };
            })
        },
        async addBank() {
            await axios.post(route('add.bank'), {
                bankName: this.add.bankname
            }).then(response => {
                const data = response.data;
                this.form.bankname = {
                    value: data.bank_id,
                    label: data.bankbranchname,
                };
            })
        },
        async addFrom() {
            await axios.post(route('add.class'), {
                checkFrom: this.add.checkfrom
            }).then(response => {
                const data = response.data;
                this.form.checkfrom = {
                    value: data.department_id,
                    label: data.department,
                };
            })
        },
    }
}
</script>
