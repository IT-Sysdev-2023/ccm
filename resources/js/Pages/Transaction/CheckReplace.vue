<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">This is treasury Dashboard</h2>
        </template>

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
                    <a-breadcrumb-item>Replace Checks</a-breadcrumb-item>
                </a-breadcrumb>
                <a-card>
                    <a-table bordered :pagination="false" :loading="isloadingtable" :data-source="data.data"
                        :columns="columns" size="small">
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'action'">
                                <template v-if="record.mode === 'CASH'">
                                    <a-button size="square" class="mx-2" @click="openUpDetails(record)">
                                        <template #icon>
                                            <RedEnvelopeOutlined />
                                        </template>
                                    </a-button>
                                </template>
                                <template v-else-if="record.mode === 'RE-DEPOSIT'">
                                    <a-button size="square" class="mx-2" @click="openUpDetails(record)">
                                        <template #icon>
                                            <DeliveredProcedureOutlined />
                                        </template>
                                    </a-button>
                                </template>
                                <template v-else-if="record.mode === 'PARTIAL'">
                                    <a-button size="square" class="mx-2" @click="openUpDetails(record)">
                                        <template #icon>
                                            <BarChartOutlined />
                                        </template>
                                    </a-button>
                                </template>
                                <template v-else-if="record.mode === 'CHECK'">
                                    <a-button size="square" class="mx-2" @click="openUpDetails(record)">
                                        <template #icon>
                                            <AuditOutlined />
                                        </template>
                                    </a-button>
                                </template>
                                <template v-else>
                                    <a-button size="square" class="mx-2" @click="openUpDetails(record)">
                                        <template #icon>
                                            <CreditCardOutlined />
                                        </template>
                                    </a-button>
                                </template>
                            </template>
                        </template>
                    </a-table>
                    <div class="flex justify-end">
                        <a-pagination class="mt-0 mb-0" v-model:current="pagination.current" style="
                                margin-top: 10px;
                                border: 1px solid rgb(219, 219, 219);
                                border-radius: 10px;
                                padding: 10px;
                            " v-model:page-size="pagination.pageSize" :show-size-changer="false"
                            :total="pagination.total" :show-total="(total, range) =>
                                `${range[0]}-${range[1]} of ${total} reports`
                                " @change="handlePaginate" />
                    </div>
                </a-card>
            </div>
        </div>
    </TreasuryLayout>
</template>
<script>
import {
    SettingOutlined,
    TagOutlined,
    FolderAddOutlined,
    HomeOutlined,
    BarChartOutlined,
    DeliveredProcedureOutlined,
    RedEnvelopeOutlined,
    CreditCardOutlined,
    AuditOutlined

} from '@ant-design/icons-vue';
export default {
    data() {
        return {
            isloadingtable: false
        }
    },
    props: {
        data: Array,
        columns: Array,
        pagination: Array,
    },
    methods: {
        handlePaginate(page = 1) {
            this.isloadingtable = true;
            this.$inertia.get(route("replace.checks"), {
                page: page
            }, {
                preserveScroll: true
            });
        }
    }
}
</script>