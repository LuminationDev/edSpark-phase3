<script setup lang="ts">

import {storeToRefs} from "pinia";
import {onMounted, ref} from "vue";
import {useRouter} from "vue-router";

import Loader from "@/js/components/spinner/Loader.vue";
import UserNotificationItemSmall
    from "@/js/components/userprofile/userprofileupdate/usernotification/ListingDesignItemSmall.vue";
import {lowerSlugify} from "@/js/helpers/stringHelpers";
import {notificationService} from "@/js/service/notificationService";
import {useUserStore} from "@/js/stores/useUserStore";

const isLoading = ref(true);
const unreadNotification = ref([])

const {currentUser} = storeToRefs(useUserStore())
const router = useRouter()

onMounted(() => {
    notificationService.getAllNotifications(currentUser.value.id).then(res => {
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

</script>

<template>
    <div class="UnreadNotifications-UserProfile">
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
            v-else-if="unreadNotification.length"
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
    </div>
</template>

<style scoped>

</style>
