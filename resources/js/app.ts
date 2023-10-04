import '../css/app.css';
import '../css/output.css';
import './bootstrap';

import {createPinia} from 'pinia';
import {setupCalendar} from 'v-calendar';
import {createApp} from 'vue';
import VueGoogleMaps from 'vue-google-maps-community-fork';
import VueTippy from 'vue-tippy'

import App from './App.vue';
import router from './router';


const pinia = createPinia();
// const authStore = useAuthStore(pinia);
const tippyOptions : any = {
    directive: 'tippy',
    component: 'tippy',
    defaultProps: { placement: 'top' }

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
    .use(VueTippy,tippyOptions
        )
    .mount('#app');
