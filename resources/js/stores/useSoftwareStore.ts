import axios from 'axios';
import { defineStore } from "pinia";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {serverURL} from "@/js/constants/serverUrl";

export const useSoftwareStore = defineStore('software', {
    state: () => ({
        relatedSoftware: [],
        allSoftware: []
    }),

    getters: {
        getArticles() {
            return this.relatedSoftware;
        }
    },

    actions: {
        async loadRelatedSoftware(id){
            const payload = {
                currentId: id,
            }
            axios.post(API_ENDPOINTS.SOFTWARE.FETCH_RELATED_SOFTWARE, payload).then(res =>{
                console.log(res.data)
                this.relatedSoftware = res.data
            } )
        }
    }
})
