<template>

    <Head title="Bounce Tagging" />
    <div class="py-5">
        <div class="max-full mx-auto sm:px-6 lg:px-8">
            <div class="text-xl mb-5 text-center" style="display: flex; justify-content: space-between">
                <a-breadcrumb>
                    <a-breadcrumb-item href="">
                        <HomeOutlined />
                    </a-breadcrumb-item>
                    <a-breadcrumb-item href="">
                        <user-outlined />
                        <span>Ds&Bounce Tagging</span>
                    </a-breadcrumb-item>
                    <a-breadcrumb-item>Bounce Tagging</a-breadcrumb-item>
                    <a-breadcrumb-item>Deposited Checks</a-breadcrumb-item>
                </a-breadcrumb>
            </div>

            <div style="display: flex; justify-content: space-between" class="mb-3">
                <a-date-picker @change="onDateChange" v-model:value="dtYear" picker="year" style="width: 250px" />
                <a-input-search v-model:value="query.search" class="mx-2" placeholder="Input Check Number"
                    style="width: 350px" />
            </div>
            <a-card>
                <a-table :dataSource="data.data" :columns="columns" :pagination="false" :loading="loading"
                    class="components-table-demo-nested" bordered size="small">
                    <template #bodyCell="{ column, record }">
                        <template v-if="Object.keys(record).includes(column.dataIndex)">
                            <span v-html="highlightText(record[column.dataIndex], query.search)
                                ">
                            </span>
                        </template>

                        <template v-if="column.key === 'action'">
                            <a-button type="primary" class="mx-1" size="small" ref="ref4" v-on:click="
                                confirmBounceTagg(record.checks_id)
                                ">
                                <template #icon>
                                    <TagsOutlined />
                                </template>
                            </a-button>

                            <a-button size="small" v-on:click="openModalDetails(record)">
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

        <CheckModalDetail v-model:open="openDetails" :datarecords="selectDataDetails"></CheckModalDetail>

        <a-modal class="mb-5" v-model:open="tagAsBounced" title="Tag as Bounce" :footer="null">
            <div class="mb-4">
                <a-tooltip :color="colors" :open="showAtootltip" title="Return Date is required">
                    <a-date-picker @change="onDateChangeReturn" v-model:value="returnDate"
                        style="width: 63%; margin-right: 5px" class="mt-3" /></a-tooltip>
                <a-button :loading="isLoadingbutton" style="width: 35%; background: #b5fcb2d0"
                    v-on:click="continueToTagg">Continue tagging?</a-button>
            </div>
        </a-modal>
    </div>
</template>

<script>
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import dayjs from "dayjs";
import { message } from "ant-design-vue";
import debounce from "lodash/debounce";
import CheckModalDetail from "@/Components/CheckModalDetail.vue";
// import highlightText from "@/Mixin/highlightText";
import { highlighten } from "@/Mixin/highlighten.js";
export default {
    layout: TreasuryLayout,
    props: {
        data: Object,
        columns: Array,
        sel_year: String,
    },
    setup() {
        const { highlightText } = highlighten();

        return {
            highlightText
        };
    },
    data() {
        return {
            query: {
                search: '',
            },
            colors: 'red',
            dataSource: [],
            isSearchLoading: false,
            loading: false,
            dtYear: dayjs(this.sel_year),
            c_page: "",
            openDetails: false,
            selectDataDetails: [],
            tagAsBounced: false,
            returnDate: dayjs(),
            checksId: null,
            isLoadingbutton: false,
            showAtootltip: false,
            page: 1,
            query: {
                search: "",
            },
        };
    },
    watch: {
        query: {
            deep: true,
            handler: debounce(async function () {
                try {
                    this.loading = true;
                    this.$inertia.get(
                        route("bounce.tagging"),
                        {
                            // page: this.page,
                            dt_year: this.dtYear.format("YYYY"),
                            search: this.query.search,
                        },
                        { preserveState: true }
                    );
                } catch (error) {
                    console.log(error);
                } finally {
                    this.loading = false;
                }
            }, 600),
        },
    },
    methods: {
        onDateChange(dateObj, dateStr) {
            this.$inertia.get(route("bounce.tagging"), {
                dt_year: dateStr,
            });
        },
        onDateChangeReturn(dateObj, dateStr) {
            this.returnDate = dateObj;
        },

        handleTableChange(pagination) {
            this.getBounceTagging(pagination);
        },

        openModalDetails(data) {
            this.openDetails = true;
            this.selectDataDetails = data;
        },
        confirmBounceTagg(checks_id) {
            this.checksId = checks_id;
            this.tagAsBounced = true;
        },
        continueToTagg() {
            this.isLoadingbutton = true;
            if (!this.returnDate) {
                setTimeout(() => {
                    this.isLoadingbutton = false;
                    this.showAtootltip = true;
                }, 700);
            } else {
                this.$inertia.post(
                    route("tag_check_bounce"),
                    {
                        date: this.returnDate.format("YYYY-MM-DD"),
                        check_id: this.checksId,
                    },
                    {
                        onFinish: () => {
                            setTimeout(() => {
                                this.tagAsBounced = false;
                                this.isLoadingbutton = false;
                            }, 700);
                        },
                    }
                );
            }
        },
    },
};
</script>

<style scoped>
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
