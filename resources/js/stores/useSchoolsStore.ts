import axios from 'axios';
import {defineStore, storeToRefs} from "pinia";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {schoolContentArrParser} from "@/js/helpers/jsonHelpers";
import {schoolService} from "@/js/service/schoolService";
import {useUserStore} from "@/js/stores/useUserStore";

export const useSchoolsStore = defineStore('schools', {
    /** newSchool (reside inside schoolsStore from FirstVisitForm):{
     *     coverImage: File,
     *     coverImageUrl: string | null,
     *     logo: File,
     *     logoUrl: string | null,
     *     role:{
     *         id: number,
     *         name: string
     *     },
     *     schoolMessage: String (motto),
     *     schoolName: String,
     *     site:{
     *         id: number
     *         name: string
     *     },
     *     techUsed: Array<string>
     * }
     */
    state: () => ({
        schools: [],
        newSchool: {},
        currentUserSchool: {}
    }),

    getters: {
        getSchools() {
            return this.schools;
        },
        getNewSchool() {
            return this.newSchool;
        }
    },

    actions: {
        async loadSchools() {
            const userStore = useUserStore()
            return axios.get(API_ENDPOINTS.SCHOOL.FETCH_ALL_SCHOOLS, userStore.getUserRequestParam).then(res => {
                const parsedRes = schoolContentArrParser(res.data)
                this.schools = parsedRes;
                return parsedRes;
            });
        },
        async loadCurrentUserSchool(){
            const userStore = useUserStore()
            const { currentUser } = storeToRefs(userStore)
            this.currentUserSchool = await schoolService.getUserSchoolByUserSiteId(currentUser.value.id, currentUser.value.site_id)
        },

        // not being used
        setNewSchoolOnSignIn(data) {
            this.newSchool = data;
        }
    }
})