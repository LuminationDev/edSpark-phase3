import { defineStore } from "pinia";
import axios from 'axios';
import {serverURL} from "@/js/constants/serverUrl";

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
            return new Promise( async (resolve, reject) => {
                await axios.get(`${serverURL}/fetchAllSites`).then(res => {
                    // console.log(res);
                    resolve(res.data);
                    // this.sites = res.data;
                }).catch(err => {
                    console.error(err);
                    console.log('Theres an error');
                    reject(err.code);
                })
            })
        },

        getSiteById(site_id) {
            this.singleSite = this.getSites.filter(site => site === site_id)
        }
    }
})
