import axios, {AxiosResponse} from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {cardDataWithGuid} from "@/js/helpers/cardDataHelper";

export const eventService = {
    fetchAllEvent: async () =>{
        return axios.get(API_ENDPOINTS.ADVICE.FETCH_ADVICE_POSTS).then(res =>{
            return cardDataWithGuid(res.data)
        })
    }
    ,
    checkIfUserIsRSVP: async (checkRSVPData): Promise<AxiosResponse<any>> => {
        return axios.post(API_ENDPOINTS.EVENT.CHECK_IF_USER_RSVPED, checkRSVPData)
    }
}
