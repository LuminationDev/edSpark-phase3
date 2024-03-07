import axios, {AxiosResponse} from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

export const notificationService = {
    getNotifications: async (currentUserId) : Promise<AxiosResponse<any>> =>{
        return axios.get(API_ENDPOINTS.NOTIFICATION.GET_NOTIFICATIONS + currentUserId);
    },
    getAllNotifications: async (currentUserId) : Promise<AxiosResponse<any>> =>{
        return axios.get(API_ENDPOINTS.NOTIFICATION.GET_ALL_NOTIFICATIONS + currentUserId);
    },
    readNotification: async (notificationId): Promise<any> =>{
        return axios.post(API_ENDPOINTS.NOTIFICATION.READ_NOTIFICATION + notificationId)
    }

}
