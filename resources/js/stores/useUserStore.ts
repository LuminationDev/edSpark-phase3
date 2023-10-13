import {useSessionStorage, useStorage} from "@vueuse/core";
import axios from "axios";
import {defineStore} from "pinia";
import {Ref} from 'vue'

import {serverURL} from "@/js/constants/serverUrl";
import {SchoolDataType} from "@/js/types/SchoolTypes";

interface UserMetaData {
    user_meta_key: string;
    user_meta_value: string;
}

interface Site {
    site_name: string;
    site_id: number
}

interface CurrentUser {
    full_name: string;
    display_name: string;
    email: string;
    id: number;
    metadata: UserMetaData[];
    permissions: string[];
    role: string;
    status: 'Active' | 'Inactive';
    site?: Site;
}

// Define the type for the state
interface UserState {
    currentUser: Ref<CurrentUser | any>;
    userAvatar: Ref<string>;
    userSchool: SchoolDataType | null
    notifications: any[];
    userRequestParam: Record<string, any>;
}


export const useUserStore = defineStore('user', {
    state: (): UserState => <UserState>({
        currentUser: useStorage('currentUser', {}, localStorage, {mergeDefaults: true}),
        userAvatar: useSessionStorage('userAvatar', ''),
        userSchool: {},
        notifications: [],
        userRequestParam: {}
    }),

    getters: {
        getUser(): CurrentUser | any {
            return this.currentUser;
        },
        getUserFullName(): string {
            if (this.currentUser && this.currentUser.full_name) {
                return this.currentUser.full_name;
            } else {
                return "";
            }
        },
        getUserSiteName(): string {
            console.log(this.currentUser?.site?.site_name);
            return this.currentUser?.site?.site_name || "";
        },
        getUserRequestParam(): { params: { usid: number } } {
            console.log(this.currentUser);
            return {
                params: {
                    usid: this.currentUser.id
                }
            };
        },
        getNotifications(): any[] {
            return this.notifications;
        },
        getIfUserIsAdmin(): boolean {
            const userRole = this.currentUser.role
            const rolesWithAdminRights = [
                "Superadmin",
                "Administrator",
                "Moderator",
                "PSACT",
                "SCHLDR"
            ];
            return rolesWithAdminRights.includes(userRole)
        },
        getIfUserIsModerator(): boolean {
            const userRole = this.currentUser.role
            const rolesWithModRights = [
                "Superadmin",
                "Administrator",
                "Moderator",
            ];
            return rolesWithModRights.includes(userRole)
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

        async fetchCurrentUserAndLoadIntoStore() {
            return axios.get(`${serverURL}/fetchUser`).then(response => {
                console.log('fetch user has been called')
                console.log(response.data)
                this.currentUser = response.data;
            }).catch(error => {
                console.log('There was a problem retrieving that user');
                console.error(error);
            })
        },

        async checkUser(email) {
            return new Promise(async resolve => {
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

                    const allowedList = response.data.filter((item) => allowedValues.indexOf(item.role_name) !== -1);

                    const mutatedArray = [];

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
            const str = user.name;
            const matches = str.match(/\b(\w)/g);
            const initials = matches.join('');

            const userData = new FormData();
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
            const userMetadata = {
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
                headers: {"Content-Type": "multipart/form-data"}
            }).then(async response => {
                console.log(response);
                // TODO: Maybe populate user data from here instead?
                // in opposed to sending another request. make create user return user data if success
                await this.fetchCurrentUserAndLoadIntoStore(response.data.uid);
                this.userAvatar = response.data.avatarUrl;
            }).catch(error => {
                if (error.status === 403) {
                    console.log('Forbidden. User Email has been registered')
                }
                console.log(error.message)

            })
        },
        async updateFirstTimeVisit(user) {
            console.log("UPDATE USER INFO", user);
            /**
             * Set the users initials - save as display_name
             */
            const str = user.name;
            const matches = str.match(/\b(\w)/g);
            const initials = matches.join('');

            const userData = new FormData();
            userData.append('userAvatar', user.avatar ? user.avatar : user.avatarUrl);
            /**
             * Populate formData Object
             */
            userData.append('full_name', user.name);
            userData.append('email', user.email);
            userData.append('display_name', JSON.stringify(initials));
            userData.append('site_id', JSON.stringify(user.site.id)); // Use the id to store as foreign key
            userData.append('role_id', JSON.stringify(user.role.id)); // Use the id to store as foreign key
            const userMetadata = {
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
                url: `${serverURL}/updateFirstTimeVisitUser`,
                data: userData,
                headers: {"Content-Type": "multipart/form-data"}
            }).then(async response => {
                console.log(response);
                // TODO: Maybe populate user data from here instead?
                // in opposed to sending another request. make create user return user data if success
                await this.fetchCurrentUserAndLoadIntoStore(response.data.uid);
                this.userAvatar = response.data.avatarUrl;
            }).catch(error => {
                if (error.status === 403) {
                    console.log('Forbidden. User Email has been registered')
                }
                console.log(error.message)

            })
        },

        async updateSingleUserItem(change) {
            const data = {
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
            if (localStorage.getItem('currentUser') === null) return;
            localStorage.removeItem('currentUser');
        }
    }
})
