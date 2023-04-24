import axios from "axios";

export const axiosFetcher = (url) => {
    return axios.get(url).then(res => {return res.data})
}