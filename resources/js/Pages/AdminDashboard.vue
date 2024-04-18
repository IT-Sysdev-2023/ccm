<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div>
            <a-row :gutter="[16, 16]">
                <a-col :span="12">
                    <div id="chart">
                        <apexchart
                            type="polarArea"
                            width="380"
                            :options="chartOptions"
                            :series="series"
                        ></apexchart>
                    </div>
                </a-col>
                <a-col :span="12"> </a-col>
            </a-row>
        </div>
    </AdminLayout>
</template>

<script>
export default {
    data() {
        return {
            series: [42, 47, 52, 58, 65],
            chartOptions: {
                chart: {
                    width: 380,
                    type: "polarArea",
                },
                labels: ["Rose A", "Rose B", "Rose C", "Rose D", "Rose E"],
                fill: {
                    opacity: 1,
                },
                stroke: {
                    width: 1,
                    colors: undefined,
                },
                yaxis: {
                    show: false,
                },
                legend: {
                    position: "bottom",
                },
                plotOptions: {
                    polarArea: {
                        rings: {
                            strokeWidth: 0,
                        },
                        spokes: {
                            strokeWidth: 0,
                        },
                    },
                },
                theme: {
                    monochrome: {
                        enabled: true,
                        shadeTo: "light",
                        shadeIntensity: 0.6,
                    },
                },
            },
        };
    },
};
</script>
