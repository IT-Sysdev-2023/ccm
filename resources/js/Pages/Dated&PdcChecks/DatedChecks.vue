<template>

    <Head title="Dated Checks" />
    <div class="py-0">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <a-breadcrumb class="mt-3 mb-3">
                <a-breadcrumb-item>Dashboard</a-breadcrumb-item>
                <a-breadcrumb-item><a href="">Dated Checks/Pdc</a></a-breadcrumb-item>
                <a-breadcrumb-item>Dated Checks</a-breadcrumb-item>
            </a-breadcrumb>

            <a-card>
                <div class="flex justify-end">
                    <a-input-search v-model:value="form.search" style="width: 400px;" class="mb-5"
                        placeholder="Search Checks" :loading="isFetching" />
                </div>
                <a-table :pagination="false" :data-source="data.data" :loading="isLoadingTbl" :columns="columns"
                    size="small" bordered>

                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex">
                            <span v-html="highlightText(record[column.dataIndex], form.search)
                                ">
                            </span>
                        </template>
                        <template v-if="column.key === 'action'">
                            <a-button @click="details(record.checks_id)">
                                <template #icon>
                                    <FolderFilled />
                                </template>
                            </a-button>
                        </template>
                    </template>
                </a-table>
                <pagination class="mt-6 mb-10" :datarecords="data" />
            </a-card>
        </div>
    </div>

    <CheckModalDetail  :datarecords="selectDataDetails"/>
</template>

<script>
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import { ref } from "vue";
import debounce from "lodash/debounce";
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
            isOpenModal: false,
            selectDataDetails: {},
            isLoadingTbl: false,
            isFetching: false,
        };
    },
    props: {
        data: Object,
        columns: Array,
        filters: Object
    },
    methods: {
        details(check_id) {
            axios.get(route(`get.check.details`, check_id)).then(response => {
                this.isOpenModal = true;
                this.selectDataDetails = response.data;
            })
        },
    },
    watch: {
        form: {
            deep: true,
            handler: debounce(async function () {
                this.isFetching = true,
                    this.$inertia.get(route("dated.checks"), {
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
</style>
