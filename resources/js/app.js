import './bootstrap';
import '../css/app.css';


import { createApp, h } from 'vue';
import ws from './utils/ws'
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import Antd from 'ant-design-vue';
import ToastPlugin from 'vue-toast-notification';
import VueApexCharts from "vue3-apexcharts";
import { createPinia } from 'pinia';
import { setTwoToneColor } from '@ant-design/icons-vue'

const primaryColor = '#4040c8'

setTwoToneColor(primaryColor)


const appName = import.meta.env.APP_NAME || 'Check Clearing And Monitoring System';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
   const app = createApp({ render: () => h(App, props) });

   app.use(plugin);
   app.use(ZiggyVue);
   app.use(Antd);
   app.use(ToastPlugin)
   app.use(createPinia())
   app.use(VueApexCharts);

   // Adding global property $ws
   app.config.globalProperties.primaryColor = primaryColor
   app.config.globalProperties.$ws = ws;

   app.mount(el);
},
    progress: {
        color: primaryColor,
        showSpinner: true
    },
});
