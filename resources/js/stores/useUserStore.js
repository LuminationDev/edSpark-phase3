import { defineStore } from "pinia";
import { useSessionStorage } from "@vueuse/core";
import axios from "axios";

export const useUserStore = defineStore('user', {
    /**
     * currentUser from server
     * has {
     *     full_name : string,
     *     display_name: string,
     *     email: string
     *     id: number
     *     metadata: Array<{user_meta_key: string, user_meta_value: string}>
     *     permissions: Array<string>
     *     role: string,
     *     status: string {Active, Inactive}
     * }
     */
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
             * Set the users initials - save as display_name
             */
            let str = user.name;
            let matches = str.match(/\b(\w)/g);
            let initials = matches.join('');

            console.log(initials);

            let data = {
                userData: {
                    full_name: user.name,
                    email: user.email,
                    role: user.role,
                    display_name: initials
                },
                userMetaData: {
                    yearLevels: user.yearLevels,
                    interest: user.interests,
                    subjects: user.subjects,
                    biography: user.biography,
                }
            };

            await axios({
                method: 'POST',
                url: 'http://localhost:8000/api/createUser',
                data: data
            }).then(response => {
                console.log(response);
            }).catch(error => {
                console.log('There was a problem updating your info');
                console.error(error);
            })
        },

        async updateSingleUserItem(change) {
            let data = {
                data: {
                    updateField: 'full_name',
                    updateValue: 'new name'
                },
                metaData: {
                    updateField: 'yearLevels',
                    updateValue: ['1', '3']
                }
            }
        }
    }
})
