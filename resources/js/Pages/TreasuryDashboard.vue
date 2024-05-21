<template>

    <Head title="Treasury Dashboard" />
      <div class="py-4">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-row :gutter="[16, 16]" class="mt-4">
                    <a-col :span="9">

                        <a-card class="mt-4">
                            <div class="flex mb-1">
                                <p class="font-bold">
                                    <InfoCircleTwoTone class="mr-3" />
                                    If you have concerns please look for luisa -
                                    <PhoneOutlined /> 1815<br>
                                </p>
                            </div>
                            <div class="flex mb-10">

                                <p class="font-bold">
                                    <InfoCircleTwoTone class="mr-3" />
                                    Or contact this ip phones below !

                                </p>
                            </div>
                            <a-timeline>
                                <a-timeline-item>
                                    <PhoneOutlined /> 1951
                                </a-timeline-item>
                                <a-timeline-item>
                                    <PhoneOutlined /> 1815
                                </a-timeline-item>
                                <a-timeline-item>
                                    <PhoneOutlined /> 1821
                                </a-timeline-item>
                                <a-timeline-item>
                                    <PhoneOutlined /> 1844
                                </a-timeline-item>
                                <a-timeline-item>
                                    <PhoneOutlined /> 1847
                                </a-timeline-item>
                                <a-timeline-item>
                                    <PhoneOutlined /> 1953
                                </a-timeline-item>
                                <a-timeline-item>
                                    <PhoneOutlined /> 1819
                                </a-timeline-item>
                                <a-timeline-item>
                                    <PhoneOutlined /> 1822
                                </a-timeline-item>
                                <a-timeline-item>
                                    <PhoneOutlined /> 1854
                                </a-timeline-item>
                                <a-timeline-item>
                                    <PhoneOutlined /> 1850
                                </a-timeline-item>
                            </a-timeline>

                        </a-card>
                    </a-col>
                    <a-col :span="15">
                        <a-row :gutter="[16, 16]" class="mt-4">
                            <a-col :span="8">
                                <div class="walletBalanceCard" style="

                                        background: linear-gradient(
                                            to right,
                                            #378ce7,
                                            violet
                                        );
                                    ">

                                    <div class="svgwrapper">
                                        <img src="../../../public/svg/checks.svg" alt="" />
                                    </div>

                                    <div class="balancewrapper">
                                        <span class="balanceHeading">Cheques</span>
                                        <p class="balance">
                                            <span id="currency">
                                                <ScheduleOutlined />
                                                &nbsp;
                                            </span>{{ checkCount.toLocaleString() }}
                                        </p>
                                    </div>

                                    <div class="addmoney">
                                        <img src="../../../public/svg/checks.svg" alt="" />
                                    </div>
                                </div>
                            </a-col>
                            <a-col :span="8">
                                <div class="walletBalanceCard" style="

                                        background: linear-gradient(
                                            to right,
                                            #ef4040,
                                            orange
                                        );
                                    ">
                                    <div class="svgwrapper">
                                        <img src="../../../public/svg/pdc.svg" alt="" />
                                    </div>

                                    <div class="balancewrapper">
                                        <span class="balanceHeading">PDC</span>
                                        <p class="balance">
                                            <span id="currency">
                                                <ScheduleOutlined />
                                                &nbsp;
                                            </span>{{ pdcCount.toLocaleString() }}
                                        </p>
                                    </div>

                                    <button class="addmoney">
                                        <span class="plussign">+</span>Add Money
                                    </button>
                                </div>
                            </a-col>
                            <a-col :span="8">
                                <div class="walletBalanceCard" style="

                                        background: linear-gradient(
                                            to right,
                                            #219c90,
                                            skyblue
                                        );
                                    ">
                                    <div class="svgwrapper">
                                        <img src="../../../public/svg/dated.svg" alt="" />
                                    </div>

                                    <div class="balancewrapper">
                                        <span class="balanceHeading">Dated</span>
                                        <p class="balance">
                                            <span id="currency">
                                                <ScheduleOutlined />
                                                &nbsp;
                                            </span>{{ datedCount.toLocaleString() }}
                                        </p>
                                    </div>

                                    <button class="addmoney">
                                        <span class="plussign">+</span>Add Money
                                    </button>
                                </div>
                            </a-col>
                            <a-col :span="8">
                                <div class="walletBalanceCard" style="

                                        background: linear-gradient(
                                            to right,
                                            #1d5b79,
                                            pink
                                        );
                                    ">
                                    <div class="svgwrapper">
                                        <img src="../../../public/svg/deposited.svg" alt="" />
                                    </div>

                                    <div class="balancewrapper">
                                        <span class="balanceHeading">Deposited</span>
                                        <p class="balance">
                                            <span id="currency">
                                                <ScheduleOutlined />
                                                &nbsp;
                                            </span>{{
                                                depositedCount.toLocaleString()
                                            }}
                                        </p>
                                    </div>

                                    <button class="addmoney">
                                        <span class="plussign">+</span>Add Money
                                    </button>
                                </div>
                            </a-col>
                            <a-col :span="8">
                                <div class="walletBalanceCard" style="

                                        background: linear-gradient(
                                            to right,
                                            black,
                                            skyblue
                                        );
                                    ">
                                    <div class="svgwrapper">
                                        <img src="../../../public/svg/bounced.svg" alt="" />
                                    </div>

                                    <div class="balancewrapper">
                                        <span class="balanceHeading">Bounced</span>
                                        <p class="balance">
                                            <span id="currency">
                                                <ScheduleOutlined />
                                                &nbsp;
                                            </span>{{ bouncedCount.toLocaleString() }}
                                        </p>
                                    </div>

                                    <button class="addmoney">
                                        <span class="plussign">+</span>Add Money
                                    </button>
                                </div>
                            </a-col>
                            <a-col :span="8">
                                <div class="walletBalanceCard" style="

                                        background: linear-gradient(
                                            to right,
                                            red,
                                            blue
                                        );
                                    ">
                                    <div class="svgwrapper">
                                        <img src="../../../public/svg/replacement.svg" alt="" />
                                    </div>

                                    <div class="balancewrapper">
                                        <span class="balanceHeading">Replacement</span>
                                        <p class="balance">
                                            <span id="currency">
                                                <ScheduleOutlined />
                                                &nbsp;
                                            </span>{{
                                                replacementCount.toLocaleString()
                                            }}
                                        </p>
                                    </div>

                                    <button class="addmoney">
                                        <span class="plussign">+</span>Add Money
                                    </button>
                                </div>
                            </a-col>
                        </a-row>

                        <a-card class="mt-5">
                            <div id="chart">
                                <apexchart type="bar" height="350" :options="chartOptions" :series="series"></apexchart>
                            </div>
                        </a-card>

                    </a-col>
                </a-row>
            </div>
        </div>
