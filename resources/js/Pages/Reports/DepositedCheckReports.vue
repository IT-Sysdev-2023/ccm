<template>

    <Head title="Deposited Check Reports" />
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
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'details'">
                        <a-button size="small" @click="details(record)" :loading="isLoadingBtn">
                            <template #icon>
                                <SettingOutlined></SettingOutlined>
                            </template>
                        </a-button>
                    </template>
                </template>
            </a-table>
            <pagination class="mt-6" :datarecords="data" />
        </div>
    </div>
    <a-modal v-model:open="isOpenModal" title="Basic Modal" style="width: 900px;" :footer="false">
        <a-table :data-source="selectDataDetails" :columns="selectedColumns" size="small" bordered>
            <template #bodyCell="{ column, record }">
                <template v-if="column.key === 'details'">
                    <a-button size="small" @click="detailsSelected(record)">
                        <template #icon>
                            <SettingOutlined></SettingOutlined>
                        </template>
                    </a-button>
                </template>
            </template>
        </a-table>
    </a-modal>
    <CheckModalDetail v-model:open="selectedIsOpen" :datarecords="selectedDetails"></CheckModalDetail>
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
            isOpenModal: false,
            selectedIsOpen: false,
            selectDataDetails: [],
            selectedDetails: [],
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

            selectedColumns: [
                {
                    title: 'Ds Date',
                    dataIndex: 'date_deposit',
                    key: 'id'
                },
                {
                    title: 'Check Date',
                    dataIndex: 'check_date',
                    key: 'id'
                },
                {
                    title: 'Bank',
                    dataIndex: 'bankbranchname',
                    key: 'id'
                },
                {
                    title: 'Customer',
                    dataIndex: 'fullname',
                    key: 'id'
                },
                {
                    title: 'Check No',
                    dataIndex: 'check_no',
                    key: 'id'
                },
                {
                    title: 'Amount',
                    dataIndex: 'check_amount',
                    key: 'id'
                },
                {
                    title: 'Ds No',
                    dataIndex: 'ds_no',
                    key: 'id'
                },
                {
                    title: 'Details',
                    key: 'details',
                    align: 'center'
                },
            ],
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
        detailsSelected(data) {

            this.selectedIsOpen = true;
            this.selectedDetails = data;
        },
        details(data) {
            console.log(data)
            axios.get(`/details/bounce`, {
                params: {
                    ds_no: data.ds_no,
                    bunit: this.selectBunit,
                    date: data.date_deposit
                }
            })
                .then(response => {
                    this.selectDataDetails = response.data;
                    this.isOpenModal = true;
                })
                .catch(error => {

                    console.error('Error fetching image details:', error);
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
