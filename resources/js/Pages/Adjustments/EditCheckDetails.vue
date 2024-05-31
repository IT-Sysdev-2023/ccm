<template>
    <Head title="Edit Check Details" />
        <div class=" py-4 max-w-8xl mx-auto sm:px-6 lg:px-8">
            <a-breadcrumb class="mb-5">
                <a-breadcrumb-item href="">
                    <home-outlined />
                </a-breadcrumb-item>
                <a-breadcrumb-item href="">
                    <span>Ajustments</span>
                </a-breadcrumb-item>
                <a-breadcrumb-item>Editing Checks Table</a-breadcrumb-item>
            </a-breadcrumb>
            <a-date-picker style="width: 250px;" @change="handleChangeYear" v-model:value="yearValue" picker="year"
                class="mr-2 mb-4" />
            <a-select style="width: 250px;" v-model:value="bunitValue" class="mr-2" placeholder="Select Businessunit"
                @change="handleChangeBunit">
                <a-select-option v-for="item in bunit" v-model:value="item.businessunit_id">{{ item.bname
                    }}</a-select-option>
            </a-select>
            <a-table :data-source="data.data" :columns="columns" size="small" bordered :pagination="false">
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'action'">
                        <a-button class="mx-1" size="small" ref="ref4" v-on:click="
                            editChecks(record)
                            ">
                            <template #icon>
                                <FormOutlined />
                            </template>
                        </a-button>

                        <a-button size="small" v-on:click="openModalDetails(record)">
                            <template #icon>
                                <SettingOutlined />
                            </template>
                        </a-button>
                    </template>
                </template>
            </a-table>
            <pagination :datarecords="data" class="mt-6" />
        </div>

        <a-modal v-model:open="isEditModal" title="Edit Checks" width="50%" wrap-class-name="full-modal"
            :footer="false">
            <a-row :gutter="[16, 16]" class="mt-4 mb-2">
                <a-col :span="12">
                    <p>
                        Customer Name:
                    </p>
                    <a-select show-search placeholder="Search customer name" :default-active-first-option="false"
                        v-model:value="form.fullname" style="width: 100%" :show-arrow="false" :filter-option="false"
                        :not-found-content="isRetrieving ? undefined : null" :options="customerOption"
                        @select="(_, val) => (form.customer_id = val.value)" @search="handleSearchCustomer">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                </a-col>
                <a-col :span="12">
                    <p>
                        Check Number:
                    </p>
                    <a-input v-model:value="form.check_no" placeholder="Check Number" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Check Class:
                    </p>
                    <a-input v-model:value="form.check_class" placeholder="Check Class" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Account Number:
                    </p>
                    <a-input v-model:value="form.account_no" placeholder="Account Number" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Account Name:
                    </p>
                    <a-input v-model:value="form.account_name" placeholder="Account Name" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Check Category:
                    </p>
                    <a-input v-model:value="form.check_category" placeholder="Check Category" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Check From:
                    </p>
                    <a-select show-search placeholder="Search Check From" :default-active-first-option="false"
                        v-model:value="form.department" style="width: 100%" :show-arrow="false" :filter-option="false"
                        :not-found-content="isRetrieving ? undefined : null" :options="checkOption"
                        @select="(_, val) => (form.department_id = val.value)" @search="handleSearchCheckFrom">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                </a-col>
                <a-col :span="12">
                    <p>
                        Approving Officer:
                    </p>
                    <a-select show-search placeholder="Search approving officer" :default-active-first-option="false"
                        v-model:value="form.approving_officer" style="width: 100%" :show-arrow="false"
                        :filter-option="false" :not-found-content="isRetrieving ? undefined : null"
                        :options="appoffOption" @search="handleSearchEmployee">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                </a-col>
                <a-col :span="12">
                    <p>
                        Check Date:
                    </p>
                    <a-date-picker style="width: 100%;" v-model:value="form.checkDate" placeholder="Check Date" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Check Received:
                    </p>
                    <a-date-picker style="width: 100%;" v-model:value="form.checkReceived"
                        placeholder="Check Received" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Bank Name:
                    </p>
                    <a-select show-search placeholder="Search bank name" :default-active-first-option="false"
                        v-model:value="form.bankbranchname" style="width: 100%" :show-arrow="false"
                        :filter-option="false" :not-found-content="isRetrieving ? undefined : null"
                        :options="bankOption" @select="(_, val) => (form.bank_id = val.value)"
                        @search="handleSearchBank">
                        <template v-if="isRetrieving" #notFoundContent>
                            <a-spin size="small" />
                        </template>
                    </a-select>
                </a-col>
                <a-col :span="12">
                    <p>
                        Amount:
                    </p>
                    <a-input v-model:value="form.check_amount" placeholder="Amount" />
                </a-col>
                <a-button type="primary" block class="mt-3" @click="updateCheckAdjustments" :loading="form.processing">
                    <template #icon>
                        <SaveOutlined />
                    </template>
                    {{ form.processing ? 'Updating Checks On Progress...' : ' Update Checks Details' }}
                </a-button>
            </a-row>

        </a-modal>
</template>
<script>
import dayjs from "dayjs";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm } from "@inertiajs/vue3";
import debounce from "lodash/debounce";
import { message } from "ant-design-vue";
export default {
    layout: AdminLayout,
    props: {
        data: Object,
        columns: Array,
        yearBackend: String,
        bunitBackend: Number,
        bunit: Object,
    },
    data() {
        return {
            allData: [],
            isRetrieving: false,
            customerOption: [],
            checkOption: [],
            bankOption: [],
            appoffOption: [],
            isEditModal: false,
            bunitValue: this.bunitBackend,
            yearValue: this.yearBackend?.length > 0 ? dayjs(this.yearBackend) : false,
            dataSelected: {},
            form: useForm({
                checks_id: null,
                customer_id: null,
                check_no: null,
                check_class: null,
                account_no: null,
                account_name: null,
                check_category: null,
                department_id: null,
                approving_officer: null,
                checkDate: null,
                checkReceived: null,
                bank_id: null,
                check_amount: null,
            }),
        }
    },
    methods: {
        updateCheckAdjustments() {
            this.form.transform((data) => ({
                checks_id: data.checks_id,
                customer_id: data.customer_id,
                check_no: data.check_no,
                check_class: data.check_class,
                account_no: data.account_no,
                account_name: data.account_name,
                check_category: data.check_category,
                department_id: data.department_id,
                approving_officer: data.approving_officer,
                bank_id: data.bank_id,
                check_amount: data.check_amount,
                check_date: dayjs(data.checkDate).format('YYYY-MM-DD'),
                check_received: dayjs(data.checkReceived).format('YYYY-MM-DD'),
            })).put(route('update.adjustments'), {
                onSuccess: () => {
                    this.form.reset();
                    this.isEditModal = false;
                    message.success('Successfully Updating Ds Number');
                }
            });
        },
        handleChangeBunit(value) {
            this.$inertia.get(route('checks.adjustment'), {
                bunitId: value,
                datedYear: this.yearBackend ? dayjs(this.yearBackend).format('YYYY') : null,

            })
        },
        handleChangeYear(yrObj, yrStr) {
            this.$inertia.get(route('checks.adjustment'), {
                bunitId: this.bunitValue,
                datedYear: yrStr,
            })
        },
        openModalDetails(dataValue) {

        },
        editChecks(dataValue) {
            this.form = useForm(dataValue);
            this.form.checkDate = dayjs(dataValue.check_date);
            this.form.checkReceived = dayjs(dataValue.check_received);
            this.isEditModal = true;
            this.dataSelected = dataValue;
        },

        // search function


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

    }
}
</script>
