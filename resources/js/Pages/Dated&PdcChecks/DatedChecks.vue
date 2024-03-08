<script setup>
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import { ref } from "vue";

const size = ref("large");
const color = ref("green");
</script>

<template>

    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                This is treasury Dashboard
            </h2>
        </template>
        <div class="py-0">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mt-3 mb-3">
                    <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                    <a-breadcrumb-item><a href="">Dated Checks/Pdc</a></a-breadcrumb-item>
                    <a-breadcrumb-item>Dated Checks</a-breadcrumb-item>
                </a-breadcrumb>
            
                <a-card>
                    <a-table :pagination="false" :data-source="data.data" :loading="isLoadingTbl"
                        class="components-table-demo-nested" :columns="columns" size="small" bordered>

                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'action'">
                                <a-button @click="detailedChecks(record)" size="small" style="width: 100%;">
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

        <a-modal v-model:open="isOpenModal" title="Details" style="top: 20px; width: 1000px"
            @ok="setModal1Visible(false)" :footer="null">
            <div class="product-container">
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-t border-gray-200">
                                Customer Name
                            </td>
                            <td
                                class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-t border-gray-200">
                                {{ selectDataDetails.fullname }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check From
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.department }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Number
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_no }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Approving Officer
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.approving_officer }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Class
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_class }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Status
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_status }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Date
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_date }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Account No
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.account_no }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Received
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_received }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Account Name
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.account_name }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Received As
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_type }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Bank Name
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.bankbranchname }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Check Category
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_category }}
                            </td>
                        </tr>
                        <tr>
                            <td
                                class="px-6 py-2 whitespace-no-wrap font-bold border-b border-r border-l border-gray-200">
                                Amount
                            </td>
                            <td class="px-6 py-2 whitespace-no-wrap border-b border-r border-l border-gray-200">
                                {{ selectDataDetails.check_amount }}
                            </td>
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
    components: {
        Pagination,
    },

    data() {
        return {
            isOpenModal: false,
            selectDataDetails: {},
            isLoadingTbl: false,
        };
    },
    props: {
        data: Array,
        columns: Array,
        pagination: Object,
    },
    methods: {
        detailedChecks(selectData) {
            this.isOpenModal = true;
            this.selectDataDetails = selectData;
        },
        handleTableChange(page) {
            this.isLoadingTbl = true;
            try {
                this.$inertia.get(route("dated.checks"), {
                    page: page,
                },
                    {
                        preserveScroll: true,
                    });
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        },
    },
};
</script>

<style scoped>
body {
    font-family: Arial, sans-serif;
}

.a-pagination-item {
    border: 1px solid #e8e8e8;
}

.pagination {
    margin: 25px 0 15px 0;
}

.pagination,
.pagination li a {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}

.pagination li {
    background: rgb(0, 21, 41);
    list-style: none;
    border-radius: 5px;
}

.pagination li a {
    text-decoration: none;
    color: #fdfdfd;
    height: 40px;
    width: 50px;
    font-size: 14px;
    padding-top: 1px;
    border: 1px solid rgba(0, 0, 0, 0.25);
    border-right-width: 0px;
    box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 0.35);
}

.pagination li:last-child a {
    border-right-width: 1px;
}

.pagination li a:hover {
    background: rgba(255, 255, 255, 0.2);
    border-top-color: rgba(0, 0, 0, 0.35);
    border-bottom-color: rgba(0, 0, 0, 0.5);
}

.pagination li a:focus,
.pagination li a:active {
    padding-top: 4px;
    border-left-width: 1px;
    background: rgba(255, 255, 255, 0.15);
    box-shadow: inset 0px 2px 1px 0px rgba(0, 0, 0, 0.25);
}

.pagination li.icon a {
    min-width: 120px;
}

.pagination li:first-child span {
    padding-right: 8px;
}

.pagination li:last-child span {
    padding-left: 8px;
}

.ant-pagination-item {
    border: 1px solid #e8e8e8;
    padding: 8px;
    /* Adjust padding as needed */
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
