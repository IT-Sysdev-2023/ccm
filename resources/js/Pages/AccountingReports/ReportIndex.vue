<script setup>
import AccountingLayout from '@/Layouts/AccountingLayout.vue';
</script>

<template>

    <Head title="Dashboard" />

    <AccountingLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">this is accounting Dashboard</h2>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-20">
                    <a-row :gutter="[16, 16]">
                        <a-col :span="12" class="flex justify-center">
                            <a-card style="width: 100%; height: 150px;" class="flex justify-center items-center">
                                <CalendarOutlined />
                                Dated & Post Dated Cheques
                                <div class="mt-4">
                                    <a-button block @click="() => $inertia.get(route('datedpcchecks.accounting'))">
                                        <template #icon>
                                            <SwapRightOutlined />
                                        </template>
                                        click here to go to tables
                                    </a-button>
                                </div>
                            </a-card>

                        </a-col>
                        <a-col :span="12" class="flex justify-center">
                            <a-card style="width: 100%; height: 150px;" class="flex justify-center items-center">
                                <FieldTimeOutlined />
                                Deposited Cheques Reports

                                <div class="mt-4">
                                    <a-button block>
                                        <template #icon>
                                            <SwapRightOutlined />
                                        </template>
                                        click here to go to tables
                                    </a-button>
                                </div>
                            </a-card>

                        </a-col>
                        <a-col :span="12" class="flex justify-center">
                            <a-card style="width: 100%; height: 150px;" class="flex justify-center items-center">
                                <FundOutlined />
                                Bounce Cheques Reports

                                <div class="mt-4">
                                    <a-button block>
                                        <template #icon>
                                            <SwapRightOutlined />
                                        </template>
                                        click here to go to tables
                                    </a-button>
                                </div>
                            </a-card>

                        </a-col>
                        <a-col :span="12" class="flex justify-center">
                            <a-card style="width: 100%; height: 150px;" class="flex justify-center items-center">
                                <DeliveredProcedureOutlined />
                                Redeem Post Dated Cheques Report

                                <div class="mt-4">
                                    <a-button block>
                                        <template #icon>
                                            <SwapRightOutlined />
                                        </template>
                                        click here to go to tables
                                    </a-button>
                                </div>
                            </a-card>

                        </a-col>


                    </a-row>
                </div>
            </div>
        </div>
    </AccountingLayout>
</template>
