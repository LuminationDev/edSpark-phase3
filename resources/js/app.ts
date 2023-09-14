import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { createPinia } from 'pinia';
import '../css/app.css';
import '../css/output.css';
import './bootstrap';
import VueGoogleMaps from 'vue-google-maps-community-fork';

import { setupCalendar } from 'v-calendar';


const pinia = createPinia();
// const authStore = useAuthStore(pinia);

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
    .mount('#app');
