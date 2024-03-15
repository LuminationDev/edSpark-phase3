import {defineStore} from "pinia";

import {notificationService} from "@/js/service/notificationService";



export const useNotificationStore = defineStore('notification', {
    state: () => ({
        readNotifications: [],
        unreadNotifications: [],
    }),
    getters: {
        getReadNotifications: state => state.readNotifications,
        getUnreadNotifications: state => state.unreadNotifications,
    },
    actions: {
        async fetchNotifications(userId) {
            try {
                const res = await notificationService.getAllNotifications(userId)
                this.readNotifications = res.data.data.filter(notification => notification.read_at)
                this.unreadNotifications = res.data.data.filter(notification => !notification.read_at)
            } catch (error) {
                console.error("Error fetching notifications:", error)
                throw error
            }
        }
    }
})
