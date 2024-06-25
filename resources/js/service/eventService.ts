import axios, {AxiosResponse} from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {cardDataWithGuid} from "@/js/helpers/cardDataHelper";

export const eventService = {
    fetchAllEvent: async () => {
        return axios.get(API_ENDPOINTS.EVENT.FETCH_EVENT_POSTS).then(res => {
            return cardDataWithGuid(res.data)
        })
    }
    ,
    checkIfUserIsRSVP: async (checkRSVPData): Promise<AxiosResponse<any>> => {
        return axios.post(API_ENDPOINTS.EVENT.CHECK_IF_USER_RSVPED, checkRSVPData)
    },
    fetchEventTypes: async (): Promise<AxiosResponse<any>> => {
        return axios.get(API_ENDPOINTS.EVENT.FETCH_EVENT_TYPES).then(res => {
            return res.data
        })
    },
    fetchEventFormats: async (): Promise<AxiosResponse<any>> => {
        return axios.get(API_ENDPOINTS.EVENT.FETCH_EVENT_FORMATS).then(res => {
            return res.data
        })
    }
}
