import axios, {Axios, AxiosResponse} from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

type CheckCanEditResponseType = {
    status: string;
    result: string;
    canNominate: boolean
}
export const schoolService = {
    testFunction: (): void => {
        console.log('testing')
    },
    fetchSchoolByName: async (schoolName : string) : Promise<AxiosResponse<any>> =>{
        return axios.get(`${API_ENDPOINTS.SCHOOL.FETCH_SCHOOL_BY_NAME}${schoolName}`)
    },
    checkIfUserCanEdit: async (site_id, user_id, school_id): Promise<AxiosResponse<CheckCanEditResponseType>> => {
        const data = {
            "site_id": site_id,
            "user_id": user_id,
            "school_id": school_id
        }
        return axios.post(
            API_ENDPOINTS.SCHOOL.CHECK_IF_USER_CAN_EDIT_SCHOOL,
            data
        )
    },
    fetchPendingSchoolByName: async(schoolName, site_id, user_id, school_id) : Promise<AxiosResponse<any>> =>{
        const data = {
            "site_id": site_id,
            "user_id": user_id,
            "school_id": school_id
        }
        return axios.post(
            API_ENDPOINTS.SCHOOL.FETCH_PENDING_SCHOOL_BY_NAME + schoolName,
            data
        )
    },
    getSchoolDataForDash: async (user_id :number, site_id:number) =>{
        const payload = {
            site_id: site_id,
            user_id: user_id
        }
        return axios.post(`${API_ENDPOINTS.SCHOOL.FETCH_USER_SCHOOL}`, payload)
    }
}