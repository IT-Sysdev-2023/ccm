<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
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
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mt-3 mb-3">
                    <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                    <a-breadcrumb-item><a href="">Dated Checks/Pdc</a></a-breadcrumb-item>
                    <a-breadcrumb-item>Pending Dated Checks</a-breadcrumb-item>
                </a-breadcrumb>
                <a-page-header style="border: 1px solid rgb(235, 237, 240)" title="Post Dated Checks"
                    sub-title="This is the table for Post dated checks" @back="() => null" />
                <a-card>
                    <a-table :loading="isLoadingTbl" :pagination="false" :dataSource="data.data"
                        class="components-table-demo-nested" :columns="columns" size="small" bordered>
                        <template #bodyCell="{ column, record, index }">
                            <template v-if="column.key === 'action'">
                                <a-button size="small" @click="openModaldated(record)">
                                    <template #icon>
                                        <!-- <FullscreenOutlined /> -->
                                        <SettingOutlined />
                                    </template>
                                </a-button>
                                <a-button type="primary" class="mx-2" size="small" @click="showModalReplace(record)">
                                    <template #icon>
                                        <FileSyncOutlined />
                                    </template>
                                </a-button>
                            </template>
                        </template>
                    </a-table>
                    <pagination class="mt-6" :datarecords="data" />
                </a-card>
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
        <a-modal v-model:open="openModalReplace" title="Basic Modal" style="top: 20px; width: 100%;"
            wrap-class-name="full-modal" @ok="handleOk">
            <a-row :gutter="[16, 16]">
                <a-col :span="6">
                    <a-card>
                        <a-breadcrumb class="mt-2 ml-3">
                            <a-breadcrumb-item>Check Number</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-input v-model:value="userName" class="" placeholder="Basic usage">
                            <template #prefix>
                                <UserOutlined />
                            </template>
                            <template #suffix>
                                <a-tooltip title="Extra information">
                                    <InfoCircleOutlined />
                                </a-tooltip>
                            </template>
                        </a-input>
                        <a-breadcrumb class="mt-2 ml-3">
                            <a-breadcrumb-item>Check Amount</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-input v-model:value="userName" class="" placeholder="Basic usage">
                            <template #prefix>
                                <UserOutlined />
                            </template>
                            <template #suffix>
                                <a-tooltip title="Extra information">
                                    <InfoCircleOutlined />
                                </a-tooltip>
                            </template>
                        </a-input>
                        <a-breadcrumb class="mt-2 ml-3">
                            <a-breadcrumb-item>Check Date</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-input v-model:value="userName" class="" placeholder="Basic usage">
                            <template #prefix>
                                <UserOutlined />
                            </template>
                            <template #suffix>
                                <a-tooltip title="Extra information">
                                    <InfoCircleOutlined />
                                </a-tooltip>
                            </template>
                        </a-input>
                        <a-breadcrumb class="mt-2 ml-3">
                            <a-breadcrumb-item>Replace Date</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-date-picker style="width: 100%;" v-model:value="userName" class="" placeholder="Basic usage">
                            <template #prefix>
                                <UserOutlined />
                            </template>
                            <template #suffix>
                                <a-tooltip title="Extra information">
                                    <InfoCircleOutlined />
                                </a-tooltip>
                            </template>
                        </a-date-picker>
                        <a-breadcrumb class="mt-2 ml-3">
                            <a-breadcrumb-item>Type of Replacement</a-breadcrumb-item>
                        </a-breadcrumb>
                        <a-select ref="select" class="" v-model:value="value1" style="width: 100%"
                            @focus="focus"></a-select>
                    </a-card>
                </a-col>
                <a-col :span="18">
                    <a-card>

                        <a-row :gutter="[16, 16]">
                            <a-col :span="8">
                                <a-breadcrumb class="mt-2 ml-3">
                                    <a-breadcrumb-item>Account Number</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-input v-model:value="userName" class="" placeholder="Basic usage">
                                    <template #prefix>
                                        <UserOutlined />
                                    </template>
                                    <template #suffix>
                                        <a-tooltip title="Extra information">
                                            <InfoCircleOutlined />
                                        </a-tooltip>
                                    </template>
                                </a-input>
                                <a-breadcrumb class="mt-2 ml-3">
                                    <a-breadcrumb-item>Account Name</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-input v-model:value="userName" class="" placeholder="Basic usage">
                                    <template #prefix>
                                        <UserOutlined />
                                    </template>
                                    <template #suffix>
                                        <a-tooltip title="Extra information">
                                            <InfoCircleOutlined />
                                        </a-tooltip>
                                    </template>
                                </a-input>
                                <a-breadcrumb class="mt-2 ml-3">
                                    <a-breadcrumb-item>Check Number</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-input v-model:value="userName" class="" placeholder="Basic usage">
                                    <template #prefix>
                                        <UserOutlined />
                                    </template>
                                    <template #suffix>
                                        <a-tooltip title="Extra information">
                                            <InfoCircleOutlined />
                                        </a-tooltip>
                                    </template>
                                </a-input>
                                <a-breadcrumb class="mt-20 ml-3">
                                    <a-breadcrumb-item>Cash Amount</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-input v-model:value="userName" class="" placeholder="Basic usage">
                                    <template #prefix>
                                        <UserOutlined />
                                    </template>
                                    <template #suffix>
                                        <a-tooltip title="Extra information">
                                            <InfoCircleOutlined />
                                        </a-tooltip>
                                    </template>
                                </a-input>
                                <a-breadcrumb class="mt-2 ml-3">
                                    <a-breadcrumb-item>Reason of return</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-textarea v-model:value="value" placeholder="Basic usage" :rows="4" />
                            </a-col>
                            <a-col :span="8">
                                <a-breadcrumb class="mt-2 ml-3">
                                    <a-breadcrumb-item>Customer Name</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-auto-complete placeholder="Search Cheques" style="width: 100%;">
                                </a-auto-complete>
                                <a-breadcrumb class="mt-2 ml-3">
                                    <a-breadcrumb-item>Bank Name</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-auto-complete placeholder="Search Cheques" style="width: 100%;">
                                </a-auto-complete>
                                <a-breadcrumb class="mt-2 ml-3">
                                    <a-breadcrumb-item>Check From</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-auto-complete placeholder="Search Cheques" style="width: 100%;">
                                </a-auto-complete>

                                <a-breadcrumb class="mt-20 ml-3">
                                    <a-breadcrumb-item>Ar# / Ds#</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-input v-model:value="userName" class="" placeholder="Basic usage">
                                    <template #prefix>
                                        <UserOutlined />
                                    </template>
                                    <template #suffix>
                                        <a-tooltip title="Extra information">
                                            <InfoCircleOutlined />
                                        </a-tooltip>
                                    </template>
                                </a-input>
                                <a-breadcrumb class="mt-2 ml-3">
                                    <a-breadcrumb-item>Penalty Amount</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-input v-model:value="userName" class="" placeholder="Basic usage">
                                    <template #prefix>
                                        <UserOutlined />
                                    </template>
                                    <template #suffix>
                                        <a-tooltip title="Extra information">
                                            <InfoCircleOutlined />
                                        </a-tooltip>
                                    </template>
                                </a-input>
                            </a-col>
                            <a-col :span="8">
                                <a-breadcrumb class="mt-2 ml-3">
                                    <a-breadcrumb-item>Check Date</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-date-picker style="width: 100%;" v-model:value="userName" class=""
                                    placeholder="Basic usage">
                                    <template #prefix>
                                        <UserOutlined />
                                    </template>
                                    <template #suffix>
                                        <a-tooltip title="Extra information">
                                            <InfoCircleOutlined />
                                        </a-tooltip>
                                    </template>
                                </a-date-picker>
                                <a-breadcrumb class="mt-2 ml-3">
                                    <a-breadcrumb-item>Check Received</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-date-picker style="width: 100%;" v-model:value="userName" class=""
                                    placeholder="Basic usage">
                                    <template #prefix>
                                        <UserOutlined />
                                    </template>
                                    <template #suffix>
                                        <a-tooltip title="Extra information">
                                            <InfoCircleOutlined />
                                        </a-tooltip>
                                    </template>
                                </a-date-picker>
                                <a-breadcrumb class="mt-2 ml-3">
                                    <a-breadcrumb-item>Check Class</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-select ref="select" class="" v-model:value="value1" style="width: 100%"
                                    @focus="focus"></a-select>
                                <a-breadcrumb class="mt-2 ml-3">
                                    <a-breadcrumb-item>Check Category</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-select ref="select" class="" v-model:value="value1" style="width: 100%"
                                    @focus="focus"></a-select>
                                <a-breadcrumb class="mt-2 ml-3">
                                    <a-breadcrumb-item>Currency</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-select ref="select" class="" v-model:value="value1" style="width: 100%"
                                    @focus="focus"></a-select>
                                <a-breadcrumb class="mt-2 ml-3">
                                    <a-breadcrumb-item>Approving Officer</a-breadcrumb-item>
                                </a-breadcrumb>
                                <a-input v-model:value="userName" class="" placeholder="Basic usage">
                                    <template #prefix>
                                        <UserOutlined />
                                    </template>
                                    <template #suffix>
                                        <a-tooltip title="Extra information">
                                            <InfoCircleOutlined />
                                        </a-tooltip>
                                    </template>
                                </a-input>

                            </a-col>
                        </a-row>
                    </a-card>
                </a-col>
            </a-row>
        </a-modal>
    </TreasuryLayout>
</template>
<script>
import Pagination from "@/Components/Pagination.vue"
export default {
    data() {
        return {
            isModalOpen: false,
            openModalReplace: false,
            selectDataDetails: [],
            isLoadingTbl: false
        }
    },
    props: {
        data: Array,
        columns: Array,
        pagination: Object,
    },
    methods: {
        openModaldated(data) {
            this.isModalOpen = true;
            this.selectDataDetails = data;
            console.log(this.selectDataDetails);
        },
        showModalReplace(data) {
            this.openModalReplace = true;
        },
        handleTableChange(page) {
            this.isLoadingTbl = true;
            try {

                this.$inertia.get(route('pdc.checks'), {
                    page: page,
                },
                    {
                        preserveScroll: true,
                    })
            } catch {

            }
        }
    }
};
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