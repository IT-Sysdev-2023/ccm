<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head } from "@inertiajs/vue3";
const colors = "red";
</script>
<template>
    <AdminLayout>
        <template #header> </template>
        <a-breadcrumb class="mb-5">
            <a-breadcrumb-item href="">
                <home-outlined />
            </a-breadcrumb-item>
            <a-breadcrumb-item href="">
                <DingtalkOutlined />
                <span>Reports</span>
            </a-breadcrumb-item>
            <a-breadcrumb-item>Deposited Check Reports</a-breadcrumb-item>
        </a-breadcrumb>
        <a-range-picker v-model:value="depositDateRange" :disabled-date="disabledDate" class="mb-5 mr-2" @change="handleDateRangeChange" />
        <a-select placeholder="Select Business Unit " ref="select" v-model:value="selectBunit" style="width: 200px" @change="handleSelectBunitChange" >
            <a-select-option v-for="(item, key) in buData" :key="key" v-model:value="item.businessunit_id">{{ item.bname }}</a-select-option>
        </a-select>
        <a-button @click="fetchData">
            fetch data
        </a-button>
        <a-table :data-source="data.data" :columns="columns" size="small" bordered :pagination="false">

        </a-table>
    </AdminLayout>
</template>
<script>
export default {
    props: {
        buData: Object,
        data: Object,
        columns: Array,
        dateRangeBackend: Array
    },
    data() {
        return {
            dateRangeData: this.dateRangeBackend,
            selectBunit: null,
        }
    },
    methods: {
        handleDateRangeChange(dateObject, dateString){
            this.dateRangeData = dateString;
            // window.alert(dateString)
        },
        handleSelectBunitChange(value){
            this.selectBunit = value;
        },
        fetchData(){
            this.$inertia.get(route('deposited.checks'), {
                dateRange: this.dateRangeData,
                bunitId: this.selectBunit,
            });
        }
    }
}
</script>