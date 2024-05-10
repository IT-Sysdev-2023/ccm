<script setup>
import AccountingLayout from '@/Layouts/AccountingLayout.vue';
</script>

<template>

    <Head title="Dashboard" />

    <AccountingLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">this is accounting Dashboard</h2>
        </template>
        <div class="py-12">
            <div class="text-center font-bold mb-10">
                <HomeOutlined /> {{ bunit[0].bname }}
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="isProgressing">
                    <div class="flex justify-between">
                        <div class="flex">
                            <DoubleRightOutlined />
                            <DoubleRightOutlined class="mr-1" />
                            <p>
                                {{ progressBar.message }}
                                {{ progressBar.currentRow }} to
                                {{ progressBar.totalRows }} records
                            </p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <a-progress class="mt-2" :stroke-color="{
                            from: '#108ee9',
                            to: '#87d068',
                        }" :percent="progressBar.percentage" status="active" />

                    </div>
                </div>
                <div class="flex justify-between">
                    <div>
                        <a-breadcrumb>
                            <a-breadcrumb-item>
                                <CalendarOutlined />
                            </a-breadcrumb-item>
                            <a-breadcrumb-item>Select Date</a-breadcrumb-item>
                        </a-breadcrumb>

                        <a-range-picker v-model:value="dateValue" @change="handleChangeDateRange" class="mb-5"
                            style="width: 350px;" />
                    </div>
                    <a-button type="primary" @click="startGeneratingDepositedChecks" :loading="isLoading"
                        :disabled="data.data.length <= 0">
                        <template #icon>
                            <CloudDownloadOutlined />
                        </template>
                        {{ isLoading ? 'Generate deposited checks' : 'Generating deposit in progress...' }}
                    </a-button>
                </div>

                <a-table :data-source="data.data" :columns="columns" size="small" :pagination="false" bordered>

                </a-table>
                <pagination :datarecords="data" class="mt-4 mb-6" />

            </div>
        </div>
    </AccountingLayout>
</template>

<script>
import dayjs from 'dayjs';
export default {
    props: {
        data: Array,
        columns: Array,
        dateRangeBackend: Object,
        bunit: Object,
    },
    data() {
        return {
            isProgressing: false,
            isLoading: false,
            dateValue: this.dateRangeBackend ? [this.dateRangeBackend[0] ? dayjs(this.dateRangeBackend[0]) : null, this.dateRangeBackend[1] ? dayjs(this.dateRangeBackend[1]) : null] : null,
            progressBar: {
                currentRow: 0,
                percentage: 0,
                message: "",
                totalRows: 0,
            },
        }
    },
    mounted() {
        this.$ws
            .private(`generating-deposited-checks-accounting.${this.$page.props.auth.user.id}`)
            .listen(".generating-deposited-accounting", (e) => {
                this.progressBar = e;
            });
    },

    methods: {
        handleChangeDateRange(dateObject, datestr) {
            this.$inertia.get(route('deposited.reports.accounting'), {
                dateFrom: datestr[0],
                dateTo: datestr[1],
            });
        },
        startGeneratingDepositedChecks() {
            this.isLoading = true;
            this.isProgressing = true;
            this.$inertia.get(route('start.generate.rep,deposited.accouting'), {
                dateFrom: dayjs(this.dateValue[0]).format('YYYY-MM-DD'),
                dateTo: dayjs(this.dateValue[1]).format('YYYY-MM-DD'),
            });
        }
    }
}
</script>
