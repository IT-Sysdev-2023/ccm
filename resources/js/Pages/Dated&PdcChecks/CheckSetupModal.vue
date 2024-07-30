<template>
    <a-modal  mask style="width: 65%; top: 50px;" title="Basic Modal" :footer="null">
        <a-card>
            <a-row class="mb-3">
                <a-col :span="10">
                    <a-descriptions size="small" layout="horizontal" bordered>
                        <a-descriptions-item class="text-right" label="Check No.">{{ record.check_no
                            }}</a-descriptions-item>
                    </a-descriptions>
                    <a-descriptions size="small" layout="horizontal" bordered>
                        <a-descriptions-item class="text-right" label="Check Date">{{ record.check_date
                            }}</a-descriptions-item>
                    </a-descriptions>
                </a-col>
                <a-col :span="14">
                    <div class="flex justify-center">
                        <a-statistic title="Check Amount" :value="record.check_amount" :precision="2"
                            class="demo-class" :value-style="{ color: '#3FA2F6' }">
                            <template #prefix>
                                <LikeFilled />
                            </template>
                        </a-statistic>
                    </div>
                </a-col>
            </a-row>
            <hr>
            <br>
            <a-tabs v-model:activeKey="activeKey" type="card" tab-position="left">
                <a-tab-pane key="1">
                    <template #tab>
                        <span>
                            <MoneyCollectFilled />
                            Cash
                        </span>
                    </template>
                    <Cash :record="record"/>
                </a-tab-pane>
                <a-tab-pane key="2">
                    <template #tab>
                        <span>
                            <IdcardFilled />
                            Check
                        </span>
                    </template>
                    <Check :id="record.checks_id" :currency="currency" :check-class="checkClass" :category="category"/>
                </a-tab-pane>
                <a-tab-pane key="3">
                    <template #tab>
                        <span>
                            <CreditCardFilled />
                            Check & Cash
                        </span>
                    </template>
                    Tab 2
                </a-tab-pane>
                <a-tab-pane key="4">
                    <template #tab>
                        <span>
                            <MoneyCollectFilled />
                            Partial Payment Cash
                        </span>
                    </template>
                    Tab 2
                </a-tab-pane>
                <a-tab-pane key="5">
                    <template #tab>
                        <span>
                            <IdcardFilled />
                            Partial Payment Check
                        </span>
                    </template>
                    Tab 2
                </a-tab-pane>
            </a-tabs>
        </a-card>
    </a-modal>
</template>

<script>
export default {
    props: {
        record: Object,
        currency: Object,
        checkClass: Object,
        category: Object
    },
    data() {
        return {
            activeKey: '1',
        }
    }
}
</script>
