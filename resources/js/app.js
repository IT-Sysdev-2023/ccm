import './bootstrap';
import '../css/app.css';


import { createApp, h } from 'vue';
import ws from './utils/ws'
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import Antd from 'ant-design-vue';
import ToastPlugin from 'vue-toast-notification';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
   const app = createApp({ render: () => h(App, props) });
   
   app.use(plugin);
   app.use(ZiggyVue);
   app.use(Antd);
   app.use(ToastPlugin);

   // Adding global property $ws
   app.config.globalProperties.$ws = ws;

   app.mount(el);
},
    progress: {
        color: '#3f8fff',
    },
});
