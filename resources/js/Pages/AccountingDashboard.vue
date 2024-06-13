<template>

    <Head title="Accounting Dashboard" />
    <div class="py-5">
        <a-row :gutter="[16, 16]">
            <a-col :span="17">
                <a-row :gutter="[16, 16]">
                    <a-col :span="8">
                        <div class="page ">
                            <div class="margin"></div>
                            <p class="mt-10">Checks</p>
                            <!-- <br> -->
                            <br>
                            {{ checkCount.toLocaleString() }}
                        </div>
                    </a-col>
                    <a-col :span="8">
                        <div class="page">
                            <div class="margin"></div>
                            <p class="mt-10">Post Dated Checks</p>
                            <br>
                            {{ pdcCount.toLocaleString() }}
                        </div>
                    </a-col>
                    <a-col :span="8">
                        <div class="page">
                            <div class="margin"></div>
                            <p class="mt-10">Dated Checks </p>
                            <br>
                            <p>{{ datedCount.toLocaleString() }}</p>
                        </div>
                    </a-col>
                    <a-col :span="8">
                        <div class="page">
                            <div class="margin"></div>
                            <p class="mt-10">Deposited Count</p>
                            <br>
                            <p>{{ depositedCount.toLocaleString() }}</p>
                        </div>
                    </a-col>
                    <a-col :span="8">
                        <div class="page">
                            <div class="margin"></div>
                            <p class="mt-10">Dated Checks</p>
                            <br>
                            <p>{{ bouncedCount.toLocaleString() }}</p>
                        </div>
                    </a-col>
                    <a-col :span="8">
                        <div class="page">
                            <div class="margin"></div>
                            <p class="mt-10">Dated Checks</p>
                            <br>
                            <p>{{ replacementCount.toLocaleString() }}</p>
                        </div>
                    </a-col>

                </a-row>
            </a-col>
            <a-col :span="7">
                <p id="MyClockDisplay" class="clock text-end mb-5">
                    {{ currentTime }}
                </p>
                <a-card class="">
                    <div class="flex mb-1">
                        <p class="font-bold">
                            <InfoCircleTwoTone class="mr-3" />
                            If you have concerns please look for luisa -
                            <PhoneOutlined /> 1815<br>
                        </p>
                    </div>
                    <div class="flex mb-4">

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
        </a-row>


    </div>
</template>

<script>
import AccountingLayout from '@/Layouts/AccountingLayout.vue';
export default {
    layout: AccountingLayout,
    props: {
        checkCount: Number,
        pdcCount: Number,
        datedCount: Number,
        depositedCount: Number,
        bouncedCount: Number,
        replacementCount: Number,
    },
    data() {
        return {
            currentTime: "",
        }
    },
    methods: {
        showTime() {
            var date = new Date();
            var h = date.getHours(); // 0 - 23
            var m = date.getMinutes(); // 0 - 59
            var s = date.getSeconds(); // 0 - 59
            var session = "AM";

            if (h == 0) {
                h = 12;
            }

            if (h > 12) {
                h = h - 12;
                session = "PM";
            }

            h = h < 10 ? "0" + h : h;
            m = m < 10 ? "0" + m : m;
            s = s < 10 ? "0" + s : s;

            var time = h + ":" + m + ":" + s + " " + session;
            this.currentTime = time;

            setTimeout(this.showTime, 1000);
        },
    },
    mounted() {
        this.showTime()
        
    }
}
</script>

<style scoped>
.clock {
    color: #707070;
    font-size: 25px;
    font-family: "Chakra Petch", sans-serif;
    font-weight: 600;
    font-style: normal;
    letter-spacing: 4px;
}

.page {
    position: relative;
    justify-content: center;
    align-items: center;
    box-sizing: border-box;
    max-width: 300px;
    font-family: cursive;
    font-size: 20px;
    height: 200px;
    border-radius: 10px;
    box-shadow: rgba(50, 50, 105, 0.15) 0px 2px 5px 0px, rgba(0, 0, 0, 0.05) 0px 1px 1px 0px;
    background: #fff;
    background-image: linear-gradient(#f5f5f0 1.1rem, #ccc 1.2rem);
    background-size: 100% 1.2rem;
    line-height: 1.2rem;
    padding: 1.4rem 0.5rem 0.3rem 4.5rem;
}

.page::before,
.page::after {
    position: absolute;
    content: "";
    bottom: 10px;
    width: 40%;
    height: 10px;
    box-shadow: 0 5px 14px rgba(0, 0, 0, 0.7);
    z-index: -1;
    transition: all 0.3s ease;
}

.page::before {
    left: 15px;
    transform: skew(-5deg) rotate(-5deg);
}

.page::after {
    right: 15px;
    transform: skew(5deg) rotate(5deg);
}

.page:hover::before,
.page:hover::after {
    box-shadow: 0 2px 14px rgba(0, 0, 0, 0.4);
}

.margin {
    position: absolute;
    border-left: 1px solid #d88;
    height: 100%;
    left: 3.3rem;
    top: 0;
}

/* .page p {
    margin: 0;
    text-indent: 1rem;
    padding-bottom: 1.2rem;
    color: black;
    line-height: 20px;
} */
</style>
