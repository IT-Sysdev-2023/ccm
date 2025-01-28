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

            <div class="flex justify-between">
                <a-button class="mb-3" @click="createMergeChecks(checkedRecords)" style="width: 350px;" type="primary"
                    :disabled="checkedRecords.length < 2">
                    <template #icon>
                        <PlusSquareOutlined />
                    </template>
                    Merge a checks
                </a-button>

                <a-input-search v-model:value="form.search" style="width: 400px;" placeholder="Search Checks"
                    :loading="isFetching" />
            </div>
            {{ checkedRecords }}
            <a-table :loading="isLoadingTable" :dataSource="data" :columns="columns" size="small" bordered
                row-key="key">
                <template #bodyCell="{ column, record, index }">
                    <template v-if="column.dataIndex">
                        <span v-html="highlightText(record[column.dataIndex], form.search)
                            ">
                        </span>
                    </template>
                    <template v-if="column.key === 'check_box'">
                        <a-switch v-model:checked="record.isChecked" @change="merginCheckSwitch">
                            <template #checkedChildren><check-outlined /></template>
                            <template #unCheckedChildren><close-outlined /></template>
                        </a-switch>
                    </template>

                    <template v-if="column.key === 'action'">
                        <a-button class="mx-2" @click="openUpDetails(record)">
                            <template #icon>
                                <SettingOutlined />
                            </template>
                        </a-button>

                    </template>
                </template>
            </a-table>

        </div>
    </div>
    <CheckModalDetail v-model:open="openDetails" :datarecords="selectDataDetails"></CheckModalDetail>
</template>



<script>
import Pagination from "@/Components/Pagination.vue"
import debounce from "lodash/debounce";
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import { message } from "ant-design-vue";
import { highlighten } from "@/Mixin/highlighten.js";

export default {
    layout: TreasuryLayout,
    setup() {
        const { highlightText } = highlighten();
        return { highlightText };
    },
    data() {
        return {
            form: {
                search: this.filters.search,
            },
            isFetching: false,
            isLoadingTable: false,
            selectDataDetails: {},
            openDetails: false,
            checkedRecords: [],
            openModal: false,
            checkAmount: 0,
            penalty: 0,
            storedLocal: {},
            isCheckLocal: null,
            checkCount: false,

        }
    },
    props: {
        data: Object,
        columns: Array,
        currency: Object,
        category: Object,
        check_class: Object,
        filters: Object,
    },

    methods: {

        openUpDetails(dataIn) {
            this.openDetails = true;
            this.selectDataDetails = dataIn;
        },
        merginCheckSwitch() {
            // console.log(red);
            this.checkedRecords = this.data.filter(item => item.isChecked);
        },

        createMergeChecks(data) {
            this.$inertia.get(route('create.merge.checks'), {
                checkAmount: data.map(record => record.check_amount),
                checkData: { ...data }
            })
        },


    },
    watch: {
        form: {
            deep: true,
            handler: debounce(async function () {
                this.isFetching = true,
                    this.$inertia.get(route("mergechecks.checks"), {
                        search: this.form.search,
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
