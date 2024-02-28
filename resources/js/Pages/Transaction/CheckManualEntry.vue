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

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mb-2 mt-2">
                    <a-breadcrumb-item href="">
                        <HomeOutlined />
                    </a-breadcrumb-item>
                    <a-breadcrumb-item href="">
                        <user-outlined />
                        <span>Dashboard</span>
                    </a-breadcrumb-item>
                    <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    <a-breadcrumb-item>Check Manual Entry</a-breadcrumb-item>
                </a-breadcrumb>
                <a-card>
                    <div class="flex justify-between">
                        <a-button class="mb-3" @click="modalAddChecksManual">
                            <template #icon>
                                <FolderAddOutlined />
                            </template>
                            Add checks manual
                        </a-button>
                        <a-input-search v-model:value="query.search" placeholder="input search text" style="width: 400px" />
                    </div>
                    <a-table :loading="isloadingtable" bordered size="small" :dataSource="data.data" :columns="columns"
                        :pagination="false">
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'action'">
                                <a-button size="square" class="mx-2">
                                    <template #icon>
                                        <SettingOutlined />
                                    </template>
                                </a-button>
                                <a-button size="square" style="background-color: rgb(58, 168, 58); color: white;">
                                    <template #icon>
                                        <TagOutlined />
                                    </template>
                                </a-button>
                            </template>
                        </template>
                    </a-table>
                    <div class="mt-3 mb-5" style="
                        border: 1px solid rgb(224, 224, 224);
                        border-radius: 10px;
                        padding: 10px;
                    ">
                        <div class="flex justify-end">
                            <a-pagination class="mt-0 mb-0" v-model:current="pagination.current"
                                v-model:page-size="pagination.pageSize" :show-size-changer="false" :total="pagination.total"
                                :show-total="(total, range) =>
                                    `${range[0]}-${range[1]} of ${total} reports`
                                    " @change="handleTableChange" />
                        </div>
                    </div>
                </a-card>
            </div>
        </div>
        <a-modal v-model:open="openModal" title="Basic Modal" width="80%" wrap-class-name="full-modal" @ok="handleOk">
            <a-row :gutter="[16, 16]">
                <a-col :span="8">
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <HomeOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Basic usage">
                        <template #prefix>
                            <user-outlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Extra information">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <HomeOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Basic usage">
                        <template #prefix>
                            <user-outlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Extra information">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <HomeOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Basic usage">
                        <template #prefix>
                            <user-outlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Extra information">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <HomeOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Basic usage">
                        <template #prefix>
                            <user-outlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Extra information">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <HomeOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Basic usage">
                        <template #prefix>
                            <user-outlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Extra information">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                </a-col>
                <a-col :span="8">
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <HomeOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Basic usage">
                        <template #prefix>
                            <user-outlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Extra information">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <HomeOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Basic usage">
                        <template #prefix>
                            <user-outlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Extra information">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <HomeOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Basic usage">
                        <template #prefix>
                            <user-outlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Extra information">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <HomeOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Basic usage">
                        <template #prefix>
                            <user-outlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Extra information">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <HomeOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Basic usage">
                        <template #prefix>
                            <user-outlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Extra information">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                </a-col>
                <a-col :span="8">
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <HomeOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Basic usage">
                        <template #prefix>
                            <user-outlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Extra information">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <HomeOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Basic usage">
                        <template #prefix>
                            <user-outlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Extra information">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-breadcrumb>
                        <a-breadcrumb-item href="">
                            <HomeOutlined />
                        </a-breadcrumb-item>
                        <a-breadcrumb-item>Trasactions</a-breadcrumb-item>
                    </a-breadcrumb>
                    <a-input v-model:value="userName" class="mb-3" placeholder="Basic usage">
                        <template #prefix>
                            <user-outlined />
                        </template>
                        <template #suffix>
                            <a-tooltip title="Extra information">
                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                            </a-tooltip>
                        </template>
                    </a-input>
                    <a-button class="mt-10" style="width: 100%; background: #15cc04; color: white;">
                        <template #icon>
                            <SaveOutlined />
                        </template>
                        continue adding checks
                    </a-button>
                </a-col>
            </a-row>
        </a-modal>
    </TreasuryLayout>
</template>

<script>
import debounce from "lodash/debounce";
import { SettingOutlined, TagOutlined, FolderAddOutlined, HomeOutlined, SaveOutlined } from '@ant-design/icons-vue';
export default {
    data() {
        return {
            isloadingtable: false,
            query: {
                search: ''
            },
            openModal: false,
        }
    },
    props: {
        data: Array,
        columns: Array,
        pagination: Array,

    },
    methods: {
        handleTableChange(page = 1) {
            this.isloadingtable = true;
            this.$inertia.get(route("manual_entry.checks"), {
                page: page,
            }, {
                preserveScroll: true,
            })
        },
        modalAddChecksManual() {
            this.openModal = true;
        }
    },
    watch: {
        query: {
            deep: true,
            handler: debounce(async function () {
                this.$inertia.get(route("manual_entry.checks"), {
                    searchQuery: this.query.search,
                }, { preserveState: true }, {
                });

            }, 600),
        }
    }
}
</script>