<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">This is treasury Dashboard</h2>
        </template>

        <div class="py-0">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mt-2 mb-3">
                    <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                    <a-breadcrumb-item><a href="">Transaction </a></a-breadcrumb-item>
                    <a-breadcrumb-item>Partial Payments</a-breadcrumb-item>
                </a-breadcrumb>
                <a-card>
                    <a-select ref="select" placeholder="Select Status Checks Report" v-model:value="datedpdcSelect"
                        style="width: 250px" @focus="focus" class="mb-2 mx-1">
                        <a-select-option value="1">Dated Check Report</a-select-option>
                        <a-select-option value="2">Post Dated Check Report</a-select-option>
                    </a-select>
                    <a-range-picker @change="handleChangeAll" v-model:value="dateRangeValue" />
                    <a-table :data-source="data.data" :pagination="false" :columns="columns" size="small" bordered>
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'action'">
                                <a-button size="square" class="mx-1" @click="openUpDetails(record)">
                                    <template #icon>
                                        <SettingOutlined />
                                    </template>
                                </a-button>
                            </template>
                        </template>
                    </a-table>
                    <pagination class="mt-6" :datarecords="data"></pagination>
                </a-card>
            </div>
        </div>
    </TreasuryLayout>
</template>

<script>
import {
    SettingOutlined,
    TagOutlined,
    FolderAddOutlined,
    HomeOutlined,
    SaveOutlined,
    AccountBookOutlined,
    CalendarOutlined,
    MoneyCollectOutlined,
    UsergroupAddOutlined,
    BankOutlined,
    UserOutlined,
    InfoCircleOutlined,
    CheckOutlined,
    CloseOutlined,
    CreditCardFilled

} from '@ant-design/icons-vue';
import dayjs from 'dayjs';
import Pagination from "@/Components/Pagination.vue"
export default {
    props: {
        data: Array,
        columns: Array,
        statusReport: Object,
        dateRange: Array,
    },
    data() {
        return {
            datedpdcSelect: this.statusReport,
            dateRangeValue: [dayjs(this.dateRange[0]), dayjs(this.dateRange[1])]
        }
    },

    methods: {
        handleChangeAll(obj, str) {

            this.$inertia.get(route("dcpdc.checks"), {
                status: this.datedpdcSelect,
                date_from: str[0],
                date_to: str[1]
            });
        }
    }
}
</script>