<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import dayjs from "dayjs";
</script>

<template>

    <Head title="Dashboard" />

    <AdminLayout>
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template> -->

        <div>
            <a-breadcrumb class="mb-5">
                <a-breadcrumb-item href="">
                    <home-outlined />
                </a-breadcrumb-item>
                <a-breadcrumb-item href="">
                    <DashboardOutlined />
                    <span>Dashboard</span>
                </a-breadcrumb-item>
                <a-breadcrumb-item>Charts</a-breadcrumb-item>
            </a-breadcrumb>

            <a-row :gutter="[16, 16]">

                <a-col :span="12">
                    <a-card style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">
                        <div id="chart">
                            <p class="font-bold underline">
                                All cheque counts
                            </p>
                            <apexchart style="height: 100px;" type="polarArea" width="600" :options="chartOptions"
                                :series="series">
                            </apexchart>
                        </div>
                    </a-card>
                </a-col>
                <a-col :span="12">
                    <a-card style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">

                        <div id="chart">
                            <p class="font-bold  underline">
                                Weekly Activities
                            </p>
                            <apexchart type="line" height="390" :options="chartOptions2" :series="series2"></apexchart>
                        </div>
                    </a-card>
                </a-col>
            </a-row>
        </div>
    </AdminLayout>
</template>
