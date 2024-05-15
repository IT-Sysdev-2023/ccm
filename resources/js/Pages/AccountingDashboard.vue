<script setup>
import AccountingLayout from '@/Layouts/AccountingLayout.vue';
</script>

<template>

    <Head title="Dashboard" />

    <AccountingLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">this is accounting Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-row :gutter="[16, 16]" class="mt-4">
                    <a-col :span="8">
                        <div class="parent">
                            <div class="card">
                                <div class="content-box">
                                    <span class="card-title">Cheques</span>
                                    <p class="card-content">
                                        This is the data of the Cheques
                                    </p>
                                </div>
                                <div class="date-box">
                                    <span class="month">Count</span>
                                    <span class="date">{{ checkCount.toLocaleString() }}</span>
                                </div>
                            </div>
                        </div>
                    </a-col>
                    <a-col :span="8">
                        <div class="parent">
                            <div class="card">
                                <div class="content-box">
                                    <span class="card-title">Post Dated Cheques</span>
                                    <p class="card-content">
                                        This is the data of the Cheques
                                    </p>

                                </div>
                                <div class="date-box">
                                    <span class="month">Count</span>
                                    <span class="date">{{ pdcCount.toLocaleString() }}</span>
                                </div>
                            </div>
                        </div>
                    </a-col>
                    <a-col :span="8">
                        <div class="parent">
                            <div class="card">
                                <div class="content-box">
                                    <span class="card-title">Dated Cheques</span>
                                    <p class="card-content">
                                        This is the data of the Cheques
                                    </p>

                                </div>
                                <div class="date-box">
                                    <span class="month">Count</span>
                                    <span class="date">{{ datedCount.toLocaleString() }}</span>
                                </div>
                            </div>
                        </div>

                    </a-col>
                    <a-col :span="8">
                        <div class="parent">
                            <div class="card">
                                <div class="content-box">
                                    <span class="card-title">Deposited Cheques</span>
                                    <p class="card-content">
                                        This is the data of the Cheques
                                    </p>

                                </div>
                                <div class="date-box">
                                    <span class="month">Count</span>
                                    <span class="date">{{ depositedCount.toLocaleString() }}</span>
                                </div>
                            </div>
                        </div>

                    </a-col>
                    <a-col :span="8">
                        <div class="parent">
                            <div class="card">
                                <div class="content-box">
                                    <span class="card-title">Bounce Cheques</span>
                                    <p class="card-content">
                                        This is the data of the Cheques
                                    </p>

                                </div>
                                <div class="date-box">
                                    <span class="month">Count</span>
                                    <span class="date">{{ bouncedCount.toLocaleString() }}</span>
                                </div>
                            </div>
                        </div>

                    </a-col>
                    <a-col :span="8">
                        <div class="parent">
                            <div class="card">
                                <div class="content-box">
                                    <span class="card-title">Replacement Cheques</span>
                                    <p class="card-content">
                                        This is the data of the Cheques
                                    </p>

                                </div>
                                <div class="date-box">
                                    <span class="month">Count</span>
                                    <span class="date">{{ replacementCount.toLocaleString() }}</span>
                                </div>
                            </div>
                        </div>
                    </a-col>
                </a-row>
            </div>
        </div>
    </AccountingLayout>
</template>

<script>
export default {
    props: {
        checkCount: Number,
        pdcCount: Number,
        datedCount: Number,
        depositedCount: Number,
        bouncedCount: Number,
        replacementCount: Number,
    }
}
</script>
<style scoped>
.parent {
    width: 100%;
    padding: 20px;
    perspective: 1000px;
}

.card {
    padding-top: 50px;
    border-radius: 10px;
    border: 2px solid rgb(201, 201, 201);
    transform-style: preserve-3d;
    background-size: 60px 60px;
    background-position: 0 0, 0 0;
    width: 100%;
    box-shadow: rgba(142, 142, 142, 0.3) 0px 30px 30px -10px;
    transition: all 0.5s ease-in-out;
}

.card:hover {
    background-position: -100px 100px, -100px 100px;
    transform: rotate3d(0.5, 1, 0, 30deg);
}

.content-box {
    background: rgba(4, 193, 250, 0.732);
    /* border-radius: 10px 100px 10px 10px; */
    transition: all 0.5s ease-in-out;
    padding: 60px 25px 25px 25px;
    transform-style: preserve-3d;
}

.content-box .card-title {
    display: inline-block;
    color: white;
    font-size: 25px;
    font-weight: 900;
    transition: all 0.5s ease-in-out;
    transform: translate3d(0px, 0px, 50px);
}

.content-box .card-title:hover {
    transform: translate3d(0px, 0px, 60px);
}

.content-box .card-content {
    margin-top: 10px;
    font-size: 12px;
    /* font-weight: 700; */
    color: rgb(71, 71, 71);
    transition: all 0.5s ease-in-out;
    transform: translate3d(0px, 0px, 30px);
}

.content-box .card-content:hover {
    transform: translate3d(0px, 0px, 60px);
}

.content-box .see-more {
    cursor: pointer;
    margin-top: 1rem;
    display: inline-block;
    font-weight: 900;
    font-size: 9px;
    text-transform: uppercase;
    color: rgb(7, 185, 255);
    /* border-radius: 5px; */
    background: white;
    padding: 0.5rem 0.7rem;
    transition: all 0.5s ease-in-out;
    transform: translate3d(0px, 0px, 20px);
}

.content-box .see-more:hover {
    transform: translate3d(0px, 0px, 60px);
}

.date-box {
    position: absolute;
    top: 30px;
    right: 30px;
    height: 60px;
    width: 150px;
    background: white;
    border: 1px solid rgb(7, 185, 255);
    /* border-radius: 10px; */
    padding: 10px;
    transform: translate3d(0px, 0px, 80px);
    box-shadow: rgba(100, 100, 111, 0.2) 0px 17px 10px -10px;
    letter-spacing: 1px;
}

.date-box span {
    display: block;
    text-align: center;
}

.date-box .month {
    color: rgb(4, 193, 250);
    font-size: 9px;
    font-weight: 700;
}

.date-box .date {
    font-size: 20px;
    font-weight: 900;
    color: rgb(4, 193, 250);
}
</style>
