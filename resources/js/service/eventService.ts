import axios, {AxiosResponse} from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

export const eventService = {
    checkIfUserIsRSVP: async (checkRSVPData): Promise<AxiosResponse<any>> => {
        return axios.post(API_ENDPOINTS.EVENT.CHECK_IF_USER_RSVPED, checkRSVPData)
    }
}
