import '../css/attaches.css';
import '../css/font/font.css'
import './bootstrap';
import 'vue3-toastify/dist/index.css';
import 'tippy.js/dist/tippy.css'
import 'tinymce/tinymce'
import 'tinymce/plugins/image';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/link';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/autoresize'
import 'tinymce/plugins/wordcount'
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/charmap';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/code';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/preview';
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/insertdatetime';
import 'tinymce/plugins/media';
import 'tinymce/plugins/help';
import 'tinymce/plugins/table';
import 'tinymce/plugins/importcss';
import 'tinymce/plugins/directionality';
import 'tinymce/plugins/visualchars';
import 'tinymce/plugins/template';
import 'tinymce/plugins/codesample';
import 'tinymce/plugins/pagebreak';
import 'tinymce/plugins/nonbreaking';
import 'tinymce/plugins/emoticons';
import 'tinymce/plugins/autosave';
import 'tinymce/models/dom'
import 'tinymce/themes/silver'
import 'tinymce/icons/default'
import 'tinymce/plugins/emoticons/js/emojis'
import 'tinymce/plugins/emoticons/js/emojiimages'
import 'tinymce/skins/ui/oxide/skin.min.css';
import 'tinymce/skins/content/default/content.min.css';
import '../css/app.css';
import '../css/output.css';

import CKEditor from '@ckeditor/ckeditor5-vue';
import {library} from '@fortawesome/fontawesome-svg-core'
import {fas} from '@fortawesome/free-solid-svg-icons'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import {createPinia} from 'pinia';
import tinymce from 'tinymce';
import {setupCalendar} from 'v-calendar';
import {createApp} from 'vue';
import VueDragsScroll from 'vue-dragscroll'
import VueGoogleMaps from 'vue-google-maps-community-fork';
import VueTippy from 'vue-tippy'
import Vue3Toastify, {ToastContainerOptions} from "vue3-toastify";

import {IMAGE_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

import App from './App.vue';
import router from './router';

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
        content_css: false, skin: false
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
    .use( CKEditor )
    .component('font-awesome-icon', FontAwesomeIcon)
    .mount('#app');
