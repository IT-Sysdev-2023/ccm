<template>
    <a-form layout="horizontal">
        <a-row :gutter="[16, 16]">
            <a-col :span="12">
                <a-form-item label="Account No" has-feedback :help="error.account"
                    :validate-status="error.account ? 'error' : ''">
                    <a-input allow-clear v-model:value="form.account" placeholder="Enter Here..." />
                </a-form-item>
                <a-form-item label="Check No" has-feedback :help="error.checkNo"
                    :validate-status="error.checkNo ? 'error' : ''">
                    <a-input allow-clear v-model:value="form.checkNo" placeholder="Enter Here..." />
                </a-form-item>
                <a-form-item allow-clear label="Check Date" has-feedback :help="error.checkDate"
                    :validate-status="error.checkDate ? 'error' : ''">
                    <a-date-picker style="width: 100%;" @change="changeCheckDate" />
                </a-form-item>

                <a-form-item label="Customer Name" has-feedback :help="error.custName"
                    :validate-status="error.custName ? 'error' : ''">
                    <a-input-group compact style="width: 100%;">
                        <a-select allow-clear style="width: calc(100% - 31px)" show-search placeholder="Search here..."
                            :default-active-first-option="false" v-model:value="form.custName" :show-arrow="false"
                            :filter-option="false" :not-found-content="isRetrieving ? undefined : null
                                " :options="optionsCustomer" @search="debounceCustomer">
                            <template v-if="isRetrieving" #notFoundContent>
                                <a-spin size="small" />
                            </template>
                        </a-select>

                        <a-tooltip title="Add Customer?">
                            <a-popconfirm title="Add Customer" ok-text="Add" cancel-text="No" @confirm="addCustomer">
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
                <a-form-item label="Bank Name" has-feedback :help="error.bankName"
                    :validate-status="error.bankName ? 'error' : ''">
                    <a-input-group compact style="width: 100%;">
                        <a-select style="width: calc(100% - 31px)" show-search placeholder="Search here..."
                            :default-active-first-option="false" v-model:value="form.bankName" :show-arrow="false"
                            :filter-option="false" :not-found-content="isRetrieving ? undefined : null
                                " :options="optionsBank" @search="debounceBank">
                            <template v-if="isRetrieving" #notFoundContent>
                                <a-spin size="small" />
                            </template>
                        </a-select>

                        <a-tooltip title="Add Bank?">
                            <a-popconfirm title="Add Bank" ok-text="Add" cancel-text="No" @confirm="addBank">
                                <template #description>
                                    <a-input v-model:value="add.bankName" placeholder="Enter Here..." />
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
                <a-form-item label="Check From" has-feedback :help="error.checkFrom"
                    :validate-status="error.checkFrom ? 'error' : ''">
                    <a-input-group compact style="width: 100%;">
                        <a-select style="width: calc(100% - 31px)" show-search placeholder="Search here..."
                            :default-active-first-option="false" v-model:value="form.checkFrom" :show-arrow="false"
                            :filter-option="false" :not-found-content="isRetrieving ? undefined : null
                                " :options="optionsDepartment" @search="debouncedDepartment">
                            <template v-if="isRetrieving" #notFoundContent>
                                <a-spin size="small" />
                            </template>
                        </a-select>

                        <a-tooltip title="Add Check From?">
                            <a-popconfirm title="Add Class" ok-text="Add" cancel-text="No" @confirm="addFrom">
                                <template #description>
                                    <a-input v-model:value="add.checkFrom" placeholder="Enter Here..." />
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
                <a-form-item label="Replacement Date." has-feedback :help="error.repDate"
                    :validate-status="error.repDate ? 'error' : ''">
                    <a-date-picker style="width: 100%;" @change="changeRepDate" />
                </a-form-item>
                <a-form-item label="Reason For Return" has-feedback :help="error.reason"
                    :validate-status="error.reason ? 'error' : ''">
                    <a-textarea allow-clear :rows="4" show-count v-model:value="form.reason"
                        placeholder="Enter Reason" />
                </a-form-item>
            </a-col>
            <a-col :span="12">
                <a-form-item label="Account Name" has-feedback :help="error.accName"
                    :validate-status="error.accName ? 'error' : ''">
                    <a-select allow-clear show-search placeholder="Search here..." :default-active-first-option="false"
                        v-model:value="form.accName" style="width: 100%" :show-arrow="false" :filter-option="false"
                        :not-found-content="isRetrieving ? undefined : null
                            " :options="optionsCustomer" @search="debounceCustomer">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                </a-form-item>
                <a-form-item label="Check Received" has-feedback :help="error.checkRec"
                    :validate-status="error.checkRec ? 'error' : ''">
                    <a-date-picker style="width: 100%;" @change="changeCheckReceived" />
                </a-form-item>
                <a-form-item label="Check Class" has-feedback :help="error.checkClass"
                    :validate-status="error.checkClass ? 'error' : ''">
                    <a-select allow-clear placeholder="Select Class" ref="select" v-model:value="form.checkClass"
                        style="width: 100%">
                        <a-select-option v-for="item in checkClass" :value="item.check_class">{{ item.check_class
                            }}</a-select-option>
                    </a-select>
                </a-form-item>
                <a-form-item label="Check Category" has-feedback :help="error.checkCat"
                    :validate-status="error.checkCat ? 'error' : ''">
                    <a-select placeholder="Select Category" ref="select" v-model:value="form.checkCat"
                        style="width: 100%">
                        <a-select-option allow-clear v-for="item in category" :value="item.check_category">{{
                            item.check_category
                            }}</a-select-option>
                    </a-select>
                </a-form-item>
                <a-form-item label="Currency" has-feedback :help="error.currency"
                    :validate-status="error.currency ? 'error' : ''">
                    <a-select placeholder="Select Currency" ref="select" v-model:value="form.currency"
                        style="width: 100%">
                        <a-select-option allow-clear v-for="item in currency" :value="item.currency_id">{{
                            item.currency_name
                            }}</a-select-option>
                    </a-select>
                </a-form-item>
                <a-form-item label="Approving Officer" has-feedback :help="error.appOfficer"
                    :validate-status="error.appOfficer ? 'error' : ''">
                    <a-select show-search placeholder="Search here..." :default-active-first-option="false"
                        v-model:value="form.appOfficer" style="width: 100%" :show-arrow="false" :filter-option="false"
                        :not-found-content="isRetrieving ? undefined : null
                            " :options="optionsEmployee" @search="debouncedSearchEmployee">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                </a-form-item>
                <a-form-item label="Penalty Amount" has-feedback :help="error.penAmount"
                    :validate-status="error.penAmount ? 'error' : ''">
                    <a-input allow-clear v-model:value="form.penAmount" placeholder="Enter Here..." />
                </a-form-item>
                <a-form-item label="Check Amount" has-feedback :help="error.checkAmount"
                    :validate-status="error.checkAmount ? 'error' : ''">
                    <a-input allow-clear v-model:value="form.checkAmount" placeholder="Enter Here..." />
                </a-form-item>

                <div v-if="type == 2">
                    <a-form-item label="Cash Amount" has-feedback :help="error.cashAmount"
                        :validate-status="error.cashAmount ? 'error' : ''">
                        <a-input allow-clear v-model:value="form.cashAmount" placeholder="Enter Here..." />
                    </a-form-item>
                    <a-form-item label="Ar Ds" has-feedback :help="error.ards"
                        :validate-status="error.ards ? 'error' : ''">
                        <a-input allow-clear v-model:value="form.ards" placeholder="Enter Here..." />
                    </a-form-item>
                </div>

                <a-button class="mt-2" block type="primary" @click="submit" :loading="isSubmitting"
                    :disabled="isDisable">
                    <template #icon>
                    </template>
                    <span v-if="type == 1">
                        {{ isSubmitting ? "Submitting form in progress..." : "Submit Check Replacement" }}
                    </span>
                    <span v-else-if="type == 2">
                        {{ isSubmitting ? "Submitting form in progress..." : "Submit Check Cash Replacement" }}
                    </span>
                    <span v-else>
                        {{ isSubmitting ? "Submitting form in progress..." : "Submit Partial Check Replacement" }}
                    </span>
                </a-button>
            </a-col>
        </a-row>
    </a-form>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import { searchFunctionMixin } from '@/Mixin/FilterQuery';
