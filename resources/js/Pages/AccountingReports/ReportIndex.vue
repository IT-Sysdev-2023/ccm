<script setup>
import AccountingLayout from '@/Layouts/AccountingLayout.vue';
</script>

<template>

    <Head title="Report Index " />

    <AccountingLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">this is accounting Dashboard</h2>
        </template>

        <div class="py-5">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="p-10">
                    <a-row :gutter="[16, 16]">
                        <a-col :span="6" class="flex justify-center">
                            <div class="card" @click="() => $inertia.get(route('datedpcchecks.accounting'))">
                                <div class="icon">
                                    <svg viewBox="0 0 1024 1024" class="icon" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M242.3 743.4h603.4c27.8 0 50.3-22.5 50.3-50.3V192H192v501.1c0 27.8 22.5 50.3 50.3 50.3z"
                                                fill="#FFEA00"></path>
                                            <path
                                                d="M178.3 807.4h603.4c27.8 0 50.3-22.5 50.3-50.3V256H128v501.1c0 27.8 22.5 50.3 50.3 50.3z"
                                                fill="#FFFF8D"></path>
                                            <path
                                                d="M960 515v384c0 35.3-28.7 64-64 64H128c-35.3 0-64-28.7-64-64V383.8c0-35.3 28.7-64 64-64h344.1c24.5 0 46.8 13.9 57.5 35.9l46.5 95.3H896c35.3 0 64 28.7 64 64z"
                                                fill="#3D5AFE"></path>
                                            <path
                                                d="M704 512c0-20.7-1.4-41.1-4.1-61H576.1l-46.5-95.3c-10.7-22-33.1-35.9-57.5-35.9H128c-35.3 0-64 28.7-64 64V899c0 6.7 1 13.2 3 19.3C124.4 945 188.5 960 256 960c247.4 0 448-200.6 448-448z"
                                                fill="#536DFE"></path>
                                        </g>
                                    </svg>
                                </div>
                                <p class="title">Dated/PDC
                                    Reports</p>
                                <p class="text">Click this to go to tables</p>
                            </div>

                        </a-col>
                        <a-col :span="6" class="flex justify-center">
                            <div class="card" @click="() => $inertia.get(route('deposited.reports.accounting'))">
                                <div class="icon">
                                    <svg viewBox="0 0 1024 1024" class="icon" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M242.3 743.4h603.4c27.8 0 50.3-22.5 50.3-50.3V192H192v501.1c0 27.8 22.5 50.3 50.3 50.3z"
                                                fill="#FFEA00"></path>
                                            <path
                                                d="M178.3 807.4h603.4c27.8 0 50.3-22.5 50.3-50.3V256H128v501.1c0 27.8 22.5 50.3 50.3 50.3z"
                                                fill="#FFFF8D"></path>
                                            <path
                                                d="M960 515v384c0 35.3-28.7 64-64 64H128c-35.3 0-64-28.7-64-64V383.8c0-35.3 28.7-64 64-64h344.1c24.5 0 46.8 13.9 57.5 35.9l46.5 95.3H896c35.3 0 64 28.7 64 64z"
                                                fill="#3D5AFE"></path>
                                            <path
                                                d="M704 512c0-20.7-1.4-41.1-4.1-61H576.1l-46.5-95.3c-10.7-22-33.1-35.9-57.5-35.9H128c-35.3 0-64 28.7-64 64V899c0 6.7 1 13.2 3 19.3C124.4 945 188.5 960 256 960c247.4 0 448-200.6 448-448z"
                                                fill="#536DFE"></path>
                                        </g>
                                    </svg>
                                </div>
                                <p class="title">Deposited Reports</p>
                                <p class="text">Click this to go to tables</p>
                            </div>
                        </a-col>
                        <a-col :span="6" class="flex justify-center">
                            <div class="card" @click="() => $inertia.get(route('bounce.checks.accounting'))">
                                <div class="icon">
                                    <svg viewBox="0 0 1024 1024" class="icon" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M242.3 743.4h603.4c27.8 0 50.3-22.5 50.3-50.3V192H192v501.1c0 27.8 22.5 50.3 50.3 50.3z"
                                                fill="#FFEA00"></path>
                                            <path
                                                d="M178.3 807.4h603.4c27.8 0 50.3-22.5 50.3-50.3V256H128v501.1c0 27.8 22.5 50.3 50.3 50.3z"
                                                fill="#FFFF8D"></path>
                                            <path
                                                d="M960 515v384c0 35.3-28.7 64-64 64H128c-35.3 0-64-28.7-64-64V383.8c0-35.3 28.7-64 64-64h344.1c24.5 0 46.8 13.9 57.5 35.9l46.5 95.3H896c35.3 0 64 28.7 64 64z"
                                                fill="#3D5AFE"></path>
                                            <path
                                                d="M704 512c0-20.7-1.4-41.1-4.1-61H576.1l-46.5-95.3c-10.7-22-33.1-35.9-57.5-35.9H128c-35.3 0-64 28.7-64 64V899c0 6.7 1 13.2 3 19.3C124.4 945 188.5 960 256 960c247.4 0 448-200.6 448-448z"
                                                fill="#536DFE"></path>
                                        </g>
                                    </svg>
                                </div>
                                <p class="title">Bounce Reports</p>
                                <p class="text">Click this to go to tables</p>
                            </div>
                        </a-col>
                        <a-col :span="6" class="flex justify-center">
                            <div class="card" @click="() => $inertia.get(route('bounce.checks.accounting'))">
                                <div class="icon">
                                    <svg viewBox="0 0 1024 1024" class="icon" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M242.3 743.4h603.4c27.8 0 50.3-22.5 50.3-50.3V192H192v501.1c0 27.8 22.5 50.3 50.3 50.3z"
                                                fill="#FFEA00"></path>
                                            <path
                                                d="M178.3 807.4h603.4c27.8 0 50.3-22.5 50.3-50.3V256H128v501.1c0 27.8 22.5 50.3 50.3 50.3z"
                                                fill="#FFFF8D"></path>
                                            <path
                                                d="M960 515v384c0 35.3-28.7 64-64 64H128c-35.3 0-64-28.7-64-64V383.8c0-35.3 28.7-64 64-64h344.1c24.5 0 46.8 13.9 57.5 35.9l46.5 95.3H896c35.3 0 64 28.7 64 64z"
                                                fill="#3D5AFE"></path>
                                            <path
                                                d="M704 512c0-20.7-1.4-41.1-4.1-61H576.1l-46.5-95.3c-10.7-22-33.1-35.9-57.5-35.9H128c-35.3 0-64 28.7-64 64V899c0 6.7 1 13.2 3 19.3C124.4 945 188.5 960 256 960c247.4 0 448-200.6 448-448z"
                                                fill="#536DFE"></path>
                                        </g>
                                    </svg>
                                </div>
                                <p class="title">Redeem PDC Reports</p>
                                <p class="text">Click this to go to tables</p>
                            </div>
                        </a-col>


                    </a-row>
                    <div class="mb-10 mt-10">
                        <h4 class="text-center">
                            Welcome!. Please select a report page. click the cards to proceed
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </AccountingLayout>
</template>

<style scoped>
.card {
    width: 100%;
    height: 250px;
    background-color: #ffffffc4;
    margin: 10px;
    border-radius: 2rem;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.24);
    border: 2px solid rgba(7, 7, 7, 0.12);
    font-size: 16px;
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
}

.icon {
    margin: 0 auto;
    width: 100%;
    height: 80px;
    max-width: 80px;
    background: linear-gradient(90deg, #7eaaff 0%, #ff48fb 40%, rgba(0, 0, 0, 0.28) 60%);
    border-radius: 1rem;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: all 0.8s ease;
    background-position: 0px;
    background-size: 200px;
}

.icon svg {
    fill: white;
}

.card .title {
    width: 100%;
    margin: 0;
    text-align: center;
    margin-top: 30px;
    color: black;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 4px;
}

.card .text {
    width: 80%;
    margin: 0 auto;
    font-size: 13px;
    text-align: center;
    margin-top: 20px;
    color: black;
    font-weight: 200;
    letter-spacing: 2px;
    opacity: 0;
    max-height: 0;
    transition: all 0.3s ease;
}

.card:hover {
    height: 270px;
}

.card:hover .text {
    transition: all 0.3s ease;
    opacity: 1;
    max-height: 40px;
}


</style>
