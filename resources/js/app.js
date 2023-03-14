import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { createPinia } from 'pinia';
import '../css/app.css'
import './bootstrap'

import VueGoogleMaps from 'vue-google-maps-community-fork';

// TODO: include pinia for state amangement
const pinia = createPinia();

createApp(App)
    .use(pinia)
    .use(router)
    .use(VueGoogleMaps, {
        load: {
          key: 'AIzaSyAFbqxGQntzgzfzKFh6bArwU14MJhcV1Wc',
        },
      })
    .mount('#app');
