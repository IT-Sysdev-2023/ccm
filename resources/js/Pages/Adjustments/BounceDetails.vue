<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useForm } from "@inertiajs/vue3";
import dayjs from "dayjs";
</script>

<template>

    <Head title="Edit check Details" />

    <AdminLayout>
        <div class=" py-4 max-w-8xl mx-auto sm:px-6 lg:px-8">
            <a-select style="width: 250px;" v-model:value="bunitValue" class="mr-2 mb-4"
                placeholder="Select Businessunit" @change="handleChangeBunit">
                <a-select-option v-for="item in bunit" v-model:value="item.businessunit_id">{{ item.bname
                    }}</a-select-option>
            </a-select>
            <a-table :data-source="data.data" :columns="columns" bordered :pagination="false" size="small">
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'action'">

                        <a-popconfirm title="You are about to re-bounced checks. Do you want to continue? " :footer="null" ok-text="Yes" cancel-text="No"
                            @confirm="reBounce(record.checks_id)">
                            <a-button size="small">
                                <template #icon>
                                    <TagsOutlined />
                                </template>
                            </a-button>
                        </a-popconfirm>

                        <a-button size="small" class="ml-1" v-on:click="openModalDetails(record)">
                            <template #icon>
                                <SettingOutlined />
                            </template>
                        </a-button>
                    </template>
                </template>
            </a-table>
            <pagination :datarecords="data" class="mt-5"></pagination>
        </div>
    </AdminLayout>

</template>
<script>
import { message } from "ant-design-vue";
export default {
    props: {
        data: Object,
        columns: Array,
        bunit: Object,
    },
    data() {
        return {
            bunitValue: null,
        }
    },
    methods: {
        handleChangeBunit() {
            this.$inertia.get(route('bounce.checks.adjustments'), {
                bunitId: this.bunitValue,
            })
        },
        reBounce(checksId) {
            this.$inertia.put(route('rebounce.adjustments'), {
                checksId: checksId
            }, {
                onSuccess: () => {
                    message.success('Rebounce Successfully');
                }
            })
        },
        openModalDetails(dataValue) {

        }
    }
}
</script>
