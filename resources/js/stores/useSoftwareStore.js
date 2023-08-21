import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import { defineStore } from "pinia";
import axios from 'axios';
import {serverURL} from "@/js/constants/serverUrl";

export const useSoftwareStore = defineStore('software', {
    state: () => ({
        recommendedSoftware: []
    }),

    getters: {
        getArticles() {
            return this.recommendedSoftware;
        }
    },

    actions: {
        async loadRecommendedSoftware(id){
            const payload = {
                currentId: id,
            }
            axios.post(API_ENDPOINTS.SOFTWARE.FETCH_RELATED_SOFTWARE, payload).then(res =>{
                console.log(res.data)
                this.recommendedSoftware = res.data
            } )
        }
    }
})
