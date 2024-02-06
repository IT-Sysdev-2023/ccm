<script setup>
import TreasuryLayout from '@/Layouts/TreasuryLayout.vue';
import { Head } from '@inertiajs/vue3';
import { SmileOutlined, QuestionOutlined, UploadOutlined } from '@ant-design/icons-vue';
const placement = 'top';

const tabPosition = 'right';
</script>

<template>
    <Head title="Dashboard" />

    <TreasuryLayout>
        <template #header>
        </template>

        <div class="py-5">

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <a-tabs v-model:activeKey="activeKey" :tab-position="tabPosition" animated>
                    <a-tab-pane key="1" tab="Import Institutional Checks">
                        <div class="text-xl">
                            <SmileOutlined /> Import Institutional Check
                        </div>
                        <div class="loader mt-10">
                            <div class="book-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 126 75" class="book">
                                    <rect stroke-width="5" stroke="#e05452" rx="7.5" height="70" width="121" y="2.5"
                                        x="2.5"></rect>
                                    <line stroke-width="5" stroke="#e05452" y2="75" x2="63.5" x1="63.5"></line>
                                    <path stroke-linecap="round" stroke-width="4" stroke="#c18949" d="M25 20H50"></path>
                                    <path stroke-linecap="round" stroke-width="4" stroke="#c18949" d="M101 20H76"></path>
                                    <path stroke-linecap="round" stroke-width="4" stroke="#c18949" d="M16 30L50 30"></path>
                                    <path stroke-linecap="round" stroke-width="4" stroke="#c18949" d="M110 30L76 30"></path>
                                </svg>

                                <svg xmlns="http://www.w3.org/2000/svg" fill="#ffffff74" viewBox="0 0 65 75"
                                    class="book-page">
                                    <path stroke-linecap="round" stroke-width="4" stroke="#c18949" d="M40 20H15"></path>
                                    <path stroke-linecap="round" stroke-width="4" stroke="#c18949" d="M49 30L15 30"></path>
                                    <path stroke-width="5" stroke="#e05452"
                                        d="M2.5 2.5H55C59.1421 2.5 62.5 5.85786 62.5 10V65C62.5 69.1421 59.1421 72.5 55 72.5H2.5V2.5Z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <a-result status="" title="Hi There!"
                            sub-title="Click  the start button, to import the institutional text-file.">
                            <template #extra>
                                <a-upload v-model:file-list="fileList" name="file"
                                    action="https://www.mocky.io/v2/5cc8019d300000980a055e76" :headers="headers"
                                    @change="handleChange">
                                    <a-button style="background: #4791d6; color: white !important;">
                                        <UploadOutlined />
                                        Start importing
                                    </a-button>
                                </a-upload>
                            </template>
                        </a-result>

                    </a-tab-pane>
                    <a-tab-pane key="2" tab="Update ATP Database">
                        <div class="text-xl">
                            <SmileOutlined /> Update ATP Database
                        </div>
                        <a-result title="Hi there do you having a great day?" sub-title="Click  the start button, to update database." class="mt-10">
                            <template #icon>
                                <SmileOutlined />
                            </template>
                            <template #extra>
                                <a-upload v-model:file-list="fileList" name="file"
                                    action="https://www.mocky.io/v2/5cc8019d300000980a055e76" :headers="headers"
                                    @change="handleChange">
                                    <a-button style="background: #4791d6; color: white !important;">
                                        <UploadOutlined />
                                        Start Updating
                                    </a-button>
                                </a-upload>
                            </template>
                        </a-result>
                    </a-tab-pane>
                </a-tabs>

            </div>
        </div>
        <div class="" style="display: flex; justify-content: start;" @click="getRandomJoke()">
            <a-tooltip placement="top">
                <template #title>
                    <span>Click this to view the tour guide</span>
                </template>
                <a-float-button type="primary" :style="{
                    right: '100px',
                }">
                    <template #icon>
                        <QuestionOutlined />
                    </template>
                </a-float-button>
            </a-tooltip>

            <a-dropdown :placement="placement" :arrow="{ pointAtCenter: true }">

                <a-float-button type="primary" :style="{
                    right: '150px',
                }">
                    <template #icon>
                        <SmileOutlined />
                    </template>
                </a-float-button>
                <template #overlay>
                    <a-menu>
                        <div class="p-6">
                            <p>
                                <QuestionOutlined /> {{ jokes.setup }}
                            </p>
                            <p class="font-bold">
                                <SmileOutlined /> {{ jokes.punchline }}
                            </p>
                        </div>
                    </a-menu>
                </template>
            </a-dropdown>

        </div>

    </TreasuryLayout>
</template>

<script>
export default {
    data() {
        return {
            jokes: '',
        }
    },
    methods: {
        async getRandomJoke() {
            try {
                const response = await fetch("https://official-joke-api.appspot.com/random_joke/");
                const data = await response.json();
                this.jokes = data;

            } catch (error) {
                console.error("Error fetching joke:", error);
            }
        },
    },
    mounted() {
        this.getRandomJoke();
    },

    props: {
        qoute_api: Array,
    }
}
</script>

<style scoped>
.loader {
    display: flex;
    align-items: center;
    justify-content: center;
}

.book-wrapper {
    width: 300px;
    height: fit-content;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    position: relative;
}

.book {
    width: 100%;
    height: auto;
    filter: drop-shadow(10px 10px 5px rgba(0, 0, 0, 0.137));
}

.book-wrapper .book-page {
    width: 50%;
    height: auto;
    position: absolute;
    animation: paging 1s linear infinite;
    transform-origin: left;
}

@keyframes paging {
    0% {
        transform: rotateY(0deg) skewY(0deg);
    }

    50% {
        transform: rotateY(90deg) skewY(-20deg);
    }

    100% {
        transform: rotateY(180deg) skewY(0deg);
    }
}
</style>