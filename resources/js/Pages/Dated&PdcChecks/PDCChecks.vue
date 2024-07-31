<template>

    <Head title="Post Dated Checks" />

    <div class="py-0">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <a-breadcrumb class="mt-3 mb-3">
                <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                <a-breadcrumb-item><a href="">Dated Checks/Pdc</a></a-breadcrumb-item>
                <a-breadcrumb-item>Pending Dated Checks</a-breadcrumb-item>
            </a-breadcrumb>

            <a-card>
                <div class="flex justify-end">
                    <a-input-search v-model:value="form.search" style="width: 350px;" class="mb-5"
                        placeholder="Search Checks" :loading="isFetching" />
                </div>
                <a-table :loading="isLoadingTbl" :dataSource="data.records"
                    :columns="data.columns" size="small" bordered>
                    <template #bodyCell="{ column, record, index }">
                        <template v-if="column.dataIndex">
                            <span v-html="highlightText(record[column.dataIndex], form.search)
                                ">
                            </span>
                        </template>
                        <template v-if="column.key === 'action'">
                            <a-button class="mx-2" @click="replace(record)">
                                <template #icon>
                                    <SettingFilled />
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
            </a-card>
        </div>
    </div>

    <CheckModalDetail v-model:open="isModalOpen" :datarecords="selectDataDetails"></CheckModalDetail>

    <CheckSetupModal v-model:open="openSetup" :record="record" :currency="data.currency" :check-class="data.check_class"
        :category="data.category" />

</template>
<script>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
import debounce from 'lodash/debounce';
import { highlighten } from "@/Mixin/highlighten.js";
import { FolderFilled } from '@ant-design/icons-vue';
import { data } from 'autoprefixer';
export default {
    layout: TreasuryLayout,

    setup() {
        const { highlightText } = highlighten();
        return { highlightText };
    },
    data() {
        return {
            form: {
                search: this.filters.search
            },
            isFetching: false,
            record: {},
            openSetup: false,
            isModalOpen: false,
            selectDataDetails: [],
        }
    },
    props: {
        data: Object,
        filters: Object,
    },
    methods: {
        details(check_id) {
            axios.get(route(`get.check.details`, check_id)).then(response => {
                this.isModalOpen = true;
                this.selectDataDetails = response.data;
            })
        },


        replace(record) {
            this.record = record;
            this.openSetup = true;
        },

    },
    watch: {
        form: {
            deep: true,
            handler: debounce(async function () {
                this.isFetching = true,
                    this.$inertia.get(route("pdc.checks"), {
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
};
</script>
