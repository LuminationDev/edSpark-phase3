import { defineStore } from 'pinia';
import { appURL } from "@/js/constants/serverUrl";

export const useAuthStore = defineStore('auth', {
  state() {
    return {
      isAuthenticated: false,
    };
  },
  actions: {
    async checkAuthenticationStatus() {
      // Make a request to the Laravel backend for authentication check
      try {
        const response = await axios.get(`${appURL}/auth/check`);
        this.isAuthenticated = response.data.authenticated;
      } catch (error) {
        console.log(error);
        // Handle error
      }
    },
  },
});
