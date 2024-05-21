<template>

    <Head title="Redeem Checks Reports" />
        <div class="py-4 max-w-8xl mx-auto sm:px-6 lg:px-8">
            <a-breadcrumb class="mb-2">
                <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                <a-breadcrumb-item><a href="">Reports</a></a-breadcrumb-item>
                <a-breadcrumb-item>Redeem Cheque Reports</a-breadcrumb-item>
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

                <!-- {{   dateRangeBackend }} -->
                <div>
                    <a-range-picker :disabled="isFetching" style="width: 350px;" class="mr-1 mb-3"
                        v-model:value="dateRange" />
                    <a-select ref="select" :disabled="isFetching" :loading="isFetching" v-model:value="bunitValue"
                        style="width: 250px" placeholder="Select Business Unit">
                        <a-select-option v-for="item in bunit" v-model:value="item.businessunit_id">{{ item.bname
                            }}</a-select-option>
                    </a-select>
                    <a-button class="ml-1" :disabled="isFetching" :loading="isFetching" style="width: 200px;"
                        type="primary" ghost @click="fetchingData">
                        <template #icon>
                            <LoginOutlined />
                        </template>
                        Fetch data
                    </a-button>
                </div>
                <div>
                    <a-button type="primary" :loading="isLoading" @click="startGeneratingRepors"
                        :disabled="data.data.length <= 0">
                        <template #icon>
                            <CloudUploadOutlined />
                        </template>
                        Generate Redeem Cheque Reports
                    </a-button>
                </div>
            </div>
            <a-table :data-source="data.data" :columns="columns" size="small" bordered :pagination="false">
            </a-table>
            <pagination :datarecords="data" class="mt-5">
            </pagination>
        </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import dayjs from "dayjs";
export default {
    layout: AdminLayout,
    props: {
        data: Object,
        bunit: Object,
        columns: Array,
        dateRangeBackend: Array,
        bunitBackend: Number,
    },
    data() {
        return {
            isGeneratingShow: false,
            progressBar: {
                currentRow: 0,
                percentage: 0,
                message: "",
                totalRows: 0,
            },
            isFetching: false,
            isLoading: false,
            bunitValue: this.bunitBackend,
            dateRange: this.dateRangeBackend ? [this.dateRangeBackend[0] ? dayjs(this.dateRangeBackend[0]) : null, this.dateRangeBackend[1] ? dayjs(this.dateRangeBackend[1]) : null] : null,
        }
    },
    methods: {
        fetchingData() {
            this.isFetching = true;
            this.$inertia.get(route('redeem.reports.admin'), {
                bunitId: this.bunitValue,
                dateFrom: (this.dateRange && this.dateRange[0] !== null) ? dayjs(this.dateRange[0]).format('YYYY-MM-DD') : null,
                dateTo: (this.dateRange && this.dateRange[1] !== null) ? dayjs(this.dateRange[1]).format('YYYY-MM-DD') : null,
            });
        },
        startGeneratingRepors() {
            this.isLoading = true;
            this.isGeneratingShow = true;
            this.$inertia.get(route('start.generate.redeem.reports'), {
                bunitId: this.bunitValue,
                dateFrom: (this.dateRange && this.dateRange[0] !== null) ? dayjs(this.dateRange[0]).format('YYYY-MM-DD') : null,
                dateTo: (this.dateRange && this.dateRange[1] !== null) ? dayjs(this.dateRange[1]).format('YYYY-MM-DD') : null,
                reDirect: 2,
            });
        }
    },
    mounted() {
        this.$ws
            .private(`generating-redeem-reports.${this.$page.props.auth.user.id}`)
            .listen(".generating-redeem-reports", (e) => {
                this.progressBar = e;
            });
    }
}
</script>
