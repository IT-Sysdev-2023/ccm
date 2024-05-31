<template>
    <a-breadcrumb class="mb-5">
        <a-breadcrumb-item>Home</a-breadcrumb-item>
        <a-breadcrumb-item><a href="">Adjustments</a></a-breadcrumb-item>
        <a-breadcrumb-item><a href="">Alta Citta Check</a></a-breadcrumb-item>
    </a-breadcrumb>
    <a-table :data-source="data.data" :columns="columns" :pagination="false" bordered size="small">
    </a-table>
    <pagination class="mt-5" :datarecords="data"></pagination>
</template>
<script>

import AdminLayout from "@/Layouts/AdminLayout.vue";
export default {
    layout: AdminLayout,
    props: {
        data: Object,
        columns: Array,
    }
}
</script>
