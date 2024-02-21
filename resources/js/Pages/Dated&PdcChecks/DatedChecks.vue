<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
import { Head } from '@inertiajs/vue3';
import { InfoCircleOutlined, UserOutlined, SearchOutlined, MenuFoldOutlined, FileSyncOutlined } from '@ant-design/icons-vue'
import { ref } from 'vue';

const size = ref('large');
</script>

<template>
    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">This is treasury Dashboard</h2>
        </template>
        <div class="py-0">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mt-3 mb-3">
                    <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                    <a-breadcrumb-item><a href="">Dated Checks/Pdc</a></a-breadcrumb-item>
                    <a-breadcrumb-item>Dated Checks</a-breadcrumb-item>
                </a-breadcrumb>
                <a-table :data-source="data" class="components-table-demo-nested" :columns="columns" size="middle" bordered>
                </a-table>
            </div>
        </div>

        <a-modal v-model:open="isModalOpen" title="Details" style="top: 20px; width: 1000px;" @ok="setModal1Visible(false)"
            :footer="null">
            <div class="product-container">
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l  border-t border-gray-200">
                                Customer Name</td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-t border-gray-200">{{
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
export default {
    props: {
        data: Array,
        columns: Array
    },


    mounted() {
        // this.data;

        console.log(this.data);

    }
}
</script>
<style scoped>
body {
    font-family: Arial, sans-serif;
}


.product-container {
    margin: 20px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
}

.product-container tr {
    border: 1px solid #ddd;
}

.product-container td {
    border: 1px solid #ddd;
}
</style>