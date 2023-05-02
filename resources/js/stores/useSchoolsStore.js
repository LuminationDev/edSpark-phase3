import { defineStore } from "pinia";
import axios from 'axios';

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
                this.schools = res.data;
                return res.data;
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
