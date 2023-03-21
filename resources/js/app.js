import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { createPinia } from 'pinia';
import '../css/app.css'
import './bootstrap'

import VueGoogleMaps from 'vue-google-maps-community-fork';

const pinia = createPinia();
createApp(App)
    .use(pinia)
    .use(router)
    .use(VueGoogleMaps, {
        load: {
          key: import.meta.env.VITE_GOOGLE_MAPS_API_KEY,
        },
      })
    .mount('#app');
