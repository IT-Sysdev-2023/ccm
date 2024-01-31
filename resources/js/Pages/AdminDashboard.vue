<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            </div>
        </div>
    </AdminLayout>
</template>
