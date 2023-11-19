import '../css/app.css';
import '../css/output.css';
import '../css/attaches.css';
import '../css/font/font.css'
import './bootstrap';
import 'vue3-toastify/dist/index.css';
import 'tippy.js/dist/tippy.css'

import {library} from '@fortawesome/fontawesome-svg-core'
import {fas} from '@fortawesome/free-solid-svg-icons'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import {createPinia} from 'pinia';
import {setupCalendar} from 'v-calendar';
import {createApp} from 'vue';
import VueDragsScroll from 'vue-dragscroll'
import VueGoogleMaps from 'vue-google-maps-community-fork';
import VueTippy from 'vue-tippy'
import Vue3Toastify, {ToastContainerOptions} from "vue3-toastify";

import IconPickerInput from "@/js/components/bases/IconPickerInput.vue";

import App from './App.vue';
import router from './router';


const pinia = createPinia();
// const authStore = useAuthStore(pinia);
const tippyOptions: any = {
    directive: 'tippy',
    component: 'tippy',
    defaultProps: {placement: 'top', hideOnClick: false},
}

library.add(fas
)
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
    .use(VueTippy, tippyOptions)
    .use(Vue3Toastify, {autoClose: 3000} as ToastContainerOptions)
    .use(VueDragsScroll)
    .component('font-awesome-icon', FontAwesomeIcon)
    .mount('#app');
