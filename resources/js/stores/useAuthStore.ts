import {useStorage} from "@vueuse/core";
import axios from "axios";
import {defineStore} from 'pinia';
import {Ref} from "vue";

import {appURL} from "@/js/constants/serverUrl";

interface AuthState {
    isAuthenticated: Ref<Promise<boolean> | boolean>
}

export const useAuthStore = defineStore('auth', {
    state: (): AuthState => <AuthState>({
        isAuthenticated: useStorage('edspark-auth', false, sessionStorage),
    }),
    getters: {
        getAuthStatus() {
            return this.isAuthenticated
        }
    },
    actions: {
        async init() {

        },
        async checkAuthenticationStatus() {
            try {
                const result = await axios.get(`${appURL}/auth/check`)
                this.isAuthenticated = result.data.authenticated
                return this.isAuthenticated;

            } catch (error) {
                this.isAuthenticated = false;
                throw new Error('Failed to check authentication status');
            }
        }
    },
});
