import axios from "axios";

//import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

// TODO mock API config
const API_ENDPOINTS = {
    DMA: {
        USER_SURVEY: 'https://564eda1c-de8c-48a5-9daa-9825f781e774.mock.pstmn.io/user/survey', // add ?eg=empty to test new survey
        SURVEY: 'https://564eda1c-de8c-48a5-9daa-9825f781e774.mock.pstmn.io/survey',
    }
}

export const dmaService = {
    // TODO proper typing on APIs
    getSurvey: async () => {
        return axios.get(API_ENDPOINTS.DMA.USER_SURVEY, {}).then(res => {
            console.log("survey data", res.data.data);
            return res.data.data;
        })
    },
    getQuestions: async (surveyId: string, domainId: string) => {
        return axios.get(`${API_ENDPOINTS.DMA.SURVEY}/${surveyId}/domain/${domainId}/questions`).then(res => {
            return res.data.data;
        })
    },
}
