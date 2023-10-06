import '../css/app.css';
import '../css/output.css';
import './bootstrap';
import 'vue3-toastify/dist/index.css';

import {createPinia} from 'pinia';
import {setupCalendar} from 'v-calendar';
import {createApp} from 'vue';
import VueGoogleMaps from 'vue-google-maps-community-fork';
import VueTippy from 'vue-tippy'
import Vue3Toastify, {ToastContainerOptions} from "vue3-toastify";

import App from './App.vue';
import router from './router';


const pinia = createPinia();
// const authStore = useAuthStore(pinia);
const tippyOptions: any = {
    directive: 'tippy',
    component: 'tippy',
    defaultProps: {placement: 'top'}

}


createApp(App)
    .use(pinia)
    .use(router)
    .use(VueGoogleMaps, {
        load: {
            key: import.meta.env.VITE_GOOGLE_MAPS_API_KEY,
        },
        autobindAllEvents: true,
    })
    .use(setupCalendar, {})
    .use(VueTippy, tippyOptions
    )
    .use(Vue3Toastify, {autoClose: 5000} as ToastContainerOptions)
    .mount('#app');
