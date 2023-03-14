import { defineStore } from "pinia";
import { useSessionStorage } from "@vueuse/core";
import axios from "axios";

export const useUserStore = defineStore('user', {
    state: () => ({
        currentUser: useSessionStorage('currentUser', {}),
    }),

    getters: {
        getUser() {
            return this.currentUser;
        }
    },

    actions: {
        async loadCurrentUser() {
            /**
             * Temporry user ID (Jake M)
             */
            const userId = 1
            await axios.get(`http://localhost:8000/api/fetchUser/${userId}`).then(response => {
                console.log(response);
            }).catch(error => {
                console.log('There was a problem retrieving that user');
                console.error(error);
            })
        }
    }
})
