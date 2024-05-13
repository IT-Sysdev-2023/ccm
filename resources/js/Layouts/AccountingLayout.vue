<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div  style="width: 95%; margin: 0 auto;">
        <div class="w-full text-gray-700 bg">
            <div class="flex flex-col  px-8 mx-auto md:items-center md:justify-between md:flex-row">
                <div class="flex flex-row items-center justify-between py-6">
                    <div class="relative md:mt-8">
                        <img class="relative z-50" src="/Logo/treasury.png" alt="">
                        <!-- <a href="#"
                            class="text-lg relative z-50 font-bold tracking-widest text-gray-900 rounded-lg focus:outline-none focus:shadow-outline">Skilline</a> -->
                        <svg class="h-11 z-40 absolute -top-2 -left-3" viewBox="0 0 79 79" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M35.2574 2.24264C37.6005 -0.100501 41.3995 -0.100505 43.7426 2.24264L76.7574 35.2574C79.1005 37.6005 79.1005 41.3995 76.7574 43.7426L43.7426 76.7574C41.3995 79.1005 37.6005 79.1005 35.2574 76.7574L2.24264 43.7426C-0.100501 41.3995 -0.100505 37.6005 2.24264 35.2574L35.2574 2.24264Z"
                                fill="#65DAFF" />
                        </svg>
                    </div>
                    <button class="rounded-lg md:hidden focus:outline-none focus:shadow-outline">
                        <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                            <path x-show="!open" fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z"
                                clip-rule="evenodd"></path>
                            <path x-show="open" fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <nav
                    class="h-0 md:h-auto flex flex-col flex-grow md:items-center pb-4 md:pb-0 md:flex md:justify-end md:flex-row origin-top duration-300 scale-y-0">
                    <Link
                        class=" rounded px-4 py-2 mt-2 text-sm bg-transparent md:mt-8 md:ml-4 text-black focus:outline-none focus:shadow-outline"
                        :href="route('accounting.dashboard')" :class="{
                            'bg-nav':
                                route().current('accounting.dashboard'),
                        }">Dashboard</Link>
                    <Link class="px-4 py-2 mt-2 text-sm bg-transparent md:mt-8 md:ml-4 text-black rounded "
                         :href="route('reports.accounting')" :class="{
                            'bg-nav':
                                route().current('reports.accounting') ||
                                route().current('datedpcchecks.accounting') ||
                                route().current('deposited.reports.accounting') ||
                                route().current('bounce.checks.accounting')
                                ,
                        }">Reports</Link>
                    <Link
                        class="rounded px-4 py-2 mt-2 text-sm bg-transparent md:mt-8 md:ml-4 text-black focus:outline-none focus:shadow-outline"
                        href="#">About Us</Link>

                </nav>
            </div>
        </div>
        <div class="bg-cream">
            <div class="">

                <div>
                    <slot />
                </div>

            </div>
            <div class="text-white -mt-14 sm:-mt-24 lg:-mt-36">
                <svg class="xl:h-40 xl:w-full" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path
                        d="M600,112.77C268.63,112.77,0,65.52,0,7.23V120H1200V7.23C1200,65.52,931.37,112.77,600,112.77Z"
                        fill="currentColor"></path>
                </svg>
                <div class="bg-white w-full h-20 -mt-px">
                </div>
            </div>
        </div>

    </div>
</template>
<style scoped>
.bg-cream {
    background-color: white;
}

/*font*/
body {
    font-family: 'Poppins', sans-serif;
}

.bg-nav {
    border-bottom: 4px solid #F48C06;
    color: #F48C06;
}

.bg-yellow-500 {
    background-color: #F48C06;
}

.text-yellow-500 {
    color: #F48C06;
}

.floating {
    animation-name: floating;
    animation-duration: 3s;
    animation-iteration-count: infinite;
    animation-timing-function: ease-in-out;
}

@keyframes floating {
    0% {
        transform: translate(0, 0px);
    }

    50% {
        transform: translate(0, 8px);
    }

    100% {
        transform: translate(0, -0px);
    }
}

.floating-4 {
    animation-name: floating;
    animation-duration: 4s;
    animation-iteration-count: infinite;
    animation-timing-function: ease-in-out;
}

@keyframes floating-4 {
    0% {
        transform: translate(0, 0px);
    }

    50% {
        transform: translate(0, 8px);
    }

    100% {
        transform: translate(0, -0px);
    }
}

.text-darken {
    color: #2F327D;
}
</style>
