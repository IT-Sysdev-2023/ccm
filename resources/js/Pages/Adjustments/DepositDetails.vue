<template>

    <Head title="Edit check Details" />
        <div class=" py-4 max-w-8xl mx-auto sm:px-6 lg:px-8">
            <a-date-picker style="width: 250px;" @change="handleChangeYear" v-model:value="yearValue" picker="year"
                class="mr-2 mb-4" />
            <a-select style="width: 250px;" v-model:value="bunitValue" class="mr-2" placeholder="Select Businessunit"
                @change="handleChangeBunit">
                <a-select-option v-for="item in bunit" v-model:value="item.businessunit_id">{{ item.bname
                    }}</a-select-option>
            </a-select>
            <a-table :columns="columns" :data-source="data.data" size="small" bordered :pagination="false">
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'action'">
                        <a-button class="mx-1" size="small" ref="ref4" v-on:click="
                            editChecks(record.checks_id)
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
        <a-modal v-model:open="isModalEdit" title="Edit Ds Number" :footer="false">
            <a-input v-model:value="form.dsNumber" placeholder="Enter Ds Here" />
            <div v-if="
                form.errors.dsNumber
            " class="text-red-600 ml-2" style="font-size: 12px">
                *{{
                    form.errors.dsNumber
                }}
            </div>
            <a-button block class="mt-3" type="primary" @click="submitDsNumber" :loading="form.processing">
                <template #icon>
                    <SaveOutlined />
                </template>
                {{ form.processing ? 'Submiting Ds number...' : 'Submit Ds Number' }}
            </a-button>
        </a-modal>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm } from "@inertiajs/vue3";
import dayjs from "dayjs";
import { message } from "ant-design-vue";
export default {
    layout: AdminLayout,
    props: {
        data: Object,
        columns: Array,
        bunitBackend: String,
        yearBackend: String,
        bunit: Number,
    },
    data() {
        return {
            isModalEdit: false,
            form: useForm({
                dsNumber: null,
                checksId: null,
            }),

            bunitValue: this.bunitBackend,
            yearValue: this.yearBackend?.length > 0 ? dayjs(this.yearBackend) : false,
        }
    },
    methods: {
        handleChangeBunit(value) {
            this.$inertia.get(route('deposit.adjustment'), {
                bunitId: value,
                datedYear: dayjs(this.yearBackend).format('YYYY'),

            })
        },
        handleChangeYear(yrObj, yrStr) {
            this.$inertia.get(route('deposit.adjustment'), {
                bunitId: this.bunitValue,
                datedYear: yrStr,
            })
        },
        editChecks(checkId) {
            this.isModalEdit = true;
            this.form.checksId = checkId;
        },
        submitDsNumber() {
            this.form.put(route('update.dsNo.adjustments'), {
                checksId: this.form.checksId,
                dsNumber: this.form.dsNumber
            }, {
                onSuccess: () => {
                    this.form.reset();
                    this.isModalEdit = false,
                        message.success('Successfully Updating Ds Number');
                }
            })
        }
    }
}
</script>
