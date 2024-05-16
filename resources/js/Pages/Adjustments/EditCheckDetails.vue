<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import dayjs from "dayjs";
</script>

<template>

    <Head title="Edit check Details" />

    <AdminLayout>
        <div class=" py-4 max-w-8xl mx-auto sm:px-6 lg:px-8">
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
            <a-row :gutter="[16, 16]" class="mt-4 mb-5">
                <a-col :span="12">
                    <p>
                        Customer Name:
                    </p>
                    <a-input v-model:value="form.custName" placeholder="Customer Name" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Check Number:
                    </p>
                    <a-input v-model:value="value" placeholder="Check Number" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Check Class:
                    </p>
                    <a-input v-model:value="value" placeholder="Check Class" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Account Number:
                    </p>
                    <a-input v-model:value="value" placeholder="Account Number" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Account Name:
                    </p>
                    <a-input v-model:value="value" placeholder="Account Name" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Check Category:
                    </p>
                    <a-input v-model:value="value" placeholder="Check Category" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Check From:
                    </p>
                    <a-input v-model:value="value" placeholder="Check From" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Approving Officer:
                    </p>
                    <a-input v-model:value="value" placeholder="Approving Officer" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Check Date:
                    </p>
                    <a-date-picker style="width: 100%;" v-model:value="value" placeholder="Check Date" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Check Received:
                    </p>
                    <a-date-picker style="width: 100%;" v-model:value="value" placeholder="Check Received" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Bank Name:
                    </p>
                    <a-input v-model:value="value" placeholder="Bank Name" />
                </a-col>
                <a-col :span="12">
                    <p>
                        Amount:
                    </p>
                    <a-input v-model:value="value" placeholder="Amount" />
                </a-col>
            </a-row>

        </a-modal>
    </AdminLayout>
</template>
<script>
import dayjs from "dayjs";
import { useForm } from '@inertiajs/vue3';
export default {
    props: {
        data: Object,
        columns: Array,
        yearBackend: String,
        bunitBackend: Number,
        bunit: Object,
    },
    data() {
        return {
            isEditModal: false,
            bunitValue: this.bunitBackend,
            yearValue: this.yearBackend?.length > 0 ? dayjs(this.yearBackend) : false,
            dataSelected: {},
            form: useForm({
                custName: null,
                checkNo: null,
                checkClass: null,
                accountNo: null,
                accountName: null,
                checkCategory: null,
                checkFrom: null,
                appOfficer: null,
                checkDate: null,
                checkReceived: null,
                bankName: null,
                amount: null,
            }),
        }
    },
    methods: {
        handleChangeBunit(value) {
            this.$inertia.get(route('checks.adjustment'), {
                bunitId: value,
                datedYear: dayjs(this.yearBackend).format('YYYY'),

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

            console.log(dataValue)
            this.form.custName = dataValue.fullname;
            this.form.checkNo = dataValue.check_no;
            this.form.checkClass = dataValue.check_class;
            this.form.accountNo = dataValue.account_no;
            this.form.accountName = dataValue.account_name;
            this.form.checkCategory = dataValue.check_class;
            // this.form.checkFrom = dataValue.;
            // this.form.appOfficer = dataValue.;
            // this.form.checkDate = dataValue.;
            // this.form.checkReceived = dataValue.;
            // this.form.bankName = dataValue.;
            // this.form.amount = dataValue.;


            this.isEditModal = true;
            this.dataSelected = dataValue;
        }

    }
}
</script>
