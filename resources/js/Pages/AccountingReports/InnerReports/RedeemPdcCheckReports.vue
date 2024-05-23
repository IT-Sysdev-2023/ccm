<template>

    <Head title="Redeem Pdc Check Reports" />
    <div class="ml-10">
        <a-breadcrumb>
            <a-breadcrumb-item href="">
                <home-outlined />
            </a-breadcrumb-item>
            <a-breadcrumb-item href="">
                <user-outlined />
                <span>Accounting Reports</span>
            </a-breadcrumb-item>
            <a-breadcrumb-item>Redeem Post Dated Reports</a-breadcrumb-item>
        </a-breadcrumb>
    </div>
    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="font-bold text-center mb-6">
                <p>
                    {{ bunit[0].bname }}
                </p>
            </div>


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


            <a-card>
                <div class="font-bold text-right text-gray-500 mb-5">
                    <p>
                        REPORT TYPE: REDEEM PDC CHEQUES
                    </p>
                </div>
                <div class="flex justify-between">
                    <div>
                        <a-range-picker :disabled="isFetching" style="width: 400px;" class="mr-1 mb-3"
                            v-model:value="dateRange" @change="handleChangeDateRange" />
                    </div>
                    <div>
                        <a-button class="mr-1"type="primary" :loading="isLoading" @click="startGeneratingRepors"
                            :disabled="data.data.length <= 0">
                            <template #icon>
                                <CloudUploadOutlined />
                            </template>
                            Generate Redeem Cheque Reports
                        </a-button>
                        <a-input-search v-model:value="query.search" style="width: 350px;" class="mb-5"
                            placeholder="Search Checks" :loading="isFetching" />
                    </div>
                </div>
                <a-table :data-source="data.data" :columns="columns" size="small" bordered :pagination="false">
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'details'">
                            <a-button size="small" @click="details(record)">
                                <template #icon>
                                    <SettingOutlined></SettingOutlined>
                                </template>
                            </a-button>
                        </template>
                    </template>
                </a-table>
                <pagination :datarecords="data" class="mt-5">

                </pagination>
            </a-card>
        </div>
    </div>
    <CheckModalDetail v-model:open="isOpenModal" :datarecords="selectDataDetails"></CheckModalDetail>

</template>
<script>
import dayjs from 'dayjs';
import debounce from "lodash/debounce";
import AccountingLayout from '@/Layouts/AccountingLayout.vue';
export default {
    layout: AccountingLayout,
    props: {
        data: Object,
        bunit: Object,
        columns: Array,
        dateRangeBackend: Array,
    },
    data() {
        return {
            isGeneratingShow: false,
            query: {
                search: '',
            },
            progressBar: {
                currentRow: 0,
                percentage: 0,
                message: "",
                totalRows: 0,
            },
            isOpenModal: false,
            selectDataDetails: [],
            isLoading: false,
            isFetching: false,
            dateRange: this.dateRangeBackend ? [this.dateRangeBackend[0] ? dayjs(this.dateRangeBackend[0]) : null, this.dateRangeBackend[1] ? dayjs(this.dateRangeBackend[1]) : null] : null,
        }
    },
    methods: {
        details(data) {
            this.isOpenModal = true;
            this.selectDataDetails = data;
        },
        handleChangeDateRange(dateObj, dateStr) {
            this.isFetching = true,
                this.$inertia.get(route('redeem.reports.accounting'), {
                    dateFrom: dateStr[0],
                    dateTo: dateStr[1],
                })
        },
        startGeneratingRepors() {
            this.isLoading = true;
            this.isGeneratingShow = true;
            this.$inertia.get(route('start.generating.redpdc.accounting'), {
                dateFrom: this.dateRangeBackend[0],
                dateTo: this.dateRangeBackend[1],
                reDirect: 1,
            })
        }
    },
    mounted() {
        this.$ws
            .private(`generating-redeem-reports.${this.$page.props.auth.user.id}`)
            .listen(".generating-redeem-reports", (e) => {
                this.progressBar = e;
            });
    },
    watch: {

        query: {
            deep: true,
            handler: debounce(async function () {
                this.isFetching = true,
                    this.$inertia.get(route("redeem.reports.accounting"), {
                        searchQuery: this.query.search,
                        dateFrom: this.dateRangeBackend[0],
                        dateTo: this.dateRangeBackend[1],
                    }, {
                        preserveState: true,
                        onSuccess: () => {
                            this.isFetching = false;
                        },
                        onError: () => {
                            this.isFetching = false; // Ensure the flag is reset on error
                        }
                    }
                    );
            }, 600),
        },
    },
}
</script>
