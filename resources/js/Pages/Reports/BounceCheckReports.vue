<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, useForm } from '@inertiajs/vue3';


const colors = "red";
</script>

<template>
    <AdminLayout>
        <div class=" py-4 max-w-8xl mx-auto sm:px-6 lg:px-8">
            <a-breadcrumb>
                <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                <a-breadcrumb-item><a href="">Reports</a></a-breadcrumb-item>
                <a-breadcrumb-item>Bounce Check Report</a-breadcrumb-item>
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


                <div>
                    <a-range-picker class="mt-5 mr-2" v-model:value="dateRangeValue" />
                    <!-- {{ bunit }} -->
                    <a-select ref="select" style="width: 200px" v-model:value="bunitCode" placeholder="Business Unit"
                        class="mr-2">
                        <a-select-option v-for="(item, key) in bunit" :key="key" v-model:value="item.businessunit_id">{{
                            item.bname }}</a-select-option>
                    </a-select>
                    <a-select ref="select" style="width: 200px" v-model:value="bounceStatus" placeholder="Bounce Status"
                        class="mr-2">
                        <a-select-option value="0">All Checks</a-select-option>
                        <a-select-option value="1">Pending Checks</a-select-option>
                        <a-select-option value="2">Settled Checks</a-select-option>
                    </a-select>
                    <a-button style="width: 200px;" type="primary" ghost @click="fetchBounceData" :loading="isFetching">
                        <template #icon>
                            <LoginOutlined />
                        </template>
                        fetch data
                    </a-button>
                </div>
                <div>
                    <a-button  :loading="isLoading" type="primary" class="mt-5" @click="startGeneratingBounceChecks" :disabled="data.data.length <= 0 || data.data == undefined">
                        <template #icon>
                            <CloudDownloadOutlined />
                        </template>
                        {{ isLoading ? 'Generating Cheques in progress...' : 'Start Generating Cheques'}}
                    </a-button>
                </div>
            </div>
            <a-table class="mt-5" :pagination="false" size="small" :data-source="data.data" :columns="columns" />
            <pagination class="mt-6" :datarecords="data" />
        </div>
    </AdminLayout>
</template>

<script>
import dayjs from "dayjs";

export default {
    props: {
        data: Object,
        columns: Array,
        bunit: Object,
        dateRangeBackend: Array,
        bounceValue: Number,
        bunitCodeBackend: Number,
    },
    data() {
        return {
            isGeneratingShow: false,
            isLoading: false,
            isFetching: false,
            bunitCode: this.bunitCodeBackend,
            bounceStatus: this.bounceValue,
            dateRangeValue: this.dateRangeBackend?.length > 0 ?
                [
                    dayjs(this.dateRangeBackend[0]),
                    dayjs(this.dateRangeBackend[1]),
                ]
                : false,

            progressBar: {
                percentage: 0,
                department: "",
                message: "",
                totalRows: 0,
                currentRow: 0,
            },
        };
    },
    methods: {
        fetchBounceData() {
            this.isFetching = true;
            if (!this.dateRangeValue) {
                this.dateRangeValue = false;
            }
            this.$inertia.get(route('bounce.checks.report'), {
                dateRangeArr0: this.dateRangeValue[0] === undefined ? null : dayjs(this.dateRangeValue[0]).format(
                    "YYYY-MM-DD"
                ),
                dateRangeArr1: this.dateRangeValue[1] === undefined ? null : dayjs(this.dateRangeValue[1]).format(
                    "YYYY-MM-DD"
                ),
                bunitCode: this.bunitCode,
                bounceStatus: this.bounceStatus,
            });
        },
        startGeneratingBounceChecks() {
            this.isGeneratingShow = true;
            this.isLoading = true;
            this.$inertia.get(route('startgenerate.bounceChecks'), {
                dateRangeArr0: this.dateRangeValue[0] === undefined ? null : dayjs(this.dateRangeValue[0]).format(
                    "YYYY-MM-DD"
                ),
                dateRangeArr1: dayjs(this.dateRangeValue[1]).format(
                    "YYYY-MM-DD"
                ),
                bunitCode: this.bunitCode,
                bounceStatus: this.bounceStatus,
            });
        }
    },
    mounted() {
        this.$ws
            .private(`generating-bounce-checks.${this.$page.props.auth.user.id}`)
            .listen(".generating-bounced", (e) => {
                this.progressBar = e;
            });
    }
};
</script>
