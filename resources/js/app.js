import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import '../css/app.css'
import './bootstrap'

// TODO: include pinia for state amangement

createApp(App).use(router).mount('#app');
