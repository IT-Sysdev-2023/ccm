<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
import { Head } from '@inertiajs/vue3';
import { reactive } from 'vue';
</script>

<template>
    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">This is treasury Dashboard</h2>
        </template>

        <div class="py-4">

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-breadcrumb class="mb-5">
                    <a-breadcrumb-item href="">
                        <HomeOutlined />
                    </a-breadcrumb-item>
                    <a-breadcrumb-item href="">
                        <user-outlined />
                        <span>Ds&Bounce Tagging</span>
                    </a-breadcrumb-item>
                    <a-breadcrumb-item>Ds Taggings</a-breadcrumb-item>
                    <a-breadcrumb-item></a-breadcrumb-item>
                </a-breadcrumb>
                <a-row :gutter="[16, 16]">
                    <a-col :span="4">
                        <a-card style="width: 200px; height: 90px;" class="mb-5">
                            <div style="display: flex; ">
                                <a-badge count="0" style="display: flex; justify-content: center;">
                                    <a-avatar shape="square" size="large" src="../icons/abacus.png" />
                                </a-badge>
                                <p class="ml-10 font-bold">{{ defaultTotal.count }}</p>
                            </div>
                        </a-card>
                    </a-col>
                    <a-col :span="5">
                        <a-card style="width: 250px; height: 90px;" class="mb-5">
                            <div style="display: flex; ">
                                <a-badge count="0" style="display: flex; justify-content: center;">
                                    <a-avatar shape="square" size="large" src="../icons/calculator.png" />
                                </a-badge>
                                <p class="ml-10 font-bold">₱ {{ defaultTotal.totalSum.toFixed(2) }}</p>
                            </div>
                        </a-card>
                    </a-col>
                    <a-col :span="4">
                        <a-card style="width: 180px; height: 90px;" class="mb-5">
                            <div style="display: flex; ">
                                <a-badge count="0" style="display: flex; justify-content: center;">
                                    <a-avatar shape="square" size="large" src="../icons/due-date(1).png" />
                                </a-badge>
                                <p class="ml-10 font-bold">{{ due_dates }}</p>
                            </div>
                        </a-card>
                    </a-col>
                    <a-col :span="11">
                        <a-card style="width: 100%;" class="mb-5;">
                            <a-row :gutter="[16, 16]" class="mt-2">
                                <a-col :span="8">
                                    <a-input placeholder="Ds Number" v-model:value="dsNo">
                                        <template #suffix>
                                            <a-tooltip title="Please Enter a Ds Number">
                                                <info-circle-outlined style="color: rgba(0, 0, 0, 0.45)" />
                                            </a-tooltip>
                                        </template>
                                    </a-input>
                                </a-col>
                                <a-col :span="8">
                                    <a-date-picker v-model:value="dateDeposit" />
                                </a-col>
                                <a-col :span="8">
                                    <a-button @click="submitToConButton()">submit ds number</a-button>
                                </a-col>
                            </a-row>
                        </a-card>
                    </a-col>
                </a-row>
                <a-table :data-source="ds_c_table.data" :pagination="false" :columns="columns" size="small"
                    :scroll="{ x: 100, y: 470 }" class="components-table-demo-nested" bordered
                    :row-class-name="(_record, index) => (_record.type === 'POST-DATED' ? 'POST-DATED' : 'DATED')">
                    <template #bodyCell="{ column, record, index }">

                        <template v-if="column.key === 'select'">
                            <a-switch v-model:checked="switchValues[index]"
                                @change="handleSwitchChange(record, switchValues[index])" size="small">
                                <template #checkedChildren><check-outlined /></template>
                                <template #unCheckedChildren><close-outlined /></template>
                            </a-switch>

                        </template>


                    </template>
                </a-table>
                <div class="mt-3 mb-5" style="border: 1px solid rgb(224, 224, 224); border-radius: 10px; padding: 10px">
                    <div class="flex justify-end">
                        <a-pagination class="mt-0 mb-0" v-model:current="pagination.current"
                            v-model:page-size="pagination.pageSize" :show-size-changer="false" :total="pagination.total"
                            :show-total="(total, range) => `${range[0]}-${range[1]} of ${total} reports`"
                            @change="handleTableChange" />
                    </div>
                </div>
            </div>
        </div>
    </TreasuryLayout>
</template>

<script>
import { HomeOutlined, CheckOutlined, CloseOutlined, InfoCircleOutlined } from '@ant-design/icons-vue';
import { message } from 'ant-design-vue';
import dayjs from 'dayjs';

export default {

    data() {
        return {
            switchValues: [],
            countDs: 0,
            totalAmount: 0,
            dataAmount: [],
            defaultTotal: this.total,
            dateDeposit: dayjs(),
            dsNo: '',

        };
    },
    props: {
        pagination: Object,
        columns: Array,
        ds_c_table: Array,
        type: Object,
        total: Number,
        due_dates: Number,

    },
    computed: {

    },
    methods: {

        switchRecord() {
            this.switchValues = this.ds_c_table.data.map(value => value.done === '' ? false : true);
        },

        handleTableChange(page) {
            try {
                this.$inertia.get(route().current(), {
                    page: page
                });

            } catch (error) {
                console.error('Error fetching data:', error);
            }
        },
        async submitToConButton() {
            // console.log(this.switchValues);
            //    this.$inertia.post(route('submit.ds.tagging'), {});
            const selected = this.switchValues
                .filter(value => value)
                .map((value, index) => this.ds_c_table.data[index].checks_id)

            // console.log(selected);
            this.$inertia.post(route('submit.ds.tagging'),
                { selected, dsNo: this.dsNo, dateDeposit: this.dateDeposit.format('YYYY-MM-DD') },
                {
                    // onFinish: () => {
                    //     this.switchValues = this.ds_c_table.data.map(value => value.done === '' ? false : true);
                    //     this.defaultTotal.count = 0;
                    //     this.defaultTotal.totalSum = 0;
                    // }
                });
                this.switchValues = this.ds_c_table.data.map(value => value.done === '' ? false : true);


        },
        async handleSwitchChange(data, isChecked) {
            const res = await axios.put(route('update.switch'), { id: data.checks_id, isCheck: isChecked, checkAmount: data.check_amount, oldAmount: this.total.totalSum, oldCount: this.defaultTotal.count });

            this.defaultTotal.totalSum = res.data.newAmount;
            this.defaultTotal.count = res.data.newCount;

            const key = 'updatable';

            if (isChecked) {
                message.loading({
                    content: 'Loading...',
                    key,
                });
                setTimeout(() => {
                    message.success({
                        content: 'Checked Successfully!',
                        key,
                        duration: 2,
                    });
                }, 1000);

            } else {
                message.loading({
                    content: 'Loading...',
                    key,
                });
                setTimeout(() => {
                    message.warning({
                        content: 'Uncheck Successfully!',
                        key,
                        duration: 2,
                    });
                }, 1000);
            }


        },
    },
    created() {
        this.switchValues = this.ds_c_table.data.map(value => value.done === '' ? false : true);
    }
};
</script>

<style>
.DATED {
    background-color: rgba(94, 169, 255, 0.274);
    /* Set background color for DATED type */
}

.POST-DATED {
    background-color: rgba(255, 121, 121, 0.274);
    /* Set background color for POST-DATED type */
}
</style>