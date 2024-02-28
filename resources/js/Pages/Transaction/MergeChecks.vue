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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-table :dataSource="data" :columns="columns" size="small" bordered>

                </a-table>
            </div>
        </div>
    </TreasuryLayout>
</template>
<script>
export default {
    props: {
        data: Array,
        columns: Array
    }
}
</script>