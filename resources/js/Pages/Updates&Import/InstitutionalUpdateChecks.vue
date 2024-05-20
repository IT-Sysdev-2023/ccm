<template>

    <Head title="Dashboard" />

        <div class="py-5">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <a-row :gutter="[16, 16]">
                    <a-col :span="5" class="mt-2">

                        <a-button
                            style="width: 100%; padding:  15px 0 35px 0; border-right: none; border-radius: 10px 0 0 0;"
                            class="mb-5" @click="showImportInstutional" :class="{ active: isActive === 'import' }">
                            <ImportOutlined />
                            Import Institutional
                        </a-button>
                        <a-button
                            style="width: 100%  ; padding:  15px 0 35px 0; border-right: none; border-radius: 0 0  0 10px;"
                            @click="showUpdateddatabase" :class="{ active: isActive === 'update' }">
                            <UpSquareOutlined />
                            Update Atp Database
                        </a-button>

                    </a-col>
                    <a-col :span="19">

                        <div v-if="showImport">
                            <a-row :gutter="[16, 16]">
                                <a-col :span="6" class="flex justify-end">
                                    <img src="../../../../public/Logo/ccmpbng.png" alt="" style="
                                                height: 130px;
                                                display: flex;
                                                justify-content: center;
                                            " />
                                </a-col>
                                <a-col :span="18">
                                    <a-card v-if="isNotProgressShowing">
                                        Hello There!
                                        <strong>{{
                                            $page.props.auth.user.name
                                        }}
                                        </strong>
                                        , To proceed the import instutional
                                        checks. Please click the "
                                        <strong>Start Importing</strong> "
                                    </a-card>
                                    <a-card>
                                        <div v-if="isProgressShowing" style="font-size: 14px">
                                            <div>
                                                <div v-if="isProgressShowing">
                                                    <div class="flex justify-between">
                                                        <div>
                                                            <p>
                                                                {{
                                                                    progressBar.message
                                                                }}
                                                                {{
                                                                    progressBar.currentRow
                                                                }}
                                                                to
                                                                {{
                                                                    progressBar.totalRows
                                                                }}
                                                            </p>
                                                        </div>

                                                        <div></div>
                                                    </div>
                                                    <a-progress style="
                                                                width: 98%;
                                                                margin: 0 auto;
                                                            " :stroke-color="{
                                                                from: '#108ee9',
                                                                to: '#87d068',
                                                            }" :percent="progressBar.percentage
                                                                " status="active" />
                                                </div>
                                            </div>
                                        </div>
                                    </a-card>
                                </a-col>
                            </a-row>
                            <div class="loader mt-0">
                                <div class="book-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 126 75"
                                        class="book">
                                        <rect stroke-width="5" stroke="#e05452" rx="7.5" height="70" width="121" y="2.5"
                                            x="2.5"></rect>
                                        <line stroke-width="5" stroke="#e05452" y2="75" x2="63.5" x1="63.5"></line>
                                        <path stroke-linecap="round" stroke-width="4" stroke="#c18949" d="M25 20H50">
                                        </path>
                                        <path stroke-linecap="round" stroke-width="4" stroke="#c18949" d="M101 20H76">
                                        </path>
                                        <path stroke-linecap="round" stroke-width="4" stroke="#c18949" d="M16 30L50 30">
                                        </path>
                                        <path stroke-linecap="round" stroke-width="4" stroke="#c18949"
                                            d="M110 30L76 30">
                                        </path>
                                    </svg>

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#ffffff74" viewBox="0 0 65 75"
                                        class="book-page">
                                        <path stroke-linecap="round" stroke-width="4" stroke="#c18949" d="M40 20H15">
                                        </path>
                                        <path stroke-linecap="round" stroke-width="4" stroke="#c18949" d="M49 30L15 30">
                                        </path>
                                        <path stroke-width="5" stroke="#e05452"
                                            d="M2.5 2.5H55C59.1421 2.5 62.5 5.85786 62.5 10V65C62.5 69.1421 59.1421 72.5 55 72.5H2.5V2.5Z">
                                        </path>
                                    </svg>
                                </div>
                                <a-result status="" title="Import institutional checks!"
                                    sub-title="Click  the start button, to import the institutional text-file.">
                                    <div class="text-center">
                                        <a-tag color="green" v-if="count">
                                            There are {{ count }} text file that can be
                                            importable
                                        </a-tag>
                                        <a-tag color="red" v-else>
                                            Theres no institutional textfile
                                            for today
                                        </a-tag>
                                    </div>
                                    <template #extra>
                                        <a-button :loading="isLoading" block type="primary" :disabled="!count"
                                            @click="startImporting">
                                            <template #icon>
                                                <UploadOutlined />
                                            </template>
                                            {{
                                                isLoading
                                                    ? "importing text file in progress please wait..."
                                                    : "Start Importing institutional text file"
                                            }}
                                        </a-button>
                                    </template>
                                </a-result>
                            </div>
                        </div>
                        <div v-else-if="showUpdate">
                            <a-row :gutter="[16, 16]">
                                <a-col :span="6" class="flex justify-end">
                                    <img src="../../../../public/Logo/ccmpbng.png" alt="" style="
                                                height: 130px;
                                                display: flex;
                                                justify-content: center;
                                            " />
                                </a-col>
                                <a-col :span="18">
                                    <a-card v-if="isNotProgressShowing">
                                        Hello There!
                                        <strong>{{
                                            $page.props.auth.user.name
                                        }} </strong>, To proceed the atp update
                                        database. Please click the "
                                        <strong>Start Updating</strong> "
                                    </a-card>
                                    <a-card>
                                        <div v-if="isProgressShowing" style="font-size: 14px">
                                            <div>
                                                <div v-if="isProgressShowing">
                                                    <div class="flex justify-between">
                                                        <div>
                                                            <p>
                                                                {{
                                                                    progressBar.message
                                                                }}
                                                                {{
                                                                    progressBar.currentRow
                                                                }}
                                                                to
                                                                {{
                                                                    progressBar.totalRows
                                                                }}

                                                            </p>
                                                        </div>

                                                        <div></div>

                                                    </div>

                                                    <a-progress style="
                                                                width: 98%;
                                                                margin: 0 auto;
                                                            " :stroke-color="{
                                                                from: '#108ee9',
                                                                to: '#87d068',
                                                            }" :percent="progressBar.percentage
                                                                " status="active" />
                                                </div>
                                            </div>
                                            <div>

                                                <div class="flex justify-between">
                                                    <div>
                                                        <p>
                                                            {{
                                                                progressBarAll.message
                                                            }}

                                                            {{
                                                                progressBarAll.currentRow
                                                            }}
                                                            to
                                                            {{
                                                                progressBarAll.totalRows
                                                            }}


                                                        </p>
                                                    </div>

                                                    <div></div>

                                                </div>


                                                <a-progress style="
                                                                width: 98%;
                                                                margin: 0 auto;
                                                            " :stroke-color="{
                                                                from: '#108ee9',
                                                                to: '#87d068',
                                                            }" :percent="progressBarAll.percentage
                                                                " status="active" />


                                            </div>
                                        </div>
                                    </a-card>

                                </a-col>
                            </a-row>

                            <div class="loader mt-0">
                                <div class="book-wrapper">
                                    <div class="gearbox">
                                        <div class="overlay"></div>
                                        <div class="gear one">
                                            <div class="gear-inner">
                                                <div class="bar"></div>
                                                <div class="bar"></div>
                                                <div class="bar"></div>
                                            </div>
                                        </div>
                                        <div class="gear two">
                                            <div class="gear-inner">
                                                <div class="bar"></div>
                                                <div class="bar"></div>
                                                <div class="bar"></div>
                                            </div>
                                        </div>
                                        <div class="gear three">
                                            <div class="gear-inner">
                                                <div class="bar"></div>
                                                <div class="bar"></div>
                                                <div class="bar"></div>
                                            </div>
                                        </div>
                                        <div class="gear four large">
                                            <div class="gear-inner">
                                                <div class="bar"></div>
                                                <div class="bar"></div>
                                                <div class="bar"></div>
                                                <div class="bar"></div>
                                                <div class="bar"></div>
                                                <div class="bar"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a-result status="" title="Update Atp Database!"
                                    sub-title="Click  the start button, to update the atp database.">
                                    <div class="text-center">
                                        <a-tag color="green" v-if="countAtp">
                                            There are {{ countAtp }} needs to be update
                                        </a-tag>
                                        <a-tag color="red" v-else>
                                            Atp database is already up to date
                                        </a-tag>
                                    </div>
                                    <template #extra>
                                        <a-button :loading="isLoading" @click="startUpdatingAtpDatabase" type="primary"
                                            block :disabled="countAtp == 0">
                                            <template #icon>
                                                <UploadOutlined />
                                            </template>
                                            {{ isLoading ? 'updating database on progress...' :
                                                ' Start Updating atp ' }}
                                        </a-button>
                                    </template>
                                </a-result>
                            </div>
                        </div>
                        <div class="mt-0 loader" v-else>
                            <img src="../../../../public/Logo/ccmpbng.png" alt="" style="
                                        height: 350px;
                                        display: flex;
                                        justify-content: center;
                                    " />
                            <a-card>
                                Hi There!
                                <strong>{{ $page.props.auth.user.name }}
                                </strong>
                                I'm
                                <strong>Ccmbot</strong>
                                We're thrilled to have you here. Explore our
                                platform to discover a world of information,
                                services, and resources tailored just for
                                you. Whether you're seeking solutions,
                                entertainment, or simply looking to learn
                                something new, we've got you covered. Let's
                                embark on this journey together! <br />

                                <br />
                                Please click the button "<strong>Import institutional checks</strong>" &
                                "<strong>Update atp
                                    database</strong>"
                                to proceed the in the page
                            </a-card>
                        </div>

                    </a-col>
                </a-row>
            </div>
        </div>
        <div class="" style="display: flex; justify-content: start" @click="getRandomJoke()">
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
</template>

