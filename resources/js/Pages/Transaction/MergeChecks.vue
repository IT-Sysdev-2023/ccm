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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mb-2 mt-2">
                    <a-breadcrumb-item href="">
                        <HomeOutlined />
                    </a-breadcrumb-item>
                    <a-breadcrumb-item href="">
                        <span>Dashboard</span>
                    </a-breadcrumb-item>
                    <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    <a-breadcrumb-item>Merge Checks</a-breadcrumb-item>
                </a-breadcrumb>
                <a-table :loading="isLoadingTable" :dataSource="data.data" :columns="columns" size="small" bordered
                    :pagination="false">
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'check_box'">
                            <a-switch size="small">
                                <template #checkedChildren><check-outlined /></template>
                                <template #unCheckedChildren><close-outlined /></template>
                            </a-switch>
                        </template>
                        <template v-if="column.key === 'action'">
                            <a-button size="square" class="mx-2" @click="openUpDetails(record)">
                                <template #icon>
                                    <SettingOutlined />
                                </template>
                            </a-button>
                        </template>
                    </template>
                </a-table>
                <div class="mt-3 mb-5" style="
                        border: 1px solid rgb(224, 224, 224);
                        border-radius: 10px;
                        padding: 10px;
                    ">
                    <div class="flex justify-end">
                        <a-pagination class="mt-0 mb-0" v-model:current="pagination.current"
                            v-model:page-size="pagination.pageSize" :show-size-changer="false" :total="pagination.total"
                            :show-total="(total, range) =>
                                `${range[0]}-${range[1]} of ${total} reports`
                                " @change="handleTableChange" />
                    </div>
                </div>
            </div>
        </div>
        <a-modal v-model:open="openDetails" style="top: 25px" width="1000px" title="Details" @ok="handleOk"
            :ok-button-props="{ hidden: true }" :cancel-button-props="{ hidden: true }" :footer="null">
            <div class="product-table">
                <table class="min-w-full divide-y divide-gray-200">

                    <tbody>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l  border-t border-gray-200">
                                Customer Name</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-t border-gray-200">
                                {{
                                    selectDataDetails.fullname }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check From</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.department }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Number</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.check_no }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Approving Officer</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.approving_officer }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Class</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.check_class }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Status</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.check_status }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Date</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.check_date }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Account No</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.account_no }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Received</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.check_received }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Account Name</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.account_name }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Received As</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.check_type }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Bank Name</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.bankbranchname }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Category</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.check_category }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Amount</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">{{
                                selectDataDetails.check_amount }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </a-modal>
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
    CloseOutlined

} from '@ant-design/icons-vue';
export default {
    data() {
        return {
            isLoadingTable: false,
            selectDataDetails: {},
        }
    },
    props: {
        data: Array,
        columns: Array,
        pagination: Array,
    },
    methods: {
        handleTableChange(page = 1) {
            this.isLoadingTable = true;
            this.$inertia.get(route("mergechecks.checks"), {
                page: page,
            }, {
                preserveScroll: true,
            })
        },
        openUpDetails(dataIn) {
            this.openDetails = true;
            this.selectDataDetails = dataIn;
        }
    },
}
</script>