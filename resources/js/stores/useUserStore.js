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
            /** **/
            await axios.get(`http://localhost:8000/api/fetchUser/${userId}`).then(response => {
                console.log(response.data);
                this.currentUser = response.data;
            }).catch(error => {
                console.log('There was a problem retrieving that user');
                console.error(error);
            })
        },

        async createUser(user) {
            /**
             * Temporry user ID (Jake M)
             */
            const userId = 1
            /** **/

            let data = {
                data: {
                    full_name: user.name,
                    email: user.email,
                    role: user.role
                },
                metaData: {
                    yearLevels: user.yearLevels,
                    interest: user.interests,
                    subjects: user.subjects
                }
            };

            data = JSON.stringify(data);

            console.log(data);

            await axios({
                method: 'POST',
                url: 'http://localhost:8000/api/createUse',
                data: JSON.stringify({
                    data: {
                        full_name: user.name,
                        email: user.email,
                        role: user.role
                    },
                    metaData: {
                        yearLevels: user.yearLevels,
                        interest: user.interests,
                        subjects: user.subjects
                    }
                })
            }).then(response => {
                console.log(response);
            }).catch(error => {
                console.log('There was a problem updating your info');
                console.error(error);
            })
        }
    }
})
