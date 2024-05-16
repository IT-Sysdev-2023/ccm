<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, useForm } from '@inertiajs/vue3';


const colors = "red";
</script>

<template>
    <AdminLayout>
        <div class="py-4">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">

                <a-breadcrumb>
                    <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                    <a-breadcrumb-item><a href="">Reports</a></a-breadcrumb-item>
                    <a-breadcrumb-item>Alta Cita Reports</a-breadcrumb-item>
                </a-breadcrumb>
                <div v-if="isGeneratingShow">
                    <div class="flex justify-between mt-5">
                        <div>
                            <p> {{ progressBar.message }}{{ progressBar.currentRow
                                }}
                                to
                                {{
                                    progressBar.totalRows }}</p>
                        </div>
                    </div>
                    <a-progress :stroke-color="{
                        from: '#108ee9', to: '#87d068',
                    }" :percent="progressBar.percentage" status="active" />
                </div>

                <div class="flex justify-between">
                    <a-date-picker :disabled="isFetching" v-model:value="dateMonth" @change="handleChangeMonth" picker="month"
                        style="width: 250px;" class="mt-5 mb-5" />
                    <a-button class="mt-5 mb-5" type="primary" @click="startGenerating" :loading="isLoading" :disabled="data.data?.length <= 0 || data.data === undefined">
                        <template #icon>
                            <CloudDownloadOutlined />
                        </template>
                        {{ isLoading ? "Generating Cheques on progress..." : "Generate Alta Cheques"}}
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
            isGeneratingShow: false,
            isFetching: false,
            isLoading: false,
            dateMonth: this.dateBackEnd ? dayjs(this.dateBackEnd) : null,
            progressBar: {
                percentage: 0,
                department: "",
                message: "",
                totalRows: 0,
                currentRow: 0,
            },
        }
    },
    methods: {
        handleChangeMonth(monthObj, monthStr) {
            this.isFetching  =  true;
            this.$inertia.get(route('alta.reports'), {
                altaDates: monthStr,
            })
        },
        startGenerating() {
            this.isLoading = true;
            this.isGeneratingShow = true;
            this.$inertia.get(route('start.generate.alta'), {
                altaDates: this.dateBackEnd,
            })
        }
    },
    mounted() {
        this.$ws
            .private(`generating-general-checks.${this.$page.props.auth.user.id}`)
            .listen(".generating-reports", (e) => {
                this.progressBar = e;
            });
    }
}
</script>
