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
    checkIfUserCanEditPartner: async (currentUserId, partnerId): Promise<AxiosResponse<CheckCanEditResponseType>> => {
        const data = {
            "user_id": currentUserId,
            "partner_id": partnerId,
        }
        return axios.post(
            API_ENDPOINTS.PARTNER.CHECK_IF_USER_CAN_EDIT_PARTNER,
            data
        )
    },
    updatePartnerContent: async (currentPartnerProfile, partnerId: number, currentUserId: number, content: string, introduction: string, motto: string, logo: any, coverImage): Promise<AxiosResponse<any>> => {
        const data = new FormData();
        data.append('content', content);
        data.append('partner_id', String(partnerId));
        data.append('introduction', introduction);
        data.append('motto', motto);
        data.append('logo', logo ? logo: currentPartnerProfile.logo);
        data.append('cover_image', coverImage ? coverImage: currentPartnerProfile.cover_image)



        return axios.post(API_ENDPOINTS.PARTNER.UPDATE_PARTNER_CONTENT, data);
    },
    fetchPendingPartnerProfile: async (partnerId: number, currentUserId: number): Promise<AxiosResponse<any>> => {
        const data = {
            "user_id": currentUserId,
            "partner_id": partnerId,
        }
        return axios.post(
            API_ENDPOINTS.PARTNER.FETCH_PARTNER_PENDING_PROFILE,
            data
        )
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
    fetchUploadLogo: async (logoData: any): Promise<AxiosResponse<any>> => {
    const formData = new FormData();
    formData.append('logo', logoData);

    try {
        const response = await axios.post(
            API_ENDPOINTS.PARTNER.UPDATE_PARTNER_CONTENT,
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        );
        return response;
    } catch (error) {
        throw error;
    }
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
