import axios, {AxiosResponse} from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

export const notificationService = {
    getNotifications: async (currentUserId): Promise<AxiosResponse<any>> => {
        if (currentUserId) {
            return axios.get(API_ENDPOINTS.NOTIFICATION.GET_NOTIFICATIONS + currentUserId)
        } else {
            return;
        }
    },
    getAllNotifications: async (currentUserId): Promise<AxiosResponse<any>> => {
        if (currentUserId) {
            return axios.get(API_ENDPOINTS.NOTIFICATION.GET_ALL_NOTIFICATIONS + currentUserId)
        } else {
            return;
        }
    },
    readNotification: async (notificationId): Promise<any> => {
        return axios.post(API_ENDPOINTS.NOTIFICATION.READ_NOTIFICATION + notificationId)
    },
    readAllNotifications: async (currentUserId): Promise<AxiosResponse<any>> => {
        return axios.post(API_ENDPOINTS.NOTIFICATION.READ_ALL_NOTIFICATIONS + currentUserId)
    }

}
