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
    updatePartnerContent: async (partnerId, currentUserId, content): Promise<AxiosResponse<any>> => {
        if (partnerId === currentUserId) {
            const data = {
                content: content,
                partner_id: partnerId
            }
            return axios.post(API_ENDPOINTS.PARTNER.UPDATE_PARTNER_CONTENT, data)
        }


    },
    fetchPartnerSoftware: async (partnerId): Promise<any> => {
        const data = {
            params: {
                usid: partnerId,
                user_id: partnerId
            }
        }
        return new Promise(async (res, rej) => {
            const result = await axios.get(API_ENDPOINTS.SOFTWARE.FETCH_USER_SOFTWARE, data)
            res(result.data)
        })


    },
    fetchPartnerHardware: async (partnerId): Promise<any> => {
        const data = {
            params: {
                usid: partnerId,
                user_id: partnerId
            }
        }
        return new Promise(async (res, rej) => {
            const result = await axios.get(API_ENDPOINTS.HARDWARE.FETCH_USER_HARDWARE, data)
            res(result.data)

        })

    },
    fetchPartnerAdvice: async (partnerId): Promise<any> => {
        const data = {
            params: {
                usid: partnerId,
                user_id: partnerId
            }
        }
        return new Promise(async (res, rej) => {
            const result = await axios.get(API_ENDPOINTS.ADVICE.FETCH_USER_ADVICE, data)
            res(result.data)
        })


    },

}