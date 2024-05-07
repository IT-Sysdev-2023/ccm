<script setup>
import AccountingLayout from '@/Layouts/AccountingLayout.vue';
</script>

<template>

    <Head title="Dashboard" />

    <AccountingLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">this is accounting Dashboard</h2>
        </template>

        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-10">
                    <a-row :gutter="[16, 16]">
                        <a-col :span="12" class="flex justify-center">
                            <div
                                class="w-full h-40 flex flex-col justify-center gap-2 bg-neutral-50 rounded-lg shadow p-2">
                                <div class="flex gap-2">
                                    <img class="bg-neutral-500 w-24 h-24 shrink-0 rounded-lg" alt="" src="/images/datedc.jpg" />
                                    <div class="flex flex-col">
                                        <span class="font-bold text-neutral-700 mb-2">Dated and Post Dated
                                            Cheques</span>
                                        <p class="line-clamp-3">

                                            "Dated and post-dated cheques are like promises written in ink, holding the
                                            value of
                                            trust for tomorrow, yet bearing the weight of certainty for today."
                                        </p>
                                    </div>
                                </div>
                                <a-button block @click="() => $inertia.get(route('datedpcchecks.accounting'))">
                                    <template #icon>
                                        <SwapRightOutlined />
                                    </template>
                                    click here to go to tables
                                </a-button>
                            </div>


                        </a-col>
                        <a-col :span="12" class="flex justify-center">

                            <div
                                class="w-full h-40 flex flex-col justify-center gap-2 bg-neutral-50 rounded-lg shadow p-2">
                                <div class="flex gap-2">
                                    <img class="bg-neutral-500 w-24 h-24 shrink-0 rounded-lg" alt=""  src="/images/bounce.jpg"/>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-neutral-700 mb-2"> Deposited Cheques Reports</span>
                                        <p class="line-clamp-3">

                                            "Deposited cheques reports: where numbers transform into narratives,
                                            detailing the
                                            journeys of transactions, echoing the rhythm of commerce, and sketching the
                                            portrait
                                            of financial stability."
                                        </p>
                                    </div>
                                </div>
                                <a-button block>
                                    <template #icon>
                                        <SwapRightOutlined />
                                    </template>
                                    click here to go to tables
                                </a-button>
                            </div>

                        </a-col>
                        <a-col :span="12" class="flex justify-center">

                            <div
                                class="w-full h-40 flex flex-col justify-center gap-2 bg-neutral-50 rounded-lg shadow p-2">
                                <div class="flex gap-2">
                                    <img class="bg-neutral-500 w-24 h-24 shrink-0 rounded-lg" alt="" src="/images/depost.jpg" />
                                    <div class="flex flex-col">
                                        <span class="font-bold text-neutral-700 mb-2"> Bounce Cheques Reports</span>
                                        <p class="line-clamp-3">

                                            "Bounce cheques reports: where financial tales take unexpected turns,
                                            revealing the
                                            fragile dance between funds and faith, reminding us that even promises can
                                            falter in
                                            the face of uncertainty."
                                        </p>
                                    </div>
                                </div>
                                <a-button block>
                                    <template #icon>
                                        <SwapRightOutlined />
                                    </template>
                                    click here to go to tables
                                </a-button>
                            </div>

                        </a-col>
                        <a-col :span="12" class="flex justify-center">
                            <div
                                class="w-full h-40 flex flex-col justify-center gap-2 bg-neutral-50 rounded-lg shadow p-2">
                                <div class="flex gap-2">
                                    <img class="bg-neutral-500 w-24 h-24 shrink-0 rounded-lg" alt="" src="/images/redeem.jpg" />
                                    <div class="flex flex-col">
                                        <span class="font-bold text-neutral-700 mb-2"> Redeem Post Dated Cheques
                                            Report</span>
                                        <p class="line-clamp-3">

                                            "Redeem post-dated cheques report: a chronicle of promises fulfilled, where
                                            anticipation meets resolution, and the currency of trust is exchanged for
                                            the
                                            satisfaction of certainty."
                                        </p>
                                    </div>
                                </div>
                                <a-button block>
                                    <template #icon>
                                        <SwapRightOutlined />
                                    </template>
                                    click here to go to tables
                                </a-button>
                            </div>

                        </a-col>


                    </a-row>
                </div>
            </div>
        </div>
    </AccountingLayout>
</template>
