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
                    <div
                        style="border: 1px solid #EEEDEB; padding: 20px; border-radius: 1rem; box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;">
                        <div id="chart">
                            <p class="font-bold mb-20 text-center">
                                ALL CHECQUE COUNTS
                            </p>
                            <apexchart style="height: 100px;" type="polarArea" width="600" :options="chartOptions"
                                :series="series">
                            </apexchart>
                        </div>
                    </div>
                </a-col>
                <a-col :span="12">
                    <div
                        style="border: 1px solid #EEEDEB; padding: 20px; border-radius: 1rem; box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;">
                        <div id="chart">
                            <p class="font-bold text-center mb-20">
                                WEEKLY ACTIVITIES
                            </p>
                            <apexchart type="line" height="390" :options="chartOptions2" :series="series2"></apexchart>
                        </div>

                    </div>
                </a-col>
            </a-row>
        </div>
    </AdminLayout>
</template>
<script>
export default {
    props: {
        pmCounts: Object,
        icm: Object,
        asc: Object,
        pmWeekly: Object,
        icmWeekly: Object,
        ascMainWeekly: Object,
    },
    data() {
        return {
            series: [this.pmCounts, this.icm, this.asc],
            chartOptions: {
                chart: {
                    width: 380,
                    type: "polarArea",
                },
                labels: ["PLAZA MARCELA", "ISLAND CITY MALL", "ASC-MAIN"],
                fill: {
                    opacity: 1,
                },
                stroke: {
                    colors: ['#fff']
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            },
            series2: [{
                name: 'PLAZA MARCELA',
                type: 'column',
                data: this.pmWeekly.map(item => item.y)
            }, {
                name: 'ISLAND CITY MALL',
                type: 'area',
                data: this.icmWeekly.map(item => item.y)
            }, {
                name: 'ASC-MAIN',
                type: 'line',
                data: this.ascMainWeekly.map(item => item.y)
            }],
            chartOptions2: {
                chart: {
                    height: 350,
                    type: 'line',
                    stacked: false,
                },
                stroke: {
                    width: [0, 2, 5],
                    curve: 'smooth'
                },
                plotOptions: {
                    bar: {
                        columnWidth: '50%'
                    }
                },

                fill: {
                    opacity: [0.85, 0.25, 1],
                    gradient: {
                        inverseColors: false,
                        shade: 'light',
                        type: "vertical",
                        opacityFrom: 0.85,
                        opacityTo: 0.55,
                        stops: [0, 100, 100, 100]
                    }
                },
                labels: this.pmWeekly.map(item => dayjs(item.x).format('MMM-DD')),
                markers: {
                    size: 0
                },

                yaxis: {
                    title: {
                        text: 'Weekly Cheques',
                    },
                    min: 0
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function (y) {
                            if (typeof y !== "undefined") {
                                return y.toFixed(0) + " Cheques";
                            }
                            return y;

                        }
                    }
                }
            },
        };
    },
};
</script>
