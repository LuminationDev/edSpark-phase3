import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import { defineStore } from "pinia";
import axios from 'axios';
import {serverURL} from "@/js/constants/serverUrl";

export const useSoftwareStore = defineStore('software', {
    state: () => ({
        relatedSoftware: []
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
