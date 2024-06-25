import axios from "axios";

import {cardDataWithGuid} from "@/js/helpers/cardDataHelper";
import {schoolContentArrParser} from "@/js/helpers/jsonHelpers";

export const axiosFetcher = (url) => {
    return axios.get(url).then(res => {
        return cardDataWithGuid(res.data)
    })
}

export const axiosSchoolFetcher = (url) => {
    return axios.get(url).then(res => {
        return schoolContentArrParser(res.data)
    })
}


export const axiosFetcherParams = (params) => {
    return (url) => axios.get(url, params).then(res => {
        return cardDataWithGuid(res.data)
    })
}