import axios from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {cardDataWithGuid} from "@/js/helpers/cardDataHelper";

export const adviceService = {
    fetchAllAdvice: () => {
        return axios.get(API_ENDPOINTS.ADVICE.FETCH_ADVICE_POSTS,).then(res => {
            return cardDataWithGuid(res.data)
        })
    },
    fetchDagAdvice: () => {
        return axios.get(API_ENDPOINTS.ADVICE.FETCH_ADVICE_POSTS_BY_TYPE_DAG).then(res => {
            return cardDataWithGuid(res.data)
        })
    },
    fetchPartnerAdvice: () => {
        return axios.get(API_ENDPOINTS.ADVICE.FETCH_ADVICE_POSTS_BY_TYPE_PARTNER).then(res => {
            return cardDataWithGuid(res.data)
        })
    }, fetchGeneralAdvice: () => {
        return axios.get(API_ENDPOINTS.ADVICE.FETCH_ADVICE_POSTS_BY_TYPE_YOUR).then(res => {
            return cardDataWithGuid(res.data)
        })
    },
    fetchLearningTask: () =>{
        return axios.get(API_ENDPOINTS.ADVICE.LEARNING_TASK).then(res =>{
            return cardDataWithGuid(res.data)
        })
    },
    fetchDAG: () =>{
        return axios.get(API_ENDPOINTS.ADVICE.DAG).then(res =>{
            return cardDataWithGuid(res.data)
        })
    },
    fetchAdviceTypes: () =>{
        return axios.get(API_ENDPOINTS.ADVICE.FETCH_ADVICE_TYPES).then(res =>{
            return res.data
        })
    }
}