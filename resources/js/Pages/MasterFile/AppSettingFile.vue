<template>
    <div class="py-2">
        <a-breadcrumb class="mb-3">
            <a-breadcrumb-item href="">
                <home-outlined />
            </a-breadcrumb-item>
            <a-breadcrumb-item href="">
                <user-outlined />
                <span>Master File</span>
            </a-breadcrumb-item>
            <a-breadcrumb-item>App Config</a-breadcrumb-item>
        </a-breadcrumb>

    </div>
    <a-tabs v-model:activeKey="activeKey">
        <a-tab-pane key="1">
            <template #tab>
                <span>
                    <BankOutlined />
                    Business Unit Table
                </span>
            </template>
            <a-table class="mt-2" :data-source="data" :columns="columns" bordered size="small">
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'action'">
                        <a-button @click="editSettings(record)" size="small">
                            <template #icon>
                                <SettingOutlined />
                            </template>
                        </a-button>
                    </template>
                </template>
            </a-table>
        </a-tab-pane>
        <a-tab-pane key="2">
            <template #tab>
                <span>
                    <ConsoleSqlOutlined />
                    Institutional File Settings
                </span>
            </template>
            <div class="flex justify-center items-center mt-10">

                <p class="font-bold">
                    Institutional IP:
                </p>

                <a-input style="width: 500px;" v-model:value="formApp.app_value" :disabled="isDisAbled"></a-input>
                <a-button @click="openEdit">
                    <template #icon v-if="isOpenEdit">
                        <FolderOpenOutlined />
                    </template>
                    <template #icon v-else>
                        <FolderOutlined />
                    </template>
                </a-button>
                <br>
            </div>
            <div class="flex justify-center items-center mt-4">
                <a-button style="width: 400px;" type="primary" v-if="isOpenEdit" @click="updateInst"
                    :loading="formApp.processing">
                    <template #icon>
                        <SaveOutlined />
                    </template>
                    {{ formApp.processing ? 'Saving institutional ip...' : " Save Changes Institutional IP" }}
                </a-button>
            </div>

        </a-tab-pane>
    </a-tabs>
    <a-modal v-model:open="isOpenModelEdit" title="Edit Settings" :footer="false">
        <p class="mt-2 ml-2">Atp Start</p>
        <a-date-picker style="width: 100%;" v-model:value="form.b_atpgetdata" />
        <p class="mt-2 ml-2">Encashment Start</p>
        <a-date-picker style="width: 100%;" v-model:value="form.b_encashstart" />
        <p class="mt-2 ml-2">Atp Location Code</p>
        <a-input placeholder="Enter Atp Code here" v-model:value="form.loc_code_atp"></a-input>
        <a-button class="mt-5 mb-4" block type="primary" :loading="form.processing" @click="submit">
            <template #icon>
                <SaveOutlined />
            </template>
            {{ form.processing ? 'Updating...' : "Update Settings" }}
        </a-button>
    </a-modal>

</template>
<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { SaveOutlined } from "@ant-design/icons-vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import dayjs from "dayjs";
import { message } from "ant-design-vue";

export default {
    layout: AdminLayout,
    props: {
        data: Object,
        columns: Array,
        appKey: Object,
    },
    data() {
        return {
            isOpenEdit: false,
            activeKey: '1',
            isDisAbled: true,
            formApp: useForm({
                app_id: null,
                app_value: null,
            }),
            form: useForm({
                loc_code_atp: null,
                b_atpgetdata: null,
                b_encashstart: null,
            }),
            isOpenModelEdit: false,

        }
    },
    methods: {
        editSettings(data) {
            this.form = useForm(data);
            this.form.b_atpgetdata = data.b_atpgetdata == null ? null : dayjs(data.b_atpgetdata);
            this.form.b_encashstart = data.b_encashstart == null ? null : dayjs(data.b_encashstart);
            this.isOpenModelEdit = true;
        },
        submit() {
            this.form.transform((data) => ({
                bunitId: data.businessunit_id,
                loc_code_atp: data.loc_code_atp,
                b_encashstart: this.form.b_encashstart == null ? null : dayjs(data.b_encashstart).format('YYYY-MM-DD'),
                b_atpgetdata: this.form.b_atpgetdata == null ? null : dayjs(data.b_atpgetdata).format('YYYY-MM-DD'),
            })).put(route('update.settings'), {
                onSuccess: () => {
                    this.isOpenModelEdit = false;
                    message.success('Successfully Updated Settings');
                }
            });
        },
        openEdit() {
            this.isDisAbled = !this.isDisAbled;
            this.isOpenEdit = !this.isOpenEdit;
        },
        updateInst() {
            this.formApp.put(route('update.inst'), {
                onSuccess: () => {
                    message.success('Successfully Updated Institutional Ip');
                    this.$inertia.visit(route('app.config'));
                }
            });
        }
    },
    mounted() {
        this.formApp.app_value = this.appKey.app_value;
        this.formApp.app_id = this.appKey.app_id;
    }
}
</script>
