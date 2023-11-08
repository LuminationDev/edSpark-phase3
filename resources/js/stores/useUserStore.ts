import {useSessionStorage, useStorage} from "@vueuse/core";
import axios, {AxiosResponse} from "axios";
import {defineStore} from "pinia";
import {Ref} from 'vue'

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
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
        userEntryLink: useStorage('edspark-entry-link', '', sessionStorage),
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
        async fetchCurrentUserAndLoadIntoStore(): Promise<void> {
            return axios.get<CurrentUser>(API_ENDPOINTS.USER.FETCH_CURRENT_USER).then((response: AxiosResponse<CurrentUser>) => {
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

        async fetchAllNotifications() {
            return new Promise(async (resolve, reject) => {
                await axios.get(API_ENDPOINTS.USER.GET_USER_NOTIFICATION)
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
