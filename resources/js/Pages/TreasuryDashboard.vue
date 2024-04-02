<script setup>
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
</script>

<template>
    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                This is treasury Dashboard
            </h2>
        </template>

        <div class="py-4">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-row :gutter="[16, 16]" class="mt-4">
                    <a-col :span="8">
                        <a-card>
                            <div class="container">
                                <div class="skill-box">
                                    <span class="title">Cheques</span>

                                    <div class="skill-bar">
                                        <span class="skill-per html">
                                            <span class="tooltip">80%</span>
                                        </span>
                                    </div>
                                </div>

                                <div class="skill-box">
                                    <span class="title"
                                        >Post Dated Cheques</span
                                    >

                                    <div class="skill-bar">
                                        <span class="skill-per css">
                                            <span class="tooltip">30%</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="skill-box">
                                    <span class="title">Dated Cheques</span>

                                    <div class="skill-bar">
                                        <span class="skill-per javascript">
                                            <span class="tooltip">40%</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="skill-box">
                                    <span class="title">Deposited Checks</span>

                                    <div class="skill-bar">
                                        <span class="skill-per nodejs">
                                            <span class="tooltip">60%</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="skill-box">
                                    <span class="title">Bounced Checks</span>

                                    <div class="skill-bar">
                                        <span class="skill-per nodejs">
                                            <span class="tooltip">60%</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="skill-box">
                                    <span class="title"
                                        >Replacement Cheques</span
                                    >

                                    <div class="skill-bar">
                                        <span class="skill-per nodejs">
                                            <span class="tooltip">60%</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a-card>
                    </a-col>
                    <a-col :span="16">
                        <a-row :gutter="[16, 16]" class="mt-4">
                            <a-col :span="8">
                                <div
                                    class="walletBalanceCard"
                                    style="
                                        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px
                                                4px,
                                            rgba(0, 0, 0, 0.3) 0px 7px 13px -3px,
                                            rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
                                        background: linear-gradient(
                                            to right,
                                            #378ce7,
                                            violet
                                        );
                                    "
                                >
                                    <div class="svgwrapper">
                                        <img
                                            src="../../../public/svg/checks.svg"
                                            alt=""
                                        />
                                    </div>

                                    <div class="balancewrapper">
                                        <span class="balanceHeading"
                                            >Cheques</span
                                        >
                                        <p class="balance">
                                            <span id="currency"
                                                ><ScheduleOutlined />
                                                &nbsp;</span
                                            >20000
                                        </p>
                                    </div>

                                    <button class="addmoney">
                                        <span class="plussign">+</span>Add Money
                                    </button>
                                </div>
                            </a-col>
                            <a-col :span="8">
                                <div
                                    class="walletBalanceCard"
                                    style="
                                        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px
                                                4px,
                                            rgba(0, 0, 0, 0.3) 0px 7px 13px -3px,
                                            rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
                                        background: linear-gradient(
                                            to right,
                                            #ef4040,
                                            orange
                                        );
                                    "
                                >
                                    <div class="svgwrapper">
                                        <img
                                            src="../../../public/svg/pdc.svg"
                                            alt=""
                                        />
                                    </div>

                                    <div class="balancewrapper">
                                        <span class="balanceHeading">PDC</span>
                                        <p class="balance">
                                            <span id="currency"
                                                ><ScheduleOutlined />
                                                &nbsp;</span
                                            >20000
                                        </p>
                                    </div>

                                    <button class="addmoney">
                                        <span class="plussign">+</span>Add Money
                                    </button>
                                </div></a-col
                            >
                            <a-col :span="8">
                                <div
                                    class="walletBalanceCard"
                                    style="
                                        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px
                                                4px,
                                            rgba(0, 0, 0, 0.3) 0px 7px 13px -3px,
                                            rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
                                        background: linear-gradient(
                                            to right,
                                            #219c90,
                                            skyblue
                                        );
                                    "
                                >
                                    <div class="svgwrapper">
                                        <img
                                            src="../../../public/svg/dated.svg"
                                            alt=""
                                        />
                                    </div>

                                    <div class="balancewrapper">
                                        <span class="balanceHeading"
                                            >Dated</span
                                        >
                                        <p class="balance">
                                            <span id="currency"
                                                ><ScheduleOutlined />
                                                &nbsp;</span
                                            >20000
                                        </p>
                                    </div>

                                    <button class="addmoney">
                                        <span class="plussign">+</span>Add Money
                                    </button>
                                </div></a-col
                            >
                            <a-col :span="8">
                                <div
                                    class="walletBalanceCard"
                                    style="
                                        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px
                                                4px,
                                            rgba(0, 0, 0, 0.3) 0px 7px 13px -3px,
                                            rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
                                        background: linear-gradient(
                                            to right,
                                            #1d5b79,
                                            pink
                                        );
                                    "
                                >
                                    <div class="svgwrapper">
                                        <img
                                            src="../../../public/svg/deposited.svg"
                                            alt=""
                                        />
                                    </div>

                                    <div class="balancewrapper">
                                        <span class="balanceHeading"
                                            >Deposited</span
                                        >
                                        <p class="balance">
                                            <span id="currency"
                                                ><ScheduleOutlined />
                                                &nbsp;</span
                                            >20000
                                        </p>
                                    </div>

                                    <button class="addmoney">
                                        <span class="plussign">+</span>Add Money
                                    </button>
                                </div></a-col
                            >
                            <a-col :span="8">
                                <div
                                    class="walletBalanceCard"
                                    style="
                                        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px
                                                4px,
                                            rgba(0, 0, 0, 0.3) 0px 7px 13px -3px,
                                            rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
                                        background: linear-gradient(
                                            to right,
                                            black,
                                            skyblue
                                        );
                                    "
                                >
                                    <div class="svgwrapper">
                                        <img
                                            src="../../../public/svg/bounced.svg"
                                            alt=""
                                        />
                                    </div>

                                    <div class="balancewrapper">
                                        <span class="balanceHeading"
                                            >Bounced</span
                                        >
                                        <p class="balance">
                                            <span id="currency"
                                                ><ScheduleOutlined />
                                                &nbsp;</span
                                            >20000
                                        </p>
                                    </div>

                                    <button class="addmoney">
                                        <span class="plussign">+</span>Add Money
                                    </button>
                                </div></a-col
                            >
                            <a-col :span="8">
                                <div
                                    class="walletBalanceCard"
                                    style="
                                        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px
                                                4px,
                                            rgba(0, 0, 0, 0.3) 0px 7px 13px -3px,
                                            rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
                                        background: linear-gradient(
                                            to right,
                                            red,
                                            blue
                                        );
                                    "
                                >
                                    <div class="svgwrapper">
                                        <img
                                            src="../../../public/svg/replacement.svg"
                                            alt=""
                                        />
                                    </div>

                                    <div class="balancewrapper">
                                        <span class="balanceHeading"
                                            >Replacement</span
                                        >
                                        <p class="balance">
                                            <span id="currency"
                                                ><ScheduleOutlined />
                                                &nbsp;</span
                                            >20000
                                        </p>
                                    </div>

                                    <button class="addmoney">
                                        <span class="plussign">+</span>Add Money
                                    </button>
                                </div>
                            </a-col>
                        </a-row>
                        <!-- <p>{{ holiday }}</p> -->
                        <a-collapse
                            v-model:activeKey="activeKey"
                            class="mt-10"
                            style="
                                box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px,
                                    rgba(0, 0, 0, 0.3) 0px 7px 13px -3px,
                                    rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
                                background: linear-gradient(
                                    to right,
                                    #bdc3c7,
                                    #2c3e50
                                );
                            "
                        >
                            <a-collapse-panel
                                key="1"
                                header="Click this to see calendar"
                            >
                                <a-calendar v-model:value="value">
                                    <template #dateCellRender="{ current }">
                                        <div
                                            :class="
                                                validateDayOfMonth(current) &&
                                                someFunction(current)
                                                    ? 'bg-green-300 text-black'
                                                    : someFunction(current)
                                                    ? 'bg-green-600 text-white'
                                                    : validateDayOfMonth(
                                                          current
                                                      ) && isWeekend(current)
                                                    ? 'bg-red-100'
                                                    : validateDayOfMonth(
                                                          current
                                                      )
                                                    ? 'bg-blue-200'
                                                    : isWeekend(current)
                                                    ? 'bg-red-400'
                                                    : !someFunction(current)
                                                    ? 'bg-blue-400'
                                                    : ''
                                            "
                                            style="
                                                width: 100%;
                                                height: 100%;
                                                box-shadow: rgba(0, 0, 0, 0.4)
                                                        0px 2px 4px,
                                                    rgba(0, 0, 0, 0.3) 0px 7px
                                                        13px -3px,
                                                    rgba(0, 0, 0, 0.2) 0px -3px 0px
                                                        inset;
                                                border-radius: 10px;
                                            "
                                        >
                                            <span
                                                v-if="someFunction(current)"
                                                class="flex justify-center items-center"
                                                style="height: inherit"
                                            >
                                                {{
                                                    holiday.find(
                                                        (item) =>
                                                            item.date.date ===
                                                            dayjs(
                                                                current
                                                            ).format(
                                                                "YYYY-MM-DD"
                                                            )
                                                    ).title
                                                }}
                                            </span>
                                        </div>
                                    </template>
                                    <template #monthCellRender="{ current }">
                                        <!-- <div
                                            v-if="getMonthData(current)"
                                            class="notes-month"
                                        >
                                            <section>
                                                {{ getMonthData(current) }}
                                            </section>
                                            <span>Backlog number</span>
                                        </div> -->
                                    </template>
                                </a-calendar>
                            </a-collapse-panel>
                        </a-collapse>
                    </a-col>
                </a-row>
            </div>
        </div>
    </TreasuryLayout>
</template>

<script>
import dayjs from "dayjs";
import isSameOrBefore from "dayjs/plugin/isSameOrBefore";
import isSameOrAfter from "dayjs/plugin/isSameOrAfter";

dayjs.extend(isSameOrAfter);
dayjs.extend(isSameOrBefore);

export default {
    data() {
        return {
            value: dayjs(),
            activeKey: null,
        };
    },
    props: {
        holiday: Object,
    },
    methods: {
        someFunction(current) {
            return this.holiday.some(
                (item) => item.date.date === dayjs(current).format("YYYY-MM-DD")
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
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 12px;
    padding: 0px 25px;
    font-family: Arial, Helvetica, sans-serif;
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
    letter-spacing: 0.5px;
}

.addmoney {
    padding: 1px 15px;
    border-radius: 20px;
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
