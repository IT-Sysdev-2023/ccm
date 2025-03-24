<template>
    <div class="flex justify-end">
        <a-input-search style="width: 300px;" v-model:value="form.search" block class="mb-3" placeholder="Search Checks"
            :loading="isFetching" />
    </div>
    <a-table :data-source="records.data" :loading="isLoading" :pagination="false" :columns="columns" size="small"
    class="no-hover-table" bordered :row-class-name="(_record, index) =>
            _record.type === 'POST-DATED'
                ? 'POST-DATED'
                : 'DATED'
            ">
        <template #bodyCell="{ column, record }">
            <template v-if="column.dataIndex">
                <span v-html="highlightText(record[column.dataIndex], form.search)
                    ">
                </span>
            </template>
            <template v-if="column.key === 'select'">
                <a-switch v-model:checked="record.done" @change="handleSwitchChange(record)">
                    <template #checkedChildren><check-outlined /></template>

                    <template #unCheckedChildren><close-outlined /></template>
                </a-switch>
            </template>
        </template>
    </a-table>
    <pagination-resource class="mt-6" :datarecords="records" />
</template>
<script>
import { highlighten } from "@/Mixin/highlighten.js";
import { message } from "ant-design-vue";
import debounce from "lodash/debounce";
import { notification } from 'ant-design-vue';

export default {
    props: {
        records: Object,
        columns: Array,
        filters: Object,
        total: Object,
        defTolal: Number
    },
    setup() {
        const { highlightText } = highlighten();
        return { highlightText };
    },
    data() {
        return {
            form: {
                search: this.filters.search,
            },
            isLoading: false,
        }
    },
    methods: {
        async handleSwitchChange(data) {
            this.$inertia.put(route("update.switch"), {
                id: data.checks_id,
                isCheck: data.done,
            }, {
                onStart: () => {
                    this.isLoading = true;
                    message.loading('Action in progress..', 0)
                },
                onSuccess: () => {
                    this.isLoading = false;
                    message.destroy(),
                    notification['success']({
                        message: 'Uncheck',
                        description:
                            'Uncheck Tag Successfully',
                    })
                },
                preserveState: true,
                preserveScroll: true,
            })
        },
    },
    watch: {
        form: {
            deep: true,
            handler: debounce(async function () {
                this.isFetching = true,
                    this.$inertia.get(route("ds_tagging"), {
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
            }, 500),
        },
    },
}
</script>

<style>
.no-hover-table .ant-table-tbody > tr:hover > td {
  background-color: black;
}
.DATED {
    background-color: rgba(94, 169, 255, 0.274);
    /* Set background color for DATED type */
}

.POST-DATED {
    background-color: rgba(255, 121, 121, 0.274);
    /* Set background color for POST-DATED type */
}
</style>
