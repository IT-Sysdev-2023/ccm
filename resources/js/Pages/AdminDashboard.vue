<template>

    <Head title="Admin Dashboard" />
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
            <!-- {{  getOnlineUsers }} -->
            <a-col :span="16">
                <p class="font-bold mb-5 text-gray-500 text-center">
                    ALL CHECQUE COUNTS
                </p>
                <div
                    style="border: 1px solid #EEEDEB; display: flex; justify-content: center; padding-top: 50px; padding-bottom: 50px; border-radius: 10px; box-shadow: rgba(0, 0, 0, 0.05) 0px 1px 2px 0px;">
                    <div id="chart">

                        <apexchart style="height: 100px;" type="polarArea" width="600" :options="chartOptions"
                            :series="series">
                        </apexchart>
                    </div>
                </div>

                <p class="font-bold text-gray-500 text-center mb-5 mt-10">
                    WEEKLY ACTIVITIES
                </p>
                <div
                    style="border: 1px solid #EEEDEB; padding: 20px; border-radius: 10px; box-shadow: rgba(0, 0, 0, 0.05) 0px 1px 2px 0px;">
                    <div id="chart">

                        <apexchart type="line" height="390" :options="chartOptions2" :series="series2"></apexchart>
                    </div>

                </div>
            </a-col>
            <a-col :span="8">
                <p class="font-bold mb-2 text-gray-500 text-center">
                    <a :href="route('users.index')"><a-button block>
                            {{ users.length }} registered users
                        </a-button></a>
                </p>
                <a-card>
                    <p class="mb-2 text-center text-blue-500">
                        ( {{ getOnlineUsers.length }} ) Online users
                    </p>
                    <a-card v-for="item in getOnlineUsers" class="mb-1">
                        <div class="flex">
                            <span><img src="/ccmlogo/onlinebadge.png"
                                    style="height: 15px; position: relative; left: 57px; background: rgb(1, 255, 1); border: 1px solid rgb(1, 255, 1); border-radius: 50%;" alt=""></span>
                            <img :src="item.image" style="height: 50px; border-radius: 50%"
                                class="mr-5" alt="image">
                            <p>
                                {{ item.name }} <br>
                                {{ item.usertype == 1 ? 'Admin' : item.usertype == 9 ? 'Treasury' : 'Accounting' }}

                            </p>
                        </div>
                    </a-card>
                </a-card>
            </a-col>
        </a-row>
    </div>
</template>
<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import dayjs from "dayjs";
import { mapState, mapActions } from 'pinia'
import { useOnlineUsersStore } from '@/stores/online-users'
export default {

    layout: AdminLayout,
    props: {
        users: Array,
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

    computed: {
        ...mapState(useOnlineUsersStore, ['getOnlineUsers']),
    },

};
</script>

