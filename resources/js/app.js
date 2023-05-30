import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { createPinia } from 'pinia';
import '../css/app.css';
import '../css/output.css';
import './bootstrap';
import oktaConfig from './constants/oktaConfig';
import OktaVue, { LoginCallback } from '@okta/okta-vue';
import VueGoogleMaps from 'vue-google-maps-community-fork';
import oktaAuth from './constants/oktaAuth';


const pinia = createPinia();
createApp(App)
    .use(pinia)
    .use(router)
    .use(OktaVue, { oktaAuth })
    .use(VueGoogleMaps, {
        load: {
            key: import.meta.env.VITE_GOOGLE_MAPS_API_KEY,
        },
        autobindAllEvents: true,
    })
    .mount('#app');
