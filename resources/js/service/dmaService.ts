import axios, {AxiosResponse} from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

export const dmaService = {
    // TODO proper typing on APIs
    getSurvey: async ():Promise<AxiosResponse<any>> => {
        return axios.get(API_ENDPOINTS.DMA.USER_SURVEY, {}).then(res => {
            return res.data.data;
        })
    },
    getQuestions: async (domainId: string):Promise<AxiosResponse<any>> => {
        return axios.get(`${API_ENDPOINTS.DMA.USER_SURVEY}/domain/${domainId}/questions`).then(res => {
            return res.data.data;
        })
    },
    getElementDescriptions: async (domainId: string):Promise<AxiosResponse<any>> => {
        return axios.get(`${API_ENDPOINTS.DMA.USER_SURVEY}/domain/${domainId}/elements`).then(res => {
            return res.data.data;
        })
    },
    postAnswer: async(
        domainId: string,
        questionId: string,
        answer: number,
        answerText: string,
        nextQuestionId: string,
        elementComplete: boolean
    ):Promise<AxiosResponse<any>> => {
        return axios.post(`${API_ENDPOINTS.DMA.USER_SURVEY}/answer`, {
            domain_id: domainId,
            question_id: questionId,
            answer,
            answer_text: answerText,
            next_question_id: nextQuestionId,
            increase_completed_element_count: elementComplete,
        }).then(res => {
            return res.data;
        })
    },
    resetDomainProgress: async(domainId:string):Promise<AxiosResponse<any>> => {
        return axios.delete(`${API_ENDPOINTS.DMA.USER_SURVEY}/domain/${domainId}`).then(res => {
            return res.data;
        })
    },
    resetSurveyProgress: async():Promise<AxiosResponse<any>> => {
        return axios.delete(`${API_ENDPOINTS.DMA.USER_SURVEY}`).then(res => {
            return res.data;
        })
    },

    putReflection: async(
        domainId: string,
        reflection: string,
    ):Promise<AxiosResponse<any>> => {
        return axios.put(`${API_ENDPOINTS.DMA.USER_SURVEY}/domain/${domainId}/reflection`, {
            reflection,
        }).then(res => {
            return res.data;
        })
    },

    getReflection: async():Promise<AxiosResponse<any>> => {
        return axios.get(`${API_ENDPOINTS.DMA.USER_SURVEY}/reflection`).then(res => {
            return res.data.data;
        })
    },

    getActionPlans: async():Promise<AxiosResponse<any>> => {
        return axios.get(`${API_ENDPOINTS.DMA.USER_SURVEY}/actionplans`).then(res => {
            return res.data.data;
        })
    },

    putActionPlan: async(
        domainId: string,
        element: string,
        action: string,
    ):Promise<AxiosResponse<any>> => {
        return axios.put(`${API_ENDPOINTS.DMA.USER_SURVEY}/domain/${domainId}/actionplan`, {
            element,
            action,
        }).then(res => {
            return res.data;
        })
    },

    deleteActionPlan: async(
        domainId: string,
        element: string,
    ):Promise<AxiosResponse<any>> => {
        return axios.delete(`${API_ENDPOINTS.DMA.USER_SURVEY}/domain/${domainId}/actionplan`, {
            data: {element},
        }).then(res => {
            return res.data;
        })
    },
}