</template>

<script>
import dayjs from "dayjs";
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";

export default {
    layout: TreasuryLayout,
    data() {
        return {
            value: dayjs(),
            activeKey: ["1"],
            series: [
                {
                    name: "Count",
                    data: [
                        this.checkCount,
                        this.pdcCount,
                        this.datedCount,
                        this.depositedCount,
                        this.bouncedCount,
                        this.replacementCount,
                    ],
                },
            ],
            chartOptions: {
                annotations: {
                    points: [
                        {
                            x: "Bananas",
                            seriesIndex: 0,
                            label: {
                                borderColor: "#775DD0",
                                offsetY: 0,
                                style: {
                                    color: "#fff",
                                    background: "#775DD0",
                                },
                                text: "Bananas are good",
                            },
                        },
                    ],
                },
                chart: {
                    height: 350,
                    type: "bar",
                },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        columnWidth: "50%",
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    width: 2,
                },

                grid: {
                    row: {
                        colors: ["#fff", "#f2f2f2"],
                    },
                },
                xaxis: {
                    labels: {
                        rotate: -45,
                    },
                    categories: [
                        "Cheques",
                        "Post Dated",
                        "Dated",
                        "Deposited",
                        "Bounced",
                        "Replacement",
                    ],
                    tickPlacement: "on",
                },
                yaxis: {
                    title: {
                        text: "Cheques",
                    },
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shade: "light",
                        type: "horizontal",
                        shadeIntensity: 0.25,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 0.85,
                        stops: [50, 0, 100],
                    },
                },
            },
        };
    },
    props: {
        holiday: Object,
        checkCount: Number,
        pdcCount: Number,
        datedCount: Number,
        depositedCount: Number,
        bouncedCount: Number,
        replacementCount: Number,
    },
    methods: {
        someFunction(current) {
            return this.holiday.some(
                (item) => item.date?.date === dayjs(current).format("YYYY-MM-DD")
            );
        },
        validateDayOfMonth(current) {
            const firstDayMonth = this.value.startOf("month");
            const endDayMonth = this.value.endOf("month");
            const toDate = dayjs(current);

            if (
                toDate.isSameOrAfter(firstDayMonth) &&
                toDate.isSameOrBefore(endDayMonth)
            ) {
                return false;
            }

            return true;
        },
        isWeekend(current) {
            const curr = dayjs(current).day();

            if (curr === 6 || curr === 0) {
                return true;
            }
            return false;
        },
    },
};
</script>

<style scoped>
.walletBalanceCard {
    height: 105px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 30px;
    padding: 0px 25px;
    font-family: Arial, Helvetica, sans-serif;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
}

.svgwrapper {
    width: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.svgwrapper img {
    width: 100%;
}

.balancewrapper {
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    flex-direction: column;
    width: 120px;
    gap: 0px;

}

.balanceHeading {
    font-size: 16px;
    color: rgb(214, 214, 214);
    font-weight: 100;
    letter-spacing: 0.6px;
}

.balance {
    margin-top: 5px;
    font-size: 13.5px;
    color: white;
    font-weight: 600;
    letter-spacing: 1.5px;
}

.currency span {
    letter-spacing: 2px;
}

.addmoney {
    padding: 1px 15px;
    border-radius: 5px;
    background-color: #c083eb;
    color: white;
    border: none;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    display: none;
}

.addmoney:hover {
    background-color: whitesmoke;
    color: #9c59cc;
}

.plussign {
    font-size: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.container {
    position: relative;
    width: 100%;
    /* background: #ececec; */
    margin: 0 15px;
    padding: 10px 20px;
    border-radius: 7px;
    user-select: none;
}

.container .skill-box {
    width: 100%;
    margin: 25px 0;
}

.skill-box .title {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #333;
}

.skill-box .skill-bar {
    height: 8px;
    width: 100%;
    border-radius: 6px;
    margin-top: 6px;
    background: rgba(0, 0, 0, 0.1);
}

.skill-bar .skill-per {
    position: relative;
    display: block;
    height: 100%;
    width: 90%;
    border-radius: 6px;
    background: linear-gradient(to right, #1d5b79, rgb(248, 109, 133));
    animation: progress 0.4s ease-in-out forwards;
    opacity: 0;
}

.skill-per.html {
    /*progreso de las diferentes lenguajes*/
    width: 80%;
    animation-delay: 0.1s;
}

.skill-per.css {
    /*progreso de las diferentes lenguajes*/
    width: 30%;
    animation-delay: 0.1s;
}

.skill-per.javascript {
    /*progreso de las diferentes lenguajes*/
    width: 40%;
    animation-delay: 0.2s;
}

.skill-per.nodejs {
    /*progreso de las diferentes lenguajes*/
    width: 70%;
    animation-delay: 0.3s;
}

@keyframes progress {
    0% {
        width: 0;
        opacity: 1;
    }

    100% {
        opacity: 1;
    }
}

.skill-per .tooltip {
    position: absolute;
    right: -14px;
    top: -28px;
    font-size: 9px;
    font-weight: 500;
    color: #fff;
    padding: 2px 6px;
    border-radius: 3px;
    background: #000000;

    z-index: 1;
}

.tooltip::before {
    content: "";
    position: absolute;
    left: 50%;
    bottom: -2px;
    height: 10px;
    width: 10px;
    z-index: -1;
    background: #000000;
    transform: translateX(-50%) rotate(45deg);
}
</style>
