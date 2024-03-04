import '../css/font/font.css'
import './bootstrap';
import 'vue3-toastify/dist/index.css';
import 'tippy.js/dist/tippy.css'
import '../css/output.css';

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

import {IMAGE_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

import App from './App.vue';
import router from './router';
import tinymce from './tinymceSetup'

const pinia = createPinia();
// const authStore = useAuthStore(pinia);
const tippyOptions: any = {
    directive: 'tippy',
    component: 'tippy',
    defaultProps: {placement: 'top', hideOnClick: false},
}

window.addEventListener('DOMContentLoaded', () => {
    tinymce.init({
        selector: 'textarea',
        min_height: 300,
        menubar: false,
        plugins: 'advlist autoresize codesample directionality emoticons fullscreen image link lists media table wordcount',
        toolbar: 'undo redo removeformat |  styles fontsize | bold italic | alignjustify alignleft aligncenter  alignright | numlist bullist | forecolor backcolor | blockquote table hr | image link media codesample emoticons | wordcount',
        images_upload_url: IMAGE_ENDPOINTS.IMAGE.IMAGE_UPLOAD_TINYMCE,
        convert_urls: false,
        toolbar_sticky: true,
        toolbar_sticky_offset: 45,
        image_caption: true,
        image_advtab: true,
        browser_spellcheck : true,
        context_menu : false,
        content_css: false, 
        skin: false
    });

});

library.add(fas)
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
