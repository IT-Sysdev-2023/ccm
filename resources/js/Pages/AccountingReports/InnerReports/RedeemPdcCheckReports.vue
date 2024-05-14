<script setup>
import AccountingLayout from '@/Layouts/AccountingLayout.vue';
</script>

<template>

    <Head title="Dashboard" />

    <AccountingLayout>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="font-bold text-center mb-6">
                    <p>
                        {{ bunit[0].bname }}
                    </p>
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
                            <a-button type="primary" :loading="isLoading" @click="startGeneratingRepors" :disabled="data.data.length <= 0" >
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
                </a-card>
            </div>
        </div>
    </AccountingLayout>
</template>
<script>
import dayjs from 'dayjs';
export default {
    props: {
        data: Array,
        bunit: Object,
        columns: Array,
        dateRangeBackend: Array,
    },
    data() {
        return {
            isLoading: false,
            isFetching: false,
            dateRange: this.dateRangeBackend ? [this.dateRangeBackend[0] ? dayjs(this.dateRangeBackend[0]) : null, this.dateRangeBackend[1] ? dayjs(this.dateRangeBackend[1]) : null] : null,
        }
    },
    methods: {
        handleChangeDateRange(dateObj, dateStr) {
            this.isFetching = true,
                this.$inertia.get(route('redeem.reports.accounting'), {
                    dateFrom: dateStr[0],
                    dateTo: dateStr[1],
                })
        },
        startGeneratingRepors() {
            this.isLoading = true;
            this.$inertia.get(route('start.generating.redpdc.accounting'), {
                dateFrom: this.dateRangeBackend[0],
                dateTo: this.dateRangeBackend[1],
            })
        }
    }
}
</script>
