import { defineStore } from "pinia";
import axios from 'axios';

export const useSchoolsStore = defineStore('schools', {
    state: () => ({
        schools: [],
    }),

    getters: {
        getSchools() {
            return this.schools;
        }
    },

    actions: {
        async loadSchools() {
            await axios.get(`http://localhost:8000/api/fetchAllSchools`).then(res => {
                console.log(res);
                this.schools = res.data;
                // featuredSites.value = res.data.splice(0,4)
                // featuredSitesData.value = schoolContentArrParser(featuredSites.value)
            })
        }
    }
})
