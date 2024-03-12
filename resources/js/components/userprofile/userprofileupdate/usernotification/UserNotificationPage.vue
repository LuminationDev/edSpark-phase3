<script setup lang="ts">

import {storeToRefs} from "pinia";
import {onMounted, ref} from "vue";
import {useRouter} from "vue-router";

import Loader from "@/js/components/spinner/Loader.vue";
import UserNotificationItemSmall
    from "@/js/components/userprofile/userprofileupdate/usernotification/UserNotificationItemSmall.vue";
import UserProfileContentContainer from "@/js/components/userprofile/userprofileupdate/UserProfileContentContainer.vue";
import {lowerSlugify} from "@/js/helpers/stringHelpers";
import {notificationService} from "@/js/service/notificationService";
import {useUserStore} from "@/js/stores/useUserStore";

const isLoading = ref(true);
const unreadNotification = ref([])
const readNotification = ref([])

const {currentUser} = storeToRefs(useUserStore())
const router = useRouter()

const leftHeadingNotification = ref('Notifications')
const leftDescriptionNotification = ref('Stay up to date with the latest activities.')
const reloadKey = ref(0)

onMounted(() => {
    notificationService.getAllNotifications(currentUser.value.id).then(res => {
        readNotification.value = res.data.data.filter(notification => notification.read_at);
        unreadNotification.value = res.data.data.filter(notification => !notification.read_at)
        isLoading.value = false
    })
    //  emits('sendMarkAllAsReadButton', handleMarkAllAsRead)
});

console.log(notificationService.getNotifications(unreadNotification.value))
const handleClickNotification = (notificationId, resourceId, resourceType, resourceTitle) => {
    const routeInfo = {
        name: '',
        params: {
            id: resourceId,
            slug: lowerSlugify(resourceTitle)
        }
    }
    if (resourceType === "ADVICE") {
        routeInfo.name = 'guide-single'
    } else if (resourceType === 'EVENT') {
        routeInfo.name = 'event-single'
    } else if (resourceType === 'SOFTWARE') {
        routeInfo.name = 'software-single'
    }
    if (!routeInfo.name) {
        console.log('Resource Type is not recognized')
        return
    } else {
        notificationService.readNotification(notificationId)
        router.push(routeInfo)
    }
}

const handleMarkAllAsRead = () => {
    isLoading.value = true;
    notificationService.readAllNotifications(currentUser.value.id)
        .then(() => {
            refreshNotifications();
            console.log("Mark All as read button is pressed!")
        })
        .catch((error) => {
            console.error("Error marking all notifications as read:", error.message);
            isLoading.value = false
        })
}

const refreshNotifications = async () => {
    isLoading.value = true;
    try {
        notificationService.getAllNotifications(currentUser.value.id).then(res => {
            readNotification.value = res.data.data.filter(notification => notification.read_at);
            unreadNotification.value = res.data.data.filter(notification => !notification.read_at)
        })
    } catch (error) {
        console.error("Error refreshing notifications:", error.message)
    } finally {
        isLoading.value = false
    }
}

</script>

<template>
    <div class="UnreadNotifications-UserProfile">
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
                                    <div v-else-if="!isLoading">
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
                                        <div
                                            v-if="unreadNotification.length"
                                            class="ListingNotificationLayout"
                                        >
                                            <div
                                                v-for="(notification, index) in unreadNotification"
                                                :key="index"
                                                class="mr-4"
                                            >
                                                <UserNotificationItemSmall
                                                    :display-heading="notification.title"
                                                    :time-date="notification.updated_at"
                                                    :category-text="notification.type"
                                                    :click-callback="() => handleClickNotification(notification.id,notification.resource_id, notification.type,notification.title )"
                                                />
                                            </div>
                                        </div>
                                        <div v-else>
                                            <p class="ml-1 mt-10">
                                                No unread notifications available
                                            </p>
                                        </div>
                                        <div class="flex flex-row mt-10">
                                            <div class="ml-1">
                                                Read Notifications
                                            </div>
                                        </div>
                                        <div
                                            v-if="readNotification.length"
                                            class="ListingNotificationLayout"
                                        >
                                            <div
                                                v-for="(notification, index) in readNotification"
                                                :key="index"
                                                class="mr-4"
                                            >
                                                <UserNotificationItemSmall
                                                    :display-heading="notification.title"
                                                    :time-date="notification.updated_at"
                                                    :category-text="notification.type"
                                                    :click-callback="() => handleClickNotification(notification.id,notification.resource_id, notification.type,notification.title )"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </UserProfileContentContainer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
