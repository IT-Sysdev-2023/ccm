<template>
    <a-form layout="horizontal">
        <a-row :gutter="[16, 16]">
            <a-col :span="12">
                <a-form-item label="Account No">
                    <a-input allow-clear v-model:value="form.account" placeholder="Enter Here..." />
                </a-form-item>
                <a-form-item  label="Check No">
                    <a-input  allow-clear v-model:value="form.checkNo" placeholder="Enter Here..." />
                </a-form-item>
                <a-form-item  allow-clear label="Check Date">
                    <a-date-picker style="width: 100%;" @change="changeCheckDate" />
                </a-form-item>

                <a-form-item label="Customer Name">
                    <a-input-group compact style="width: 100%;">
                        <a-select  allow-clear style="width: calc(100% - 31px)" show-search placeholder="Search here..."
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
                <a-form-item label="Bank Name">
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
                <a-form-item label="Currency">
                    <a-select placeholder="Select Currency" ref="select" v-model:value="form.currency" style="width: 100%">
                        <a-select-option  allow-clear v-for="item in currency" :value="item.currency_id">{{ item.currency_name }}</a-select-option>
                    </a-select>
                </a-form-item>
                <a-form-item label="Replacement Date.">
                    <a-date-picker  style="width: 100%;" @change="changeRepDate" />
                </a-form-item>
                <a-form-item label="Reason For Return">
                    <a-textarea  allow-clear :rows="4" show-count v-model:value="form.reason" placeholder="Enter Reason" />
                </a-form-item>
            </a-col>
            <a-col :span="12">
                <a-form-item label="Account Name">
                    <a-select allow-clear show-search placeholder="Search here..." :default-active-first-option="false"
                        v-model:value="form.accName" style="width: 100%" :show-arrow="false" :filter-option="false"
                        :not-found-content="isRetrieving ? undefined : null
                            " :options="optionsCustomer" @search="debounceCustomer">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                </a-form-item>
                <a-form-item label="Check Received">
                    <a-date-picker  style="width: 100%;" @change="changeCheckReceived" />
                </a-form-item>
                <a-form-item label="Check Class">
                    <a-select  allow-clear placeholder="Select Class" ref="select" v-model:value="form.checkClass" style="width: 100%">
                        <a-select-option v-for="item in checkClass" :value="item.check_class">{{ item.check_class }}</a-select-option>
                    </a-select>
                </a-form-item>

                <a-form-item label="Check From">
                    <a-input-group compact style="width: 100%;">
                        <a-select style="width: calc(100% - 31px)" show-search placeholder="Search here..."
                            :default-active-first-option="false" v-model:value="form.checkFrom" :show-arrow="false"
                            :filter-option="false" :not-found-content="isRetrieving ? undefined : null
                                " :options="optionsDepartment" @search="debouncedDepartment">
                            <template v-if="isRetrieving" #notFoundContent>
                                <a-spin size="small" />
                            </template>
                        </a-select>

                        <a-tooltip title="Add Class?">
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
                <a-form-item label="Check Category">
                    <a-select placeholder="Select Category" ref="select" v-model:value="form.checkCat" style="width: 100%">
                        <a-select-option  allow-clear v-for="item in category" :value="item.check_category">{{ item.check_category }}</a-select-option>
                    </a-select>
                </a-form-item>
                <a-form-item label="Approving Officer">
                    <a-select show-search placeholder="Search here..." :default-active-first-option="false"
                        v-model:value="form.appOfficer" style="width: 100%" :show-arrow="false" :filter-option="false"
                        :not-found-content="isRetrieving ? undefined : null
                            " :options="optionsEmployee" @search="debouncedSearchEmployee">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                </a-form-item>
                <a-form-item label="Penalty Amount">
                    <a-input  allow-clear v-model:value="form.penAmount" placeholder="Enter Here..." />
                </a-form-item>
                <a-form-item label="Check Amount">
                    <a-input  allow-clear v-model:value="form.checkAmount" placeholder="Enter Here..." />
                </a-form-item>
                <a-button class="mt-2" block type="primary" @click="submit">
                    <template #icon>
                    </template>
                    Submit Check Replacement
                </a-button>
            </a-col>
        </a-row>
    </a-form>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import { searchFunctionMixin } from '@/Mixin/FilterQuery';
import axios from 'axios';
import { fromPairs } from 'lodash';


export default {
    setup() {
        return searchFunctionMixin();
    },
    props: {
        id: Number,
        record: Object,
        currency: Object,
        checkClass: Object,
        category: Object
    },
    data() {
        return {
            form: useForm({
                account: '',
                accName: null,
                checkAmount: '',
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
            }),
            add: {
                customer: '',
                bankName: '',
                checkClass: ''
            },
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
            this.form.transform((data) => ({
                ...data
            })).post(route('pdc_check.replacement'), {

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
