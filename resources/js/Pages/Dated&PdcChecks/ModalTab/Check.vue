<template>
    <a-form layout="horizontal">
        <a-row :gutter="[16, 16]">
            <a-col :span="12">
                <a-form-item label="Account No">
                    <a-input allow-clear v-model:value="form.account" placeholder="Enter Here..." />
                </a-form-item>
                <a-form-item label="Check No">
                    <a-input v-model:value="form.checkNo" placeholder="Enter Here..." />
                </a-form-item>
                <a-form-item label="Check Date">
                    <a-date-picker style="width: 100%;" @change="changeCheckDate" />
                </a-form-item>

                <a-form-item label="Customer Name">
                    <a-input-group compact style="width: 100%;">
                        <a-select style="width: calc(100% - 31px)" show-search placeholder="Search here..."
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
                    <a-input v-model:value="form.currency" placeholder="Enter Here..." />
                </a-form-item>
                <a-form-item label="Replacement Date.">
                    <a-date-picker style="width: 100%;" @change="changeRepDate" />
                </a-form-item>
                <a-form-item label="Reason For Return">
                    <a-textarea :rows="4" show-count v-model:value="form.reason" placeholder="Enter Reason" />
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
                    <a-date-picker style="width: 100%;" @change="changeCheckReceived" />
                </a-form-item>
                <a-form-item label="Check From">
                    <a-input v-model:value="form.checkFrom" placeholder="Enter Here..." />
                </a-form-item>
                <a-form-item label="Check Class">
                    <a-input-group compact style="width: 100%;">
                        <a-select style="width: calc(100% - 31px)" show-search placeholder="Search here..."
                            :default-active-first-option="false" v-model:value="form.checkClass" :show-arrow="false"
                            :filter-option="false" :not-found-content="isRetrieving ? undefined : null
                                " :options="optionsDepartment" @search="debouncedDepartment">
                            <template v-if="isRetrieving" #notFoundContent>
                                <a-spin size="small" />
                            </template>
                        </a-select>

                        <a-tooltip title="Add Class?">
                            <a-popconfirm title="Add Class" ok-text="Add" cancel-text="No" @confirm="addClass">
                                <template #description>
                                    <a-input v-model:value="add.checkClass" placeholder="Enter Here..." />
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
                    <a-input v-model:value="form.checkCat" placeholder="Enter Here..." />
                </a-form-item>
                <a-form-item label="Approving Officer">
                    <a-input v-model:value="form.appOfficer" placeholder="Enter Here..." />
                </a-form-item>
                <a-form-item label="Penalty Amount">
                    <a-input v-model:value="form.penAmount" placeholder="Enter Here..." />
                </a-form-item>
                <a-form-item label="Check Amount">
                    <a-input v-model:value="form.checkAmount" placeholder="Enter Here..." />
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

export default {
    setup() {
        return searchFunctionMixin();
    },
    props: {
        id: Number
    },
    data() {
        return {
            form: useForm({
                amount: '',
                account: '',
                accName: null,
                checkAmount: '',
                penAmount: '',
                appOfficer: '',
                checkNo: '',
                checkDate: '',
                checkRec: '',
                checkCat: '',
                checkClass: null,
                bankName: null,
                currency: '',
                custName: null,
                reason: '',
                repDate: '',
                checkFrom: '',
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
        async addClass() {
            await axios.post(route('add.class'), {
                checkClass: this.add.checkClass
            }).then(response => {
                const data = response.data;
                this.form.checkClass = {
                    value: data.department_id,
                    label: data.department,
                };
            })
        },


    }
}
</script>
