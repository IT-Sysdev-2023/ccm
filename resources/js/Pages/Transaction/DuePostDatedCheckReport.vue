<template>

    <Head title="Due Post Dated Checks Report" />

    <div class="py-0">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <a-breadcrumb class="mt-2 mb-3">
                <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                <a-breadcrumb-item><a href="">Transaction </a></a-breadcrumb-item>
                <a-breadcrumb-item>Due Post Dated Checks Report</a-breadcrumb-item>
            </a-breadcrumb>
            <div v-if="isProgressShowing" style="font-size: 14px">
                <div class="flex justify-between">
                    <div class="flex">
                        <DoubleRightOutlined class="mr-2" />
                        <p>
                            {{ progressBar.message }}
                            {{ progressBar.department }}
                            {{ progressBar.currentRow }} to
                            {{ progressBar.totalRows }} records
                        </p>
                    </div>
                    <div class="flex">
                        <InfoCircleTwoTone class="mr-2" />
                        <p>
                            "Please note: Do not exit the page while
                            generating or navigate to another link within
                            this system."
                        </p>
                    </div>
                </div>
                <a-progress :stroke-color="{
                    from: '#108ee9',
                    to: '#87d068',
                }" :percent="progressBar.percentage" status="active" />
            </div>
            <div class="text-center mb-10 mt-4">
                <h2>{{ buname.bname }}</h2>
            </div>
            <div class="flex justify-between mb-10">
                <div>
                    <a-range-picker @change="handleDateGenerate" v-model:value="form.dateRange" />
                    <a-button class="ml-2" type="primary" :loading="isLoading"
                        :disabled="!data.data.length">
                        <template #icon>
                            <UploadOutlined />
                        </template>
                        {{
                            isLoading
                                ? "Generating due post dated check report..."
                                : "Generate Due Post Dated Check Report Excel"
                        }}
                    </a-button>
                </div>
                <div>


                    <a-input-search v-model:value="form.search" style="width: 350px;" class="mb-5"
                        placeholder="Search Checks" :loading="isFetching" />

                </div>
            </div>
            <a-table :data-source="data.data" :columns="columns" bordered size="small" :pagination="false">
                <template #bodyCell="{ column, record }">
                    <template v-if="column.dataIndex">
                        <span v-html="highlightText(record[column.dataIndex], form.search)
                            ">
                        </span>
                    </template>
                    <template v-if="column.key === 'details'">
                        <a-button class="mx-1" @click="openUpDetails(record)">
                            <template #icon>
                                <SettingOutlined />
                            </template>
                        </a-button>
                    </template>
                </template>
            </a-table>
            <pagination class="mt-6 mb-10" :datarecords="data" />
        </div>
    </div>

    <CheckModalDetail v-model:open="openDetails" :datarecords="selectDataDetails"></CheckModalDetail>
</template>

<script>
import dayjs from "dayjs";
import { message } from "ant-design-vue";
import Pagination from "@/Components/Pagination.vue";
import { InfoCircleTwoTone } from "@ant-design/icons-vue";
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import debounce from "lodash/debounce";
import { highlighten } from "@/Mixin/highlighten.js";

export default {
    layout: TreasuryLayout,
    props: {
        data: Object,
        columns: Array,
        buname: Object,
        filters: Object

    },
    setup() {
        const { highlightText } = highlighten();
        return { highlightText };
    },
    data() {
        return {
            form: {
                search: this.filters.search,
                dateRange: this.filters.date_from ? [dayjs(this.filters.date_from),  dayjs(this.filters.date_to)]: null,
            },
            isLoading: false,
            openDetails: false,
            selectDataDetails: [],
            isProgressShowing: false,
            progressBar: {
                currentRow: 0,
                percentage: 0,
                department: "",
                message: "",
                totalRows: 0,
            },
        };
    },
    methods: {
        openUpDetails(inData) {
            this.openDetails = true;
            this.selectDataDetails = inData;
        },

        startGenerating() {
            this.isProgressShowing = true;
            this.isLoading = true;
            this.$inertia.post(route("generate_duepdcrep.checks"),
                {
                    date_from: this.filters.date_from ? dayjs(this.filters.date_from).format("YYYY-MM-DD") : null,
                    date_to: this.filters.date_to ? dayjs(this.filters.date_to).format("YYYY-MM-DD") : null,
                },
                {
                    onFinish: () => {
                        this.isLoading = false;
                    },
                }
            );
        },
    },
    mounted() {
        this.$ws
            .private(`excel-progress.${this.$page.props.auth.user.id}`)
            .listen(".generate-excel", (e) => {
                this.progressBar = e;
            });
    },
    watch: {
        form: {
            deep: true,
            handler: debounce(async function () {
                this.isFetching = true,
                    this.$inertia.get(route("duePdcReports.checks"), {
                        search: this.form.search,
                        date_from: this.form.dateRange ? dayjs(this.form.dateRange[0]).format('YYYY-MM-DD') : null,
                        date_to: this.form.dateRange ? dayjs(this.form.dateRange[1]).format('YYYY-MM-DD') : null,
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
