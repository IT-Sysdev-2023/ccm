<template>

    <Head title="Merging Checks" />

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
                    <a-button class="mb-3" @click="modalMergeChecks" style="width: 350px;" type="primary"
                        :disabled="checkedRecords.length < 2">
                        <template #icon>
                            <PlusSquareOutlined />
                        </template>
                        Merge a checks
                    </a-button>

                    <a-input-search v-model:value="query.search" style="width: 400px;" placeholder="Search Checks"
                        :loading="isFetching" />
                </div>
                <a-table :loading="isLoadingTable" :dataSource="data.data" :columns="columns" size="small" bordered
                    :pagination="false">
                    <template #bodyCell="{ column, record, index }">
                        <template v-if="column.key === 'check_box'">
                            <a-switch size="small" v-model:checked="record.isChecked"
                                @change="merginCheckSwitch(record)">
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
    <MergeCheckModal v-model:open="openModal"
        :currency="currency"
        :category="category"
        :check_class="check_class"
        :checkAmount="checkAmount"
        :penaltyProps="penalty"
    />

    <CheckModalDetail v-model:open="openDetails" :datarecords="selectDataDetails"></CheckModalDetail>
</template>


<script>
import Pagination from "@/Components/Pagination.vue"
import debounce from "lodash/debounce";
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import MergeCheckModal from "@/Pages/Transaction/Modals/MergeCheckModal.vue"
export default {
    layout: TreasuryLayout,
    data() {
        return {
            query: {
                search: '',
            },
            isFetching: false,
            isLoadingTable: false,
            selectDataDetails: {},
            openDetails: false,
            checkedRecords: [],
            openModal: false,
            checkAmount: 0,
            penalty: 0

        }
    },
    props: {
        data: Object,
        columns: Array,
        currency: Object,
        category: Object,
        check_class: Object,
    },
    methods: {
        openUpDetails(dataIn) {
            this.openDetails = true;
            this.selectDataDetails = dataIn;
        },
        merginCheckSwitch() {
            this.checkedRecords = this.data.data.filter(record => record.isChecked);

            return this.checkedRecords.length;
        },
        modalMergeChecks() {
            // console.log(this.checkedRecords)
            this.checkAmount = this.checkedRecords.map(record => parseFloat(record.check_amount)).reduce((acc, subtotal) => { return acc + subtotal }, 0);

            parseFloat(this.checkAmount).toLocaleString(undefined, { minimumFractionDigits: 2 })
            this.penalty = this.checkAmount * parseFloat(.02);
            this.openModal = true;
        },

    },
    watch: {
        query: {
            deep: true,
            handler: debounce(async function () {
                this.isFetching = true,
                    this.$inertia.get(route("mergechecks.checks"), {
                        searchQuery: this.query.search,
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

}
</script>

