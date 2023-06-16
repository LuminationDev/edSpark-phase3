import { defineStore } from "pinia";
import axios from "axios";

export const useAuthStore = defineStore('auth', {
    state: () => ({
        isAuthenticated: false,
    }),
    getters: {
        isAuthenticated: (state) => state.isAuthenticated,
    },
    actions: {
        redirectToOkta() {
            window.location = '/login'; //Replace with your laravel route
        },
        async handleOktaCallBack(token) {
            console.log('handle okta call back called')
            console.log("TOKEN", token);
            try {
                await axios.post('/login/callback', { token });
                this.setAuthentication(true);
                // Redirect to the authenticated section of your application
                // e.g., router.push('/dashboard');
            } catch ( error ) {
                console.log(error);
            }
        }
    },
    mutations: {
        setAuthentication(isAuthenticated) {
            this.isAuthenticated = isAuthenticated;
        }
    }
});
