<template>

    <Head title="Manual Check Entry" />

    <div class="py-2">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <a-breadcrumb class="mb-2 mt-2">
                <a-breadcrumb-item href="">
                    <HomeOutlined />
                </a-breadcrumb-item>
                <a-breadcrumb-item href="">
                    <span>Dashboard</span>
                </a-breadcrumb-item>
                <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                <a-breadcrumb-item>Check Manual Entry</a-breadcrumb-item>
            </a-breadcrumb>

                <div class="flex justify-between mb-5 mt-4">
                    <a-button @click="modalAddChecksManual" type="primary" style="width: 350px">
                        <template #icon>
                            <PlusSquareOutlined />
                        </template>
                        Add manual check
                    </a-button>
                    <a-input-search v-model:value="form.search" placeholder="Search here..." style="width: 400px" />
                </div>
                <a-table :loading="isloadingtable" bordered size="small" :dataSource="data.data" :columns="columns"
                    :pagination="false">
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex">
                            <span v-html="highlightText(record[column.dataIndex], form.search)
                                ">
                            </span>
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
                <pagination class="mt-6" :datarecords="data" />
        </div>
    </div>

    <ManualEntryModal v-model:open="openModal" :currency="currency" :category="category" :checkClass="check_class" />
    <CheckModalDetail v-model:open="openDetails" :datarecords="selectDataDetails"></CheckModalDetail>

</template>

<script>
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import ManualEntryModal from "@/Pages/Transaction/Modals/ManualEntryModal.vue"
import debounce from "lodash/debounce";
import Pagination from "@/Components/Pagination.vue";
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
            openModal: false,
            openDetails: false,
            selectDataDetails: {},
            isRetrieving: false,
        };
    },
    props: {
        data: Array,
        columns: Array,
        currency: Array,
        category: Array,
        check_class: Array,
        filters: Object,
    },
    methods: {

        modalAddChecksManual() {
            this.openModal = true;
        },
        openUpDetails(dataIn) {
            this.openDetails = true;
            this.selectDataDetails = dataIn;
        },
    },
    watch: {
        form: {
            deep: true,
            handler: debounce(async function () {
                this.$inertia.get( route("manual_entry.checks"),{
                        search: this.form.search,
                    },
                    { preserveState: true },
                );
            }, 600),
        },
    },
};
</script>