<script>
import TreasuryLayout from "@/Layouts/TreasuryLayout.vue";
export default {
    layout: TreasuryLayout,
    data() {
        return {
            placement: 'top',
            jokes: "",
            showUpdate: false,
            showImport: false,
            isActive: null,
            progressBar: {
                currentRow: 0,
                percentage: 0,
                message: "",
                totalRows: 0,
            },
            progressBarAll: {
                currentRow: 0,
                percentage: 0,
                message: "",
                totalRows: 0,
            },
            isProgressShowing: false,
            isNotProgressShowing: true,
            isLoading: false,
        };
    },
    methods: {
        async getRandomJoke() {
            try {
                const response = await fetch(
                    "https://official-joke-api.appspot.com/random_joke/"
                );
                const data = await response.json();
                this.jokes = data;
            } catch (error) {
                console.error("Error fetching joke:", error);
            }
        },
        showImportInstutional() {
            this.showImport = true;
            this.showUpdate = false;
            this.isActive = "import";
        },
        showUpdateddatabase() {
            this.showImport = false;
            this.showUpdate = true;
            this.isActive = "update";
        },
        startImporting() {
            this.isProgressShowing = true;
            this.isNotProgressShowing = false;
            this.isLoading = true;

            this.$inertia.get(
                route("start.importing.checks"),
                {},
                {
                    preserveScroll: true,
                    preserveState: true,
                }
            );
        },
        startUpdatingAtpDatabase() {
            this.isProgressShowing = true;
            this.isNotProgressShowing = false;
            this.isLoading = true;

            this.$inertia.get(route('updatingAtp.database'), {},
                {
                    preserveScroll: true,
                    preserveState: true,
                });
        }

    },
    mounted() {
        this.getRandomJoke();

        this.$ws
            .private(`importing-progress.${this.$page.props.auth.user.id}`)
            .listen(".importing-database", (e) => {
                // console.log(e);
                this.progressBar = e;
            });

        this.$ws
            .private(`updating-progress.${this.$page.props.auth.user.id}`)
            .listen(".updating-database", (e) => {
                // console.log(e);
                this.progressBar = e;
            });

        this.$ws
            .private(`updating-progress-all.${this.$page.props.auth.user.id}`)
            .listen(".updating-database-all", (e) => {
                console.log(e);
                this.progressBarAll = e;
            });
    },

    props: {
        qoute_api: Object,
        count: Number,
        countAtp: Number,
    },
};
</script>

