<script setup>


import {storeToRefs} from "pinia";
import {computed, onMounted, ref} from "vue";

import Loader from "@/js/components/spinner/Loader.vue";
import UserNotificationLinearLayout
    from "@/js/components/userprofile/userprofileupdate/usernotification/UserNotificationLinearLayout.vue";
import UserProfileContentContainer from "@/js/components/userprofile/userprofileupdate/UserProfileContentContainer.vue";
import {notificationService} from "@/js/service/notificationService";
import {useUserStore} from "@/js/stores/useUserStore";


const {currentUser} = storeToRefs(useUserStore())
const leftHeadingNotification = ref('Notifications')
const leftDescriptionNotification = ref('Stay up to date with the latest activities.')
const reloadKey = ref(0)
// const markAllAsRead = ref()
const isLoading = ref(true);
// const notificationUnread = ref();
const unreadNotification = ref([])
const readNotification = ref([])

// const handleReceiveIsLoading = (value) => {
//     isLoading.value = value
// }

onMounted(() => {
    notificationService.getNotifications(currentUser.value.id).then(res => {
        unreadNotification.value = res.data.data
        isLoading.value = false
    }).catch(err => {
        console.log(err.message)
        isLoading.value = false
    })
    notificationService.getAllNotifications(currentUser.value.id).then(res =>{
        readNotification.value = res.data.data.filter(notification => notification.read_at)
        console.log(readNotification)
        isLoading.value = false
    }).catch(err => {
        console.log(err.message)
        isLoading.value = false
    })
    //  emits('sendMarkAllAsReadButton', handleMarkAllAsRead)
});

const handleMarkAllAsRead = () => {
    isLoading.value = true;
    notificationService.readAllNotifications(currentUser.value.id)
        .then(() => {
            refreshNotifications();
            console.log("Mark All as read button is pressed!")
        })
        .catch((error) => {
            console.error("Error marking all notifications as read:", error.message);
            isLoading.value = false;
        });
};

const refreshNotifications = async () => {
    isLoading.value = true;
    try {
        notificationService.getAllNotifications(currentUser.value.id).then(res => {
            readNotification.value = res.data.data.filter(notification => notification.read_at);
            unreadNotification.value = res.data.data.filter(notification => !notification.read_at)
        })
    } catch (error) {
        console.error("Error refreshing notifications:", error.message);
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <div class="UserProfilePage h-full w-full">
        <div
            class="bg-bottom bg-cover border-b-[1px] h-[12vh] profileCoverImage relative top-0 left-0 w-full"
        />
        <div class="flex flex-col profileBodyContainer w-full">
            <div class="flex flex-col min-h-[70vh] pb-10 px-4 md:!px-8 lg:!px-24">
                <div class="UserProfileSelectionMenuContainer flex flex-col mt-1">
                    <div>
                        <UserProfileContentContainer
                            id="reloadableDiv"
                            :key="reloadKey"
                            :left-heading="leftHeadingNotification"
                            :left-description="leftDescriptionNotification"
                        >
                            <template #content>
                                <div
                                    v-if="isLoading"
                                    class="loader"
                                >
                                    <Loader
                                        loader-type="small"
                                        loader-message="Fetching your notifications"
                                    />
                                </div>
                                <div
                                    v-else
                                    class="flex flex-col gap-10"
                                >
                                    <div

                                        class="ml-4 mt-2"
                                    >
                                        <div class="flex flex-row">
                                            <div class="ml-1">
                                                Unread Notifications
                                            </div>
                                            <button
                                                class="ml-auto mr-4 underline"
                                                @click="handleMarkAllAsRead"
                                            >
                                                Mark all as read
                                            </button>
                                        </div>
                                        <div>
                                            <div>
                                                <UserNotificationLinearLayout
                                                    notification-size="large"
                                                    notification-status="unread"

                                                    @send-is-loading="handleReceiveIsLoading"
                                                />
                                            </div>
                                            <div class="flex flex-row mt-10">
                                                <div class="ml-1">
                                                    Read Notifications
                                                </div>
                                            </div>
                                            <div>
                                                <UserNotificationLinearLayout
                                                    notification-size="large"
                                                    notification-status="read"
                                                    @send-is-loading="handleReceiveIsLoading"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </userprofilecontentcontainer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
