<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
</script>

<template>
    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">This is treasury Dashboard</h2>
        </template>

        <div class="py-0">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
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
                <a-card>
                    <div class="flex justify-between">
                        <a-button class="mb-3" @click="modalMergeChecks">
                            <template #icon>
                                <FolderAddOutlined />
                            </template>
                            Merge a checks
                        </a-button>
                        <a-input-search placeholder="input search text" style="width: 400px" />
                    </div>
                    <a-table :loading="isLoadingTable" :dataSource="data.data" :columns="columns" size="small" bordered
                        :pagination="false">
                        <template #bodyCell="{ column, record, index }">
                            <template v-if="column.key === 'check_box'">
                                <a-switch size="small" v-model:checked="record.isChecked" @change="computedAmount(record)">
                                    <template #checkedChildren><check-outlined /></template>
                                    <template #unCheckedChildren><close-outlined /></template>
                                </a-switch>
                            </template>
                            <template v-if="column.key === 'action'">
                                <a-button size="small" class="mx-2" @click="openUpDetails(record)">
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
        <a-modal v-model:open="openModal" title="Basic Modal" width="80%" wrap-class-name="full-modal" @ok="handleOk"
            :footer="null">
            <a-row :gutter="[16, 16]">
                <a-col :span="8">
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <AccountBookOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Account Number</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Enter here...">
                        <template #prefix>

                        </template>
                        <template #suffix>
                            <a-tooltip title="Enter account number here">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <CalendarOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Check Date</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Enter here...">
                        <template #prefix>

                        </template>
                        <template #suffix>
                            <a-tooltip title="Enter check date here..">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <MoneyCollectOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Currency</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Enter here...">
                        <template #prefix>

                        </template>
                        <template #suffix>
                            <a-tooltip title="Enter currency here..">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">

                            <BankOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Check From</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Enter here...">
                        <template #prefix>

                        </template>
                        <template #suffix>
                            <a-tooltip title="Enter Check From here..">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <UsergroupAddOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Account Name</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Enter here...">
                        <template #prefix>

                        </template>
                        <template #suffix>
                            <a-tooltip title="Enter account name here...">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                </a-col>
                <a-col :span="8">
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <UserOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Customer Name</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Enter here...">
                        <template #prefix>

                        </template>
                        <template #suffix>
                            <a-tooltip title="Entere customer name here...">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <MoneyCollectOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Check Amount</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Enter here...">
                        <template #prefix>

                        </template>
                        <template #suffix>
                            <a-tooltip title="Enter check amount here...">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <BankOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Check Class</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Enter here...">
                        <template #prefix>

                        </template>
                        <template #suffix>
                            <a-tooltip title="Enter check class here...">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <MoneyCollectOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Check Number</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Enter here...">
                        <template #prefix>

                        </template>
                        <template #suffix>
                            <a-tooltip title="Enter check number here...">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <BankOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Bank Name</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Enter here...">
                        <template #prefix>

                        </template>
                        <template #suffix>
                            <a-tooltip title="Enter bank name here...">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                </a-col>
                <a-col :span="8">
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <CalendarOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Check Received</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Enter here...">
                        <template #prefix>

                        </template>
                        <template #suffix>
                            <a-tooltip title="Enter check date here...">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <HomeOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Check Category</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Enter here...">
                        <template #prefix>

                        </template>
                        <template #suffix>
                            <a-tooltip title="Enter Check categoty here...">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <UsergroupAddOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Approving Officer</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Enter here...">
                        <template #prefix>

                        </template>
                        <template #suffix>
                            <a-tooltip title="Enter Approving officer here...">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <UsergroupAddOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Penalty</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="amount" class="mb-3" placeholder="Enter here...">
                        <template #prefix>

                        </template>
                        <template #suffix>
                            <a-tooltip title="Enter Approving officer here...">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <UsergroupAddOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Reason for return</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-textarea v-model:value="value" placeholder="Basic usage" :rows="4" />
                    <a-button class="mt-2" style="width: 100%; background: #15cc04; color: white;">
                        <template #icon>
                            <SaveOutlined />
                        </template>
                        continue merging?
                    </a-button>
                </a-col>
            </a-row>
        </a-modal>
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
import Pagination from "@/Components/Pagination.vue"
export default {
    data() {
        return {
            isLoadingTable: false,
            selectDataDetails: {},
            openDetails: false,
            checkedRecords: [],
            openModal: false,
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
        },
        computedAmount() {
            this.checkedRecords = this.data.data.filter(record => record.isChecked);
            return this.checkedRecords.length;
        },
        modalMergeChecks() {
            this.openModal = true;
        }
    },

}
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