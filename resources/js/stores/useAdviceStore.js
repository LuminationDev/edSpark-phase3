import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {defineStore} from "pinia";
import axios from 'axios';
import {serverURL} from "@/js/constants/serverUrl";

export const useAdviceStore = defineStore('advice', {
    state: () => ({
        relatedAdvice: [],
    }),

    getters: {
        getResources() {
            return this.relatedAdvice;
        }
    },

    actions: {
        async loadRelatedAdvice(id) {
            const payload = {
                currentId: id
            }
            axios.post(API_ENDPOINTS.ADVICE.FETCH_RELATED_ADVICE, payload).then(res => {
                this.relatedAdvice = res.data
            }).catch(e => {
                console.log(e)
            })
        }
    }
})
