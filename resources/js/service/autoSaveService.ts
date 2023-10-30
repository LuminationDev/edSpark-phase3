import axios, {AxiosResponse} from 'axios'

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

export const autoSaveService = {
    getExpiryDateMonth: (): string => {
        // Get the current date
        const currentDate = new Date();
        currentDate.setDate(currentDate.getDate() + 30);

        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
        const day = String(currentDate.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    },
    savePost: (user_id: number, type: string, data: Record<string, any>): Promise<void> => {
        console.log(data)
        /**
         * from softwareForm, data is
         * authorName
         * content
         * coverImage
         * excerpt
         * extra_content
         * softwaretype_id
         * tags
         * title
         */
        const expiryDate = autoSaveService.getExpiryDateMonth()
        console.log(expiryDate)
        const requestPayload = {
            user_id: user_id,
            post_id: 10, // TODO: Fix this. maybe put this to zero and the backend will mutate before saving
            post_type: type,
            content: JSON.stringify(data),
            exp_date: expiryDate

        }

        return axios.post(API_ENDPOINTS.AUTOSAVE.AUTOSAVE, requestPayload)

    },
    getAutoSave: (user_id: number, type: string): Promise<AxiosResponse<any>> => {
        const url = API_ENDPOINTS.AUTOSAVE.AUTOSAVE;

        return axios.get(url, {
            params: {
                user_id: user_id,
                post_type: type
            }
        })
    },
    getAllUserDraftPost: (user_id: number): Promise<AxiosResponse<any>> => {
        const url = API_ENDPOINTS.USER.GET_USER_DRAFT_POSTS;
        return axios.get(url, {
            params: {
                user_id: user_id,
            }
        })
    }
}