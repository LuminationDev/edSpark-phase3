import axios from "axios";
import {schoolContentArrParser} from "@/js/helpers/jsonHelpers";

export const axiosFetcher = (url) => {
    return axios.get(url).then(res => {return res.data})
}

export const axiosSchoolFetcher = (url) => {
    return axios.get(url).then(res =>{
        return schoolContentArrParser(res.data)
    })
}