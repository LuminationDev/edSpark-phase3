import axios, {Axios, AxiosResponse} from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

type CheckCanEditResponseType = {
    status: string;
    result: string;
    canNominate: boolean
}
export const partnerService = {
    testFunction: (): void => {
        console.log('testing')
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
    updatePartnerContent: async(partnerId, currentUserId, content): Promise<AxiosResponse<any>> =>{
        if(partnerId === currentUserId){
            const data = {
                content : content,
                partner_id : partnerId
            }
            return axios.post(API_ENDPOINTS.PARTNER.UPDATE_PARTNER_CONTENT, data)
        }


    },
    fetchPartnerResource: (resourceType, partnerId) =>{
        const data = {
            params: {
                resource_type: resourceType,
                partner_id: partnerId
            }
        }
        return axios.get(API_ENDPOINTS.PARTNER.FETCH_PARTNER_RESOURCE, data)

    },

}