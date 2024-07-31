<template>
    <a-modal mask style="width: 100%; top: 5px;" :footer="null">
        <a-card class="mt-7">
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
                        <a-statistic title="Check Amount" :value="record.check_amount" :precision="2" class="demo-class"
                            :value-style="{ color: '#3FA2F6' }">
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
                <a-tab-pane key="6">
                    <template #tab>
                        <span>
                            <ArrowDownOutlined />
                            Select Type Below
                        </span>
                    </template>
                    <a-typography-title :level="5">
                        <ToolFilled /> Select Type
                    </a-typography-title>
                    <br>
                    <a-result status="404" title="Select Check Type"
                        sub-title="Select Type first in the side bar below to continue!">

                    </a-result>
                </a-tab-pane>
                <a-tab-pane key="1">
                    <template #tab>
                        <span>
                            <MoneyCollectFilled />
                            Cash
                        </span>
                    </template>
                    <a-typography-title :level="5">
                        <ToolFilled /> Cash
                    </a-typography-title>
                    <br>
                    <Cash :record="record" :type="1" @close="close" />
                </a-tab-pane>
                <a-tab-pane key="2">
                    <template #tab>
                        <span>
                            <IdcardFilled />
                            Check
                        </span>
                    </template>
                    <a-typography-title :level="5">
                        <ToolFilled /> Check
                    </a-typography-title>
                    <br>
                    <Check  @close="close" :amount="record.check_amount" :id="record.checks_id" :currency="currency"
                        :check-class="checkClass" :category="category" :type="1" />
                </a-tab-pane>
                <a-tab-pane key="3">
                    <template #tab>
                        <span>
                            <CreditCardFilled />
                            Check & Cash
                        </span>
                    </template>
                    <a-typography-title :level="5">
                        <ToolFilled /> Check and Cash
                    </a-typography-title>
                    <br>
                    <Check  @close="close" :amount="record.check_amount" :id="record.checks_id" :currency="currency"
                        :check-class="checkClass" :category="category" :type="2" />
                </a-tab-pane>
                <a-tab-pane key="4">
                    <template #tab>
                        <span>
                            <MoneyCollectFilled />
                            Partial Payment Cash
                        </span>
                    </template>
                    <a-typography-title :level="5">
                        <ToolFilled /> Partial Payment Cash
                    </a-typography-title>
                    <br>
                    <Cash  @close="close" :record="record" :type="2" />
                </a-tab-pane>
                <a-tab-pane key="5">
                    <template #tab>
                        <span>
                            <IdcardFilled />
                            Partial Payment Check
                        </span>
                    </template>
                    <a-typography-title :level="5">
                        <ToolFilled /> Partial Payment Check
                    </a-typography-title>
                    <br>
                    <Check  @close="close" :amount="record.check_amount" :id="record.checks_id" :currency="currency"
                        :check-class="checkClass" :category="category" :type="3" />
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
            activeKey: '6',
        }
    },
    methods: {
        close() {
            this.$emit('close-modal');
        },
    }
}
</script>
