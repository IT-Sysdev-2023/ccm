<template>
    <TreasuryLayout>
        <a-card>
            <template #title>
                <div class="flex justify-between">
                    <div>
                        DS TAGGING
                    </div>
                    <div class="flex gap-5">
                        <span class="text-blue-500">
                            Count: {{ doneCount }}
                        </span>
                        <span>
                            Total: {{ totalAmount?.toLocaleString() }}
                        </span>
                        <span class="text-red-400">
                            Due Checks: {{ dsData.due_dates }}
                        </span>
                    </div>
                </div>
            </template>
            <a-alert v-if="errors" message="Opps Something went wrong!" class="mb-3" type="error" show-icon>
                <template #description>
                    <div v-for="err in errors">
                        <p>{{ err }}</p>
                    </div>
                </template>
            </a-alert>
            <a-row :gutter="[16, 16]">
                <a-col :span="8">
                    <a-input placeholder="Ds Number" v-model:value="form.dsNo" />
                </a-col>
                <a-col :span="8">
                    <a-date-picker v-model:value="form.date" style="width: 100%" />
                </a-col>
                <a-col :span="8">
                    <a-button :loading="form.processing" block type="primary" @click="submitToConButton">
                        <template #icon>
                            <FastForwardOutlined />
                        </template>
                        Submit Ds Number
                    </a-button>
                </a-col>
            </a-row>
            <div class="flex justify-end ">
                <a-input-search style="width: 450px;" v-model:value="form.search" block class="mb-3 mt-5"
                    placeholder="Search Checks" />
            </div>
            <div class="mt-3">
                <a-table :loading="isFetching" size="small" bordered :data-source="dsData.data"
                    :columns="dsData.columns" class="no-hover-table" :row-class-name="(_record, index) =>
                        _record.type === 'POST-DATED'
                            ? 'POST-DATED'
                            : 'DATED'
                        ">
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'select'">
                            <a-switch v-model:checked="record.done" @change="handleSwitchChange(record)">
                                <template #checkedChildren><check-outlined /></template>
                                <template #unCheckedChildren><close-outlined /></template>
                            </a-switch>
                        </template>

                    </template>
                </a-table>
            </div>
        </a-card>
    </TreasuryLayout>
</template>
<script setup>
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
import axios from "axios";
import { computed, watch, ref, onMounted } from "vue";
import { debounce } from 'lodash';
import { router, useForm } from "@inertiajs/vue3";
import dayjs from "dayjs";
import { message } from "ant-design-vue";
import { notification } from 'ant-design-vue';

const dsData = ref([]);
const isFetching = ref(false);
const fetchData = async () => {
    try {
        isFetching.value = true;

        const { data } = await axios.get(route('axios.get.tagging'));

        dsData.value = data;
    } catch {
        isFetching.value = false;
    } finally {
        isFetching.value = false;
    }

}

const doneCount = computed(() => dsData?.value?.data?.filter(item => item.done).length);

const totalAmount = computed(() => {
    return dsData?.value?.data?.filter(item => item.done)
        .reduce((sum, item) => sum + (item.check_amount || 0), 0);
});

const handleSwitchChange = async (item) => {
    try {

        await axios.put(route('update.switch'), {
            id: item.checks_id,
            isCheck: item.done,
        })

    } catch (error) {

    } finally {

    }
}

onMounted(() => {
    fetchData();
})

const form = useForm({
    search: '',
    date: '',
    dsNo: '',
})

watch(
    () => form.search,
    debounce(async () => {
        try {
            isFetching.value = true;
            const { data } = await axios.get(route('search.dstagging'), {
                params: {
                    search: form.search
                }
            });
            dsData.value = data;
        } catch (error) {
            // Optionally log or handle the error
            console.error(error);
        } finally {
            isFetching.value = false;
        }
    }, 500),
    { deep: true }
);
const errors = ref();
const submitToConButton = async () => {
    const selected = dsData.value.data.filter((value) => value.done)
    await router.post(
        route("submit.ds.tagging"),
        {
            selected,
            dsNo: form.dsNo,
            dateDeposit: dayjs(form.date).format(
                "YYYY-MM-DD"
            ),
        },
        {
            onSuccess: (e) => {
                message.success({
                    content: "Successfully submitted",
                    duration: 5,
                });
                notification['success']({
                    message: 'Successfully Submmitted',
                    description:
                        'Successfully Submitting Checks',
                });
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            },

            onError: (err) => {
                errors.value = err;
            }
        }
    );
}

</script>

<style>
.no-hover-table .ant-table-tbody>tr.ant-table-row:hover>td {
    background: transparent !important;
}

.DATED {
    color: rgb(56, 56, 255);
    /* Set background color for DATED type */
}

.POST-DATED {

    color: rgb(255, 56, 56);
    /* background-color: rgba(253, 132, 132, 0.233); */
    /* Set background color for POST-DATED type */
}
</style>
