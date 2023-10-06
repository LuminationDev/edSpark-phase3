import axios, {Axios, AxiosResponse} from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {parseToJsonIfString} from "@/js/helpers/jsonHelpers";
import {SchoolDataType} from "@/js/types/SchoolTypes";

type CheckCanEditResponseType = {
    status: string;
    result: string;
    canNominate: boolean
}
export const schoolService = {
    testFunction: (): void => {
        console.log('testing')
    },
    fetchSchoolByName: async (schoolName: string): Promise<any> => {
        return axios.get(`${API_ENDPOINTS.SCHOOL.FETCH_SCHOOL_BY_NAME}${schoolName}`).then(res => {
            const {data} = res;
            const {content_blocks, tech_used, cover_image, logo, metadata} = parseToJsonIfString(data);
            return ({
                ...data,
                content_blocks: content_blocks ? parseToJsonIfString(content_blocks) : {},
                tech_used: tech_used ? parseToJsonIfString(tech_used) : [],
                cover_image: cover_image ? cover_image.replace("/\\/g", "") : '',
                logo: logo ? logo.replace("/\\/g", "") : ''
            })
        })
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
    fetchPendingSchoolByName: async (schoolName, site_id, user_id, school_id): Promise<AxiosResponse<any>> => {
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
    /**
     * Get school info based on user's site id
     * if user is principal or has `SCHLDR` role, it will also automatically create schoolProfile from the sites
     * @param user_id
     * @param site_id
     */
    getUserSchoolByUserSiteId: async (user_id: number, site_id: number): Promise<AxiosResponse<any>> => {
        const payload = {
            site_id: site_id,
            user_id: user_id
        }
        return axios.post(`${API_ENDPOINTS.SCHOOL.FETCH_USER_SCHOOL}`, payload)
    },
    createSchool: async (data): Promise<AxiosResponse<any>> => {
        return 'schoo nam'
    },
    updateSchool: async (data): Promise<AxiosResponse<any>> => {
        return ''
    }
}