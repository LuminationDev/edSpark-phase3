import { defineStore } from "pinia";
import axios from 'axios';
import {schoolContentArrParser} from "@/js/helpers/jsonHelpers";
import {serverURL} from "@/js/constants/serverUrl";

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
        newSchool: {}
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
            return await axios.get(`${serverURL}/fetchAllSchools`).then(res => {
                const parsedRes = schoolContentArrParser(res.data)
                this.schools = parsedRes;
                return parsedRes;
            });
        },

        /**
         * Set new school data when a principal
         * is visiting for the first time
         */
        setNewSchoolOnSignIn(data) {
            this.newSchool = data;
        }
    }
})