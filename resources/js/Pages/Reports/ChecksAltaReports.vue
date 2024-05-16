<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, useForm } from '@inertiajs/vue3';


const colors = "red";
</script>

<template>
    <AdminLayout>
        <div class="py-4">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between">
                    <a-date-picker v-model:value="dateMonth" @change="handleChangeMonth" picker="month"
                        style="width: 200px;" class="mt-5 mb-5" />
                    <a-button class="mt-5 mb-5" type="primary">
                        <template #icon>
                            <CloudDownloadOutlined />
                        </template>
                        Generate Alta Cheques
                    </a-button>
                </div>
                <a-table :data-source="data.data" :columns="columns" size="small" bordered :pagination="false">

                </a-table>
                <pagination :datarecords="data" class="mt-5" />
            </div>
        </div>

    </AdminLayout>
</template>
<script>
import dayjs from 'dayjs';
export default {
    props: {
        data: Object,
        columns: Array,
        dateBackEnd: String,
    },
    data() {
        return {
            dateMonth: this.dateBackEnd ? dayjs(this.dateBackEnd) : null,
            // this.dateRangeBackend ? [this.dateRangeBackend[0] ? dayjs(this.dateRangeBackend[0]) : null, this.dateRangeBackend[1] ? dayjs(this.dateRangeBackend[1]) : null] : null,
        }
    },
    methods: {
        handleChangeMonth(monthObj, monthStr) {
            this.$inertia.get(route('alta.reports'), {
                altaDates: monthStr,
            })
        }
    }
}
</script>