<style scoped>
.active {
    border-right: none;
    box-shadow: rgb(104, 106, 109) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
    color: rgb(0, 0, 0);
}

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

@keyframes clockwise {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

@keyframes counter-clockwise {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(-360deg);
    }
}

.gearbox {
    height: 170px;
    width: 280px;
    position: relative;

    border-radius: 6px;
}

.gearbox .overlay {
    background: transparent;
}

.gear {
    position: absolute;
    height: 60px;
    width: 60px;
    box-shadow: 0px -1px 0px 0px #000000, 0px 1px 0px 0px black;
    border-radius: 30px;
}

.gear.large {
    height: 120px;
    width: 120px;
    border-radius: 60px;
}

.gear.large:after {
    height: 96px;
    width: 96px;
    border-radius: 48px;
    margin-left: -48px;
    margin-top: -48px;
}

.gear.one {
    top: 12px;
    left: 10px;
}

.gear.two {
    top: 61px;
    left: 60px;
}

.gear.three {
    top: 110px;
    left: 10px;
}

.gear.four {
    top: 13px;
    left: 128px;
}

.gear:after {
    content: "";
    position: absolute;
    height: 36px;
    width: 36px;
    border-radius: 36px;
    background: #111;
    top: 50%;
    left: 50%;
    margin-left: -18px;
    margin-top: -18px;
    z-index: 3;
    box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.1),
        inset 0px 0px 10px rgba(0, 0, 0, 0.1), inset 0px 2px 0px 0px #090909,
        inset 0px -1px 0px 0px #888888;
}

