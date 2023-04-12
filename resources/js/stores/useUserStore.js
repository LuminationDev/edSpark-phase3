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
            console.log(user.avatar);
            /**
             * Set the users initials - save as display_name
             */
            let str = user.name;
            let matches = str.match(/\b(\w)/g);
            let initials = matches.join('');

            let userData = new FormData();
            // let metaData = new FormData();
            // let formAvatar = new FormData();

            userData.append('userAvatar', user.avatar);
            /**
             * Populate formData Object
             */
            userData.append('full_name', user.name);
            userData.append('email', user.email);
            userData.append('display_name', JSON.stringify(initials));
            userData.append('site_id', JSON.stringify(user.site.id)); // Use the id to store as foreign key
            userData.append('role_id', JSON.stringify(4)); // Use the id to store as foreign key

            /**
             * Populate metaData Object
             */
            userData.append('yearLevels', JSON.stringify(user.yearLevels));
            userData.append('interest', JSON.stringify(user.interests));
            userData.append('subjects', JSON.stringify(user.subjects));
            userData.append('biography', JSON.stringify(user.biography));

            console.log(userData.get('full_name'));

            let data = {
                // userData: userData,
                // metaData: metaData
                // userData: {
                //     full_name: user.name,
                //     email: user.email,
                //     role: user.role,
                //     display_name: initials,
                //     site_id: user.site.id,
                //     role_id: 4
                // },
                // metaData: {
                //     yearLevels: user.yearLevels,
                //     interest: user.interests,
                //     subjects: user.subjects,
                //     biography: user.biography,
                //     userAvatar: formAvatar
                // },
            };

            console.log(data);

            await axios({
                method: 'POST',
                url: 'http://localhost:8000/api/createUser',
                data: userData,
                headers: { "Content-Type" : "multipart/form-data" }
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
        },

        clearStore() {
            this.currentUser = {};
            if (sessionStorage.getItem('currentUser') === null) return;
            sessionStorage.removeItem('currentUser');
        }
    }
})
