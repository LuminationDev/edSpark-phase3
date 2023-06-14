import { defineStore } from "pinia";
import { useSessionStorage } from "@vueuse/core";
import axios from "axios";
import {serverURL} from "@/js/constants/serverUrl";

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
        userAvatar: useSessionStorage('userAvatar', ''),
        userLikeList: {},
        userBookmarkList: {},
        notifications: [],
    }),

    getters: {
        getUser() {
            return this.currentUser;
        },

        getNotifications() {
            return this.notifications;
        }
    },

    actions: {
        async updateUserName(newName) {
            await axios({
                method: 'POST',
                url: `${serverURL}/updateUser`,
                data: {
                    id: this.currentUser.id,
                    data: {
                        updateField: 'full_name',
                        updateValue: newName
                    }
                }
            }).then(response => {
                console.log(response);

                this.currentUser.full_name = newName;
            }).catch(error => {
                console.error(error);
            });
        },

        async fetchCurrentUserAndLoadIntoStore(userId) {
            console.log(userId);
            return axios.get(`${serverURL}/fetchUser/${userId}`).then(response => {
                this.currentUser = response.data;
            }).catch(error => {
                console.log('There was a problem retrieving that user');
                console.error(error);
            })
        },

        async checkUser(email) {
            return new Promise(async resolve => {
                console.log('HERE YOU ARE!!!!!');
                await axios({
                    method: 'POST',
                    url: `${serverURL}/checkEmail`,
                    data: {
                        email: email
                    }
                }).then(response => {
                    console.log(response.data);
                    resolve(response.data);
                }).catch(error => {
                    console.log(error);
                    return error
                })
            })
        },

        async fetchAllRoles() {
            return new Promise(async (resolve, reject) => {
                await axios.get(`${serverURL}/fetchAllRoles`).then(response => {
                    // console.log(response);
                    const allowedValues = [
                        'SCHLDR',
                        'PRESCLDR',
                        'SITELDR',
                        'STCH',
                        'PTCH',
                        'SITESUPP',
                        'PSACT',
                        'IT',
                        'OTHER',
                        'STAFF'
                    ];

                    let allowedList = response.data.filter((item) => allowedValues.indexOf(item.role_name) !== -1);

                    let mutatedArray = [];

                    allowedList.forEach(item => {
                        mutatedArray.push({
                            id: item.id,
                            name: item.role_value
                        });
                    });
                    resolve(mutatedArray);
                }).catch(error => {
                    console.log(error);
                    reject(error.code);
                })
            })
        },

        getUserSiteById(siteId) {
            siteId = siteId.replace(/^0+/, '');
            console.log(siteId);
            return new Promise(async resolve => {
                await axios.get(`${serverURL}/fetchSiteByCode/${siteId}`).then(response => {
                    console.log(response.data);
                    resolve(response.data);
                }).catch(error => {
                    console.log('error', error);
                })
            })
        },

        async createUser(user) {
            console.log(user);
            /**
             * Set the users initials - save as display_name
             */
            let str = user.name;
            let matches = str.match(/\b(\w)/g);
            let initials = matches.join('');

            let userData = new FormData();
            // let metaData = new FormData();
            // let formAvatar = new FormData();

            userData.append('userAvatar', user.avatar ? user.avatar : user.avatarUrl);
            /**
             * Populate formData Object
             */
            userData.append('full_name', user.name);
            userData.append('email', user.email);
            userData.append('display_name', JSON.stringify(initials));
            userData.append('site_id', JSON.stringify(user.site.id)); // Use the id to store as foreign key
            userData.append('role_id', JSON.stringify(user.role.id)); // Use the id to store as foreign key
            let userMetadata = {
                yearLevels: user.yearLevels,
                interests: user.interests,
                subjects: user.subjects,
                biography: user.biography

            }
            /**
             * Populate metaData Object
             */
            userData.append('metadata', JSON.stringify(userMetadata));

            return axios({
                method: 'POST',
                url: `${serverURL}/createUser`,
                data: userData,
                headers: { "Content-Type" : "multipart/form-data" }
            }).then(async response => {
                console.log(response);
                // TODO: Maybe populate user data from here instead?
                // in opposed to sending another request. make create user return user data if success
                await this.fetchCurrentUserAndLoadIntoStore(response.data.uid);
                this.userAvatar = response.data.avatarUrl;
            }).catch(error => {
                if(error.status === 403){
                    console.log('Forbidden. User Email has been registered')
                }
                console.log(error.message)

            })
        },
        populateUserLikesAndBookmark(){
            if(this.currentUser.id){
                console.log('called populate like and bookmark')
                let data = {
                    user_id: this.currentUser.id
                }
                axios.post(`${serverURL}/fetchAllLikes`, data).then(res => {
                    res.data.data.forEach(like =>{
                        if(!this.userLikeList[like.post_type]){
                            this.userLikeList[like.post_type] = []
                        }
                        if(!this.userLikeList[like.post_type].includes(like.post_id)){
                            this.userLikeList[like.post_type].push(like.post_id)
                        }
                    })
                })
                axios.post(`${serverURL}/fetchAllBookmarks`, data).then(res => {
                    res.data.data.forEach(bookmark =>{
                        if(!this.userBookmarkList[bookmark.post_type]){
                            this.userBookmarkList[bookmark.post_type] = []
                        }
                        if(!this.userBookmarkList[bookmark.post_type].includes(bookmark.post_id)){
                            this.userBookmarkList[bookmark.post_type].push(bookmark.post_id)
                        }
                    })
                })

            } else {
                console.log('No currentUser Id. didnt fetch')
            }

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

        async fetchAllNotifications(userId) {
            return new Promise(async (resolve, reject) => {
                await axios.get(`${serverURL}/fetchAllNotifications/${userId}`)
                    .then(response => {
                        // console.log(response.data)
                        this.notifications = response.data;
                    }).catch(error => {
                        console.log('Sorry, there was problem retrieving notification data');
                        console.error(error);
                        reject(error.code);
                    });
            });
        },

        clearStore() {
            this.currentUser = {};
            if (sessionStorage.getItem('currentUser') === null) return;
            sessionStorage.removeItem('currentUser');
        }
    }
})
