import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { createPinia } from 'pinia';
import '../css/app.css';
import './bootstrap';
/**
 * THIS BIT!!!!!
 */
import { OktaAuth } from '@okta/okta-auth-js'
import OktaVue, { LoginCallback } from '@okta/okta-vue';

import VueGoogleMaps from 'vue-google-maps-community-fork';

// AND THIS!!!!!!!
const oktaAuth = new OktaAuth({
    issuer: 'https://portal-test.edpass.sa.edu.au',
    clientId: '0oa1xyje6zV6eKawK3l7',
    redirectUri: 'http://localhost:8000/dashboard',
    scopes: ['openid', 'profile', 'email'],
    tokenManager: {
        storage: 'localStorage'
    }
});

// const oktaAuth = new OktaAuth({
//     issuer: 'https://dev-75424864.okta.com/oauth2/default',
//     clientId: '0oa612he4hleIM5555d7',
//     redirectUri: 'http://localhost:8000/dashboard',
//     scopes: ['openid', 'profile', 'email'],
//     tokenManager: {
//         storage: 'localStorage'
//     }
// });

console.log(window.location.origin);


const pinia = createPinia();
createApp(App)
    .use(pinia)
    .use(router)
    // AND THIS!!!!!!!
    .use(OktaVue, { oktaAuth })
    .use(VueGoogleMaps, {
        load: {
          key: import.meta.env.VITE_GOOGLE_MAPS_API_KEY,
        },
      })
    .mount('#app');