import axios from 'axios';
import { message, notification } from 'ant-design-vue';
import { pickBy } from 'lodash';


export default {
    setup() {
        return searchFunctionMixin();
    },
    props: {
        id: Number,
        amount: Number,
        currency: Object,
        checkClass: Object,
        category: Object,
        type: Number,
    },
    data() {
        return {
            form: useForm({
                account: '',
                accName: null,
                checkAmount: this.amount,
                penAmount: '',
                appOfficer: null,
                checkNo: '',
                checkDate: '',
                checkRec: null,
                checkCat: null,
                checkClass: null,
                bankName: null,
                currency: null,
                custName: null,
                reason: '',
                repDate: '',
                checkFrom: null,
                id: this.id,
                ards: null,
                cashAmount: null,
            }),
            add: {
                customer: '',
                bankName: '',
                checkClass: ''
            },
            isSubmitting: false,
            isDisable: false,
            inputError: '',
            error: {},
        }
    },
    methods: {
        changeRepDate(obj, str) {
            this.form.repDate = str;
        },
        changeCheckDate(obj, str) {
            this.form.checkDate = str;
        },
        changeCheckReceived(obj, str) {
            this.form.checkRec = str;
        },
        submit() {
            const routeUrl =
                this.type === 1 ? 'pdc_check.replacement'
                    : this.type === 3
                        ? 'pdc_check_partial.replacement'
                        : 'pdc_cash_check.replacement';

            const desc =
                this.type === 1 ? 'Replaced Check successfully'
                    : this.type === 3
                        ? 'Replaced Partial Check successfully'
                        : 'Replaced Check and Cash Successfully';

            this.form.transform((data) => ({
                ...pickBy(data)
            })).post(route(routeUrl), {
                onStart: () => {
                    this.isSubmitting = true;
                    message.loading('Action in progress..', 0)
                },
                onSuccess: () => {
                    message.destroy();
                    this.isSubmitting = false;
                    this.isDisable = true;
                    notification['success']({
                        message: 'Success',
                        description: desc,
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 200);
                },
                onError: (errors) => {
                    console.log(errors)
                    this.isSubmitting = false;
                    message.destroy();
                    this.inputError = 'error';
                    this.error = errors;
                }
            })

        },


        async addCustomer() {
            await axios.post(route('add.customer'), {
                customer: this.add.customer
            }).then(response => {
                const data = response.data;
                this.form.custName = {
                    value: data.customer_id,
                    label: data.fullname,
                };
            })
        },
        async addBank() {
            await axios.post(route('add.bank'), {
                bankName: this.add.bankName
            }).then(response => {
                const data = response.data;
                this.form.bankName = {
                    value: data.bank_id,
                    label: data.bankbranchname,
                };
            })
        },
        async addFrom() {
            await axios.post(route('add.class'), {
                checkFrom: this.add.checkFrom
            }).then(response => {
                const data = response.data;
                this.form.checkFrom = {
                    value: data.department_id,
                    label: data.department,
                };
            })
        },
    }
}
</script>
