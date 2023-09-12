import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import axios from 'axios'
export const formService = {
    sendAutoSave: async () =>{
        return axios.post(API_ENDPOINTS.AUTOSAVE.AUTOSAVE)
    },
    getAutoSave: async ()  => {
        return axios.get(API_ENDPOINTS.AUTOSAVE.AUTOSAVE)
    },
    getTypes: async (url) => {
        return axios.get(url)
    }
}