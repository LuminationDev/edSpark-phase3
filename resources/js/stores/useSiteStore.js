import { defineStore } from "pinia";
import axios from 'axios';

export const useSiteStore = defineStore('sites', {
    state: () => ({
        sites: [],
        singleSite: {}
    }),

    getters: {
        getSites() {
            return this.sites;
        },
        getSingleSite() {
            return this.singleSite;
        }
    },

    actions: {
        loadSites() {
            console.log('Hi there');
            axios.get('http://localhost:8000/api/fetchAllSites').then(res => {
                console.log(res);
                this.sites = res.data;
            }).catch(err => {
                console.error(err);
                console.log('Theres an error');
            })
        },

        getSiteById(site_id) {
            this.singleSite = this.getSites.filter(site => site === site_id)
        }
    }
})
