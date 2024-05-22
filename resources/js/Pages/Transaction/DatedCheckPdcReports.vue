<template>

    <Head title="Dated Checks Pdc Reports" />

    <div class="py-0">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <a-breadcrumb class="mt-2 mb-3">
                <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                <a-breadcrumb-item><a href="">Transaction </a></a-breadcrumb-item>
                <a-breadcrumb-item>Partial Payments</a-breadcrumb-item>
            </a-breadcrumb>
            <div v-if="isProgressShowing" style="font-size: 14px;">
                <div>
                    <div v-if="isProgressShowing" class="mb-10">
                        <div class="flex justify-between">
                            <div>
                                <p> {{ progressBar.message }} {{ progressBar.department }} {{ progressBar.currentRow
                                    }}
                                    to
                                    {{
                                        progressBar.totalRows }}</p>
                            </div>

                            <div>
                                <span class="font-bold">{{ progressBar.preProcess }} </span> out of
                                <span class="font-bold">{{ progressBar.departmentToBeProcessed }}</span>
                            </div>
                        </div>
                        <a-progress :stroke-color="{
                            from: '#108ee9', to: '#87d068',
                        }" :percent="progressBar.percentage" status="active" />
                    </div>


                </div>
            </div>
            <a-card>


                <div class="flex justify-between mb-10">
                    <div>
                        <a-select ref="select" @change="handleChangeStatus" v-model:value="statusValue" class="mr-2"
                            placeholder="Select Status Checks Report" style="width: 250px" @focus="focus">
                            <a-select-option value="1">Dated Check Report</a-select-option>
                            <a-select-option value="2">Post Dated Check Report</a-select-option>
                        </a-select>
                        <a-range-picker @change="handleDateGenerate" v-model:value="dateRange" />
                        <a-button @click="startGenerating" class="ml-2" type="primary" :loading="isLoading" :disabled="!statusValue ||
                            !dateRange ||
                            !data.data.length
                            ">
                            <template #icon>
                                <UploadOutlined />
                            </template>
                            {{ isLoading ? 'Generating in progress please wait it will takes some time...' :
                                'GenerateDated and Post Dated Check report excel' }}
                        </a-button>
                    </div>
                    <div>
                        <a-input-search v-model:value="query.search" style="width: 350px;" class="mb-5"
                            placeholder="Search Checks" :loading="isFetching" />
                    </div>
                </div>

                <a-table :data-source="data.data" :pagination="false" :columns="columns" size="small" bordered>
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'details'">
                            <a-button size="small" class="mx-1" @click="openUpDetails(record)">
                                <template #icon>
                                    <SettingOutlined />
                                </template>
                            </a-button>
                        </template>
                    </template>
                </a-table>
                <pagination class="mt-6" :datarecords="data" />
            </a-card>
        </div>
    </div>
    <CheckModalDetail v-model:open="openDetails" :datarecords="selectDataDetails"></CheckModalDetail>
</template>

<script>
import dayjs from "dayjs";
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import { message } from "ant-design-vue";
import Pagination from "@/Components/Pagination.vue";
import debounce from "lodash/debounce";

export default {
    layout: TreasuryLayout,
    props: {
        data: Array,
        columns: Array,
        statusReport: Object,
        dateRangeValue: Array,
        filters: Array,
        status: Object,
    },
    data() {
        return {
            query: {
                search: '',
            },
            isFetching: false,
            progressBar: {
                currentRow: 0,
                percentage: 0,
                department: "",
                message: "",
                totalRows: 0,
                preProcess: 0,
                departmentToBeProcessed: 0
            },
            statusValue: this.status,
            dateRange: [
                dayjs(this.dateRangeValue[0]),
                dayjs(this.dateRangeValue[1]),
            ],
            isLoading: false,
            openDetails: false,
            selectDataDetails: [],
            isProgressShowing: false,
        };
    },
    mounted() {
        this.$ws
            .private(`excel-progress.${this.$page.props.auth.user.id}`)
            .listen(".generate-excel", (e) => {
                this.progressBar = e;
            });
    },

    methods: {
        handleDateGenerate(obj, str) {
            console.log(this.dateRangeValue);
            this.$inertia.get(
                route("dcpdc.checks"),
                {
                    status: this.statusValue,
                    date_from: str[0],
                    date_to: str[1],
                },
                {
                    preserveState: true,
                }
            );
        },
        handleChangeStatus() {
            this.$inertia.get(
                route("dcpdc.checks"),
                {
                    status: this.statusValue,
                    date_from: this.filters.date_from || "",
                    date_to: this.filters.date_to || "",
                },
                {
                    preserveState: true,
                }
            );
        },
        startGenerating() {
            this.isProgressShowing = true;
            this.isLoading = true;

            this.$inertia.get(route('generate_report.checks'), {
                status: this.statusValue,
                date_from: dayjs(this.dateRangeValue[0]).format("YYYY-MM-DD"),
                date_to: dayjs(this.dateRangeValue[1]).format("YYYY-MM-DD"),
            });
        },

        openUpDetails(inData) {
            this.openDetails = true;
            this.selectDataDetails = inData;
        },
    },
    watch: {
        query: {
            deep: true,
            handler: debounce(async function () {
                this.isFetching = true,
                    this.$inertia.get(route("dcpdc.checks"), {
                        searchQuery: this.query.search,
                        status: this.statusValue,
                        date_from: dayjs(this.dateRangeValue[0]).format("YYYY-MM-DD"),
                        date_to: dayjs(this.dateRangeValue[1]).format("YYYY-MM-DD"),
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
};
</script>

<style>
.product-table {
    margin: 20px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
}

.product-table tr {
    border: 1px solid #ddd;
}

.product-table td {
    border: 1px solid #ddd;
}
</style>
