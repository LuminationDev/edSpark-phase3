import { defineStore } from "pinia";
import axios from 'axios';
import {schoolContentArrParser} from "@/js/helpers/jsonHelpers";

export const useSchoolsStore = defineStore('schools', {
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
            return await axios.get(`http://localhost:8000/api/fetchAllSchools`).then(res => {
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
