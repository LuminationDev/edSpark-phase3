import axios, {AxiosResponse} from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

export const dmaService = {
    // TODO proper typing on APIs
    getSurvey: async ():Promise<AxiosResponse<any>> => {
        return axios.get(API_ENDPOINTS.DMA.USER_SURVEY, {}).then(res => {
            console.log("survey data", res.data.data);
            return res.data.data;
        })
    },
    getQuestions: async (surveyId: string, domainId: string):Promise<AxiosResponse<any>> => {
        return axios.get(`${API_ENDPOINTS.DMA.USER_SURVEY}/domain/${domainId}/questions`).then(res => {
            return res.data.data;
        })
    },
}
