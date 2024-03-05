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
                    <a-select class="mb-3" ref="select" placeholder="Filter table" v-model:value="getMode"
                        style="width: 300px" @focus="focus" @change="handleChangeNode">
                        <a-select-option value="1">CHECK</a-select-option>
                        <a-select-option value="2">CHECK & CASH</a-select-option>
                        <a-select-option value="3">CASH</a-select-option>
                        <a-select-option value="4">RE DEPOSIT</a-select-option>
                        <a-select-option value="5">PARTIALS</a-select-option>
                    </a-select>
                    <a-table bordered :pagination="false" :loading="isloadingtable" :data-source="data.data"
                        :columns="columns" size="small">

                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'action'">
                                <template v-if="record.mode === 'CASH'">
                                    <a-button size="small" class="mx-2" @click="openUpDetails(record)">
                                        <template #icon>
                                            <RedEnvelopeOutlined />
                                        </template>
                                    </a-button>
                                </template>

                                <template v-else-if="record.mode === 'RE-DEPOSIT'">
                                    <a-button size="small" class="mx-2" @click="openUpDetails(record)">
                                        <template #icon>
                                            <DeliveredProcedureOutlined />
                                        </template>
                                    </a-button>
                                </template>

                                <template v-else-if="record.mode === 'PARTIAL'">
                                    <a-button size="small" class="mx-2" @click="openUpDetails(record)">
                                        <template #icon>
                                            <BarChartOutlined />
                                        </template>
                                    </a-button>
                                </template>

                                <template v-else-if="record.mode === 'CHECK'">
                                    <a-button size="small" class="mx-2" @click="openUpDetails(record)">
                                        <template #icon>
                                            <AuditOutlined />
                                        </template>
                                    </a-button>
                                </template>

                                <template v-else>
                                    <a-button size="small" class="mx-2" @click="openUpDetails(record)">
                                        <template #icon>
                                            <CreditCardOutlined />
                                        </template>
                                    </a-button>
                                </template>
                            </template>
                        </template>
                    </a-table>
                    <pagination class="mt-6" :datarecords="data" />
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
import Pagination from "@/Components/Pagination.vue"
export default {
    data() {
        return {
            isloadingtable: false,
            getMode: this.getModeProps
        }
    },
    props: {
        data: Array,
        columns: Array,
        pagination: Array,
        getModeProps: Object
    },
    methods: {
        handlePaginate(page = 1) {
            this.isloadingtable = true;
            this.$inertia.get(route("replace.checks"), {
                page: page,
                getMode: this.getMode
            }, {
                preserveScroll: true
            });
        },
        handleChangeNode(page = 1) {
            this.$inertia.get(route("replace.checks"), {
                page: page,
                getMode: this.getMode
            }, {
                preserveScroll: true
            });
        }
    }
}
</script>