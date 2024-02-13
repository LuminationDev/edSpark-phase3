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
    getQuestions: async (domainId: string):Promise<AxiosResponse<any>> => {
        return axios.get(`${API_ENDPOINTS.DMA.USER_SURVEY}/domain/${domainId}/questions`).then(res => {
            return res.data.data;
        })
    },
    postAnswer: async(
        domainId: string,
        questionId: string,
        answer: number,
        answerText: string,
        nextQuestionId: string,
        chapterComplete: boolean
    ):Promise<AxiosResponse<any>> => {
        return axios.post(`${API_ENDPOINTS.DMA.USER_SURVEY}/answer`, {
            domain_id: domainId,
            question_id: questionId,
            answer,
            answer_text: answerText,
            next_question_id: nextQuestionId,
            increase_completed_chapter_count: chapterComplete,
        }).then(res => {
            return res.data;
        })
    },
}
