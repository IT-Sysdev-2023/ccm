<template>

    <Head title="Bounce Checks Report" />

    <div class="ml-20">
        <a-breadcrumb>
            <a-breadcrumb-item href="">
                <home-outlined />
            </a-breadcrumb-item>
            <a-breadcrumb-item href="">
                <user-outlined />
                <span>Accounting Reports</span>
            </a-breadcrumb-item>
            <a-breadcrumb-item>Bounce Check Reports</a-breadcrumb-item>
        </a-breadcrumb>
    </div>
    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center font-bold mb-2">
                <HomeOutlined /> {{ bunit[0].bname }}
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
                <div class="flex  mb-2 justify-between">

                    <div class="flex">
                        <div>
                            <a-breadcrumb>
                                <a-breadcrumb-item>
                                    <CalendarOutlined />
                                </a-breadcrumb-item>
                                <a-breadcrumb-item>Select Date Range</a-breadcrumb-item>
                            </a-breadcrumb>
                            <a-range-picker class="mr-1" v-model:value="fetch.dateRange"
                                @change="handleChangeDateRange" />
                        </div>
                        <div>
                            <a-breadcrumb>
                                <a-breadcrumb-item>
                                    <FileTextOutlined />
                                </a-breadcrumb-item>
                                <a-breadcrumb-item>Select Status</a-breadcrumb-item>
                            </a-breadcrumb>
                            <a-select placeholder="Select Status" v-model:value="fetch.dataStatus"
                                @change="handleChangeDataStatus" style="width: 210px" :loading="isFetching"
                                :disabled="isFetching">
                                <a-select-option value="0">View All</a-select-option>
                                <a-select-option value="1">Pending Deposit Cheques</a-select-option>
                                <a-select-option value="2">Settled Cheques</a-select-option>
                            </a-select>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a-button class="mr-1" type="primary" @click="startGeneratingExcel"
                            :disabled="data.data?.length <= 0 || data.data === undefined" :loading="isLoading">
                            <template #icon>
                                <CloudUploadOutlined />
                            </template>
                            {{
                                isLoading ? 'Generating Cheques in Progress..' : 'Generate Bounce Cheques Reports'
                            }}
                        </a-button>
                        <a-input-search v-model:value="query.search" style="width: 350px;" class="mb-5"
                        placeholder="Search Checks" :loading="isFetching" />
                    </div>
                </div>
                <a-table class="mt-10" :data-source="data.data" :columns="columns" size="small" bordered
                    :pagination="false">
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
                <pagination :datarecords="data" class="mt-6" />
            </a-card>
        </div>
    </div>

    <CheckModalDetail v-model:open="isOpenModal" :datarecords="selectDataDetails"></CheckModalDetail>
</template>
<script>
import debounce from "lodash/debounce";
import dayjs from 'dayjs';
import AccountingLayout from '@/Layouts/AccountingLayout.vue';
export default {
    layout: AccountingLayout,
    props: {
        data: Object,
        bunit: Object,
        columns: Array,
        dateRangeBackend: Array,
        dataSatusBackend: Number,
    },
    data() {
        return {
            query: {
                search: '',
            },
            isGeneratingShow: false,
            isLoading: false,
            isFetching: false,
            fetch: {
                dataStatus: this.dataSatusBackend,
                dateRange: this.dateRangeBackend ? [this.dateRangeBackend[0] ? dayjs(this.dateRangeBackend[0]) : null, this.dateRangeBackend[1] ? dayjs(this.dateRangeBackend[1]) : null] : null,
            },
            isOpenModal: false,
            selectedDataDetails: [],
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
        details(data) {
            this.isOpenModal = true;
            this.selectDataDetails = data;
        },

        handleChangeDateRange(dateobj, dateStr) {
            this.isFetching = true,
                this.$inertia.get(route('bounce.checks.accounting'), {
                    dateFrom: dateStr[0],
                    dateTo: dateStr[1],
                });
        },
        handleChangeDataStatus() {
            this.isFetching = true;
            this.$inertia.get(route('bounce.checks.accounting'), {
                bounceStatus: this.fetch.dataStatus,
                dateFrom: this.dateRangeBackend[0],
                dateTo: this.dateRangeBackend[1],
            });
        },
        startGeneratingExcel() {
            this.isLoading = true;
            this.isGeneratingShow = true;
            this.$inertia.get(route('start.bounce.accounting'), {
                bounceStatus: this.dataSatusBackend,
                dateFrom: this.dateRangeBackend[0],
                dateTo: this.dateRangeBackend[1],
            });
        }
    },
    mounted() {
        this.$ws
            .private(`generating-bounce-checks.${this.$page.props.auth.user.id}`)
            .listen(".generating-bounced", (e) => {
                this.progressBar = e;
            });
    },
    watch: {

        query: {
            deep: true,
            handler: debounce(async function () {
                this.isFetching = true,
                    this.$inertia.get(route("bounce.checks.accounting"), {
                        searchQuery: this.query.search,
                        bounceStatus: this.dataSatusBackend,
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
