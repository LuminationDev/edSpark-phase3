import axios from "axios";
import {defineStore} from 'pinia';

import {appURL} from "@/js/constants/serverUrl";

export const useAuthStore = defineStore('auth', {
    state: () => ({
        isAuthenticated: false,
    }),
    getters: {
        getAuthStatus() {
            return this.isAuthenticated
        }
    },
    actions: {
        async checkAuthenticationStatus() {
            return new Promise(async(res, rej) =>{
                try {
                    const response = await axios.get(`${appURL}/auth/check`);
                    this.isAuthenticated = response.data.authenticated;
                    res();
                } catch (error) {
                    this.isAuthenticated = false
                    rej()
                }

            })
            // Make a request to the Laravel backend for authentication check
        },
    },
});
