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

                <a-date-picker v-model:value="form.year" picker="year" style="width: 250px" />
                <a-input-search v-model:value="form.search" class="mx-2" placeholder="Search here.."
                    style="width: 350px" />

            </div>
            <a-table :dataSource="data.data" :columns="columns" :pagination="false" :loading="isTableLoading" bordered
                size="small">
                <template #bodyCell="{ column, record }">
                    <template v-if="column.dataIndex">
                        <span v-html="highlightText(record[column.dataIndex], form.search)
                            ">
                        </span>
                    </template>

                    <template v-if="column.key === 'action'">
                        <a-button class="mx-1" ref="ref4" v-on:click="
                            confirmBounceTagg(record.checks_id)
                            ">
                            <template #icon>
                                <TagFilled />
                            </template>
                        </a-button>

                        <a-button @click="details(record.checks_id)">
                            <template #icon>
                                <FolderFilled />
                            </template>
                        </a-button>
                    </template>
                </template>
            </a-table>
            <pagination-resource class="mt-6" :datarecords="data" />
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
import pickBy from "lodash/pickBy";
import CheckModalDetail from "@/Components/CheckModalDetail.vue";
import { highlighten } from "@/Mixin/highlighten.js";
import axios from "axios";
export default {
    layout: TreasuryLayout,
    props: {
        data: Object,
        columns: Array,
        filters: Object,
    },
    setup() {
        const { highlightText } = highlighten();
        return { highlightText };
    },
    data() {
        return {
            colors: 'red',
            openDetails: false,
            selectDataDetails: [],
            tagAsBounced: false,
            returnDate: dayjs(),
            checksId: null,
            isLoadingbutton: false,
            showAtootltip: false,
            isTableLoading: false,
            form: {
                search: this.filters.search,
                year: this.filters.year ? dayjs(this.filters.year) : dayjs()
            },
        };
    },
    watch: {
        form: {
            deep: true,
            handler: debounce(async function () {
                this.isTableLoading = true;
                const formattedDate = this.form.year ? this.form.year.format('YYYY') : null;
                this.$inertia.get(route("bounce.tagging"), {
                    ...pickBy(this.form), year: formattedDate
                }, {
                    preserveState: true,
                    onSuccess: () => {
                        this.isTableLoading = false;
                    }
                });
            }, 500),
        },
    },
    methods: {
        onDateChangeReturn(dateObj, dateStr) {
            this.returnDate = dateObj;
        },

        details(check_id) {
            axios.get(route(`get.check.details`, check_id)).then(response => {
                this.openDetails = true;
                this.selectDataDetails = response.data;
            })
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
