import axios from "axios";
import {defineStore} from 'pinia';
import {Ref} from "vue";

import {appURL} from "@/js/constants/serverUrl";
interface AuthState {
    isAuthenticated : Ref<Promise<boolean> | boolean>
}
export const useAuthStore = defineStore('auth', {
    state: (): AuthState => <AuthState>({
        isAuthenticated: false,
    }),
    getters: {
        getAuthStatus() {
            return this.isAuthenticated
        }
    },
    actions: {
        async init(){

        },
        checkAuthenticationStatus() {
            try {
                this.isAuthenticated = axios.get(`${appURL}/auth/check`).then(res => {
                    console.log('AUTHSTORE IS AUTHENTICATED IS FULFILLED ' + res.data.authenticated)
                    this.isAuthenticated = res.data?.authenticated
                })
            } catch (error) {
                this.isAuthenticated = false;
                throw new Error('Failed to check authentication status');
            }
        }
    },
});