.gear-inner {
    position: relative;
    height: 100%;
    width: 100%;
    background: #555;
    border-radius: 30px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.large .gear-inner {
    border-radius: 60px;
}

.gear.one .gear-inner {
    animation: counter-clockwise 3s infinite linear;
}

.gear.two .gear-inner {
    animation: clockwise 3s infinite linear;
}

.gear.three .gear-inner {
    animation: counter-clockwise 3s infinite linear;
}

.gear.four .gear-inner {
    animation: counter-clockwise 6s infinite linear;
}

.gear-inner .bar {
    background: #555;
    height: 16px;
    width: 76px;
    position: absolute;
    left: 50%;
    margin-left: -38px;
    top: 50%;
    margin-top: -8px;
    border-radius: 2px;
    border-left: 1px solid rgba(255, 255, 255, 0.1);
    border-right: 1px solid rgba(255, 255, 255, 0.1);
}

.large .gear-inner .bar {
    margin-left: -68px;
    width: 136px;
}

.gear-inner .bar:nth-child(2) {
    transform: rotate(60deg);
}

.gear-inner .bar:nth-child(3) {
    transform: rotate(120deg);
}

.gear-inner .bar:nth-child(4) {
    transform: rotate(90deg);
}

.gear-inner .bar:nth-child(5) {
    transform: rotate(30deg);
}

.gear-inner .bar:nth-child(6) {
    transform: rotate(150deg);
}
</style>
