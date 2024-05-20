<template>
        <div class="py-4">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mb-5">
                    <a-breadcrumb-item href="">
                        <home-outlined />
                    </a-breadcrumb-item>
                    <a-breadcrumb-item href="">
                        <DingtalkOutlined />
                        <span>Reports</span>
                    </a-breadcrumb-item>
                    <a-breadcrumb-item>Deposited Check Reports</a-breadcrumb-item>
                </a-breadcrumb>
                <div v-if="progressBarShowing" class="mb-5">

                    <div class="flex justify-between">
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
                        <a-range-picker @change="handleDateChange" v-model:value="dateRangeValue" class="mb-5 mr-2" />
                        <a-select placeholder="Select Business Unit " ref="select" v-model:value="selectBunit"
                            style="width: 200px" @change="handleSelectBunitChange">
                            <a-select-option v-for="(item, key) in buData" :key="key"
                                v-model:value="item.businessunit_id">{{
                                    item.bname
                                }}</a-select-option>
                        </a-select>
                        <a-button @click="fetchData" class="ml-5" style="width: 200px;" type="primary" ghost
                            :loading="loadingbutton">
                            <template #icon>
                                <LoginOutlined />
                            </template>
                            fetch data
                        </a-button>
                    </div>
                    <div>
                        <a-button :disabled="data.data.length <= 0" style="width: 230px;" type="primary"
                            @click="startGenerating" :loading="loadingGenButton">
                            <template #icon>
                                <CloudDownloadOutlined />
                            </template>
                            {{ loadingGenButton ? 'Generating Excel On Progress...' : 'Start Generating Cheques' }}
                        </a-button>
                    </div>
                </div>
                <a-table :data-source="data.data" :columns="columns" size="small" bordered :pagination="false">
                </a-table>
                <pagination class="mt-6" :datarecords="data" />
            </div>
        </div>
</template>
<script>
import Pagination from "@/Components/Pagination.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import dayjs from "dayjs";

export default {
    layout: AdminLayout,
    props: {
        buData: Object,
        data: Array,
        columns: Array,
        dateRangeBackend: Array,
        buniIdType: Number,
    },
    data() {
        return {
            progressBarShowing: false,
            disabledFetch: false,
            progressBar: {
                percentage: 0,
                department: "",
                message: "",
                totalRows: 0,
                currentRow: 0,
            },
            loadingbutton: false,
            loadingGenButton: false,
            dateRangeArray0: null,
            dateRangeArray1: null,
            selectBunit: this.buniIdType,
            dateRangeValue: this.dateRangeBackend?.length > 0 ?
                [
                    dayjs(this.dateRangeBackend[0]),
                    dayjs(this.dateRangeBackend[1]),
                ]
                : null,
        }
    },
    methods: {
        handleSelectBunitChange(value) {
            this.selectBunit = value;
        },
        fetchData() {
            this.loadingbutton = true;
            this.$inertia.get(route('deposited.checks'), {
                dateRangeArr0: this.dateRangeArray0,
                dateRangeArr1: this.dateRangeArray1,
                bunitId: this.selectBunit,
            }, {
                onSuccess: () => {
                    this.loadingbutton = false;
                }
            });
        },
        handleDateChange(dateObj, dateStr) {
            this.dateRangeArray0 = dateStr[0];
            this.dateRangeArray1 = dateStr[1];

        },
        startGenerating() {
            this.progressBarShowing = true;
            this.loadingGenButton = true;
            this.$inertia.get(route('startgenerate.depchecks'), {
                dateRangeArr0: this.dateRangeValue[0] === undefined ? null : dayjs(this.dateRangeValue[0]).format(
                    "YYYY-MM-DD"
                ),
                dateRangeArr1: this.dateRangeValue[1] === undefined ? null : dayjs(this.dateRangeValue[1]).format(
                    "YYYY-MM-DD"
                ),

                bunitId: this.selectBunit,
            });
        },
    },
    mounted() {
        this.$ws
            .private(`generating-deposited-checks.${this.$page.props.auth.user.id}`)
            .listen(".generating-deposited", (e) => {
                this.progressBar = e;
            });



    }
}
</script>
