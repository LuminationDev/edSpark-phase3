<script setup lang="ts">

import {storeToRefs} from "pinia";
import {computed, onMounted, ref} from "vue";
import {useRouter} from "vue-router";

import Loader from "@/js/components/spinner/Loader.vue";
import UserNotificationItemLarge
    from "@/js/components/userprofile/userprofileupdate/usernotification/UserNotificationItemLarge.vue";
import UserNotificationItemSmall
    from "@/js/components/userprofile/userprofileupdate/usernotification/UserNotificationItemSmall.vue";
import {lowerSlugify} from "@/js/helpers/stringHelpers";
import {notificationService} from "@/js/service/notificationService";
import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({
    notificationSize:{
        type: String,
        required: false,
        default: 'small'
    },
    notificationUnread:{
        type: String,
        required: false,
        default:'unread'
    }
})
const isLoading = ref(true);
const notificationUnread = ref(props.notificationUnread);
const notifications = ref([])
const allNotifications = ref([])

const {currentUser} = storeToRefs(useUserStore())
const router = useRouter()

onMounted(() => {
    notificationService.getNotifications(currentUser.value.id).then(res => {
        notifications.value = res.data.data
        isLoading.value = false
    }).catch(err => {
        console.log(err.message)
        isLoading.value = false
    })
    notificationService.getAllNotifications(currentUser.value.id).then(res =>{
        allNotifications.value = res.data.data
        console.log(allNotification)
        isLoading.value = false
    }).catch(err => {
        console.log(err.message)
        isLoading.value = false
    })
});


console.log(notificationService.getNotifications(notifications.value))
const handleClickNotification = (notificationId,resourceId, resourceType, resourceTitle) =>{
    const routeInfo = {
        name: '',
        params: {
            id:  resourceId,
            slug: lowerSlugify(resourceTitle)
        }
    }
    if(resourceType === "ADVICE"){
        routeInfo.name = 'guide-single'
    } else if(resourceType === 'EVENT'){
        routeInfo.name = 'event-single'
    } else if(resourceType ==='SOFTWARE'){
        routeInfo.name = 'software-single'
    }
    if(!routeInfo.name) {
        console.log('Resource Type is not recognized')
        return
    }
    else {
        notificationService.readNotification(notificationId)
        router.push(routeInfo)
    }
}

const filteredNotifications = computed(() => {
    if (notificationUnread.value === 'unread') {
        return notifications.value;
    } else if (notificationUnread.value === 'read') {
        return allNotifications.value;
    }
    // Add more conditions if needed
});

</script>

<template>
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
        v-else-if="notifications.length && notificationUnread === 'unread' || notificationUnread === 'read'"
        class="ListingNotificationLayout"
    >
        <div
            v-for="(notification, index) in filteredNotifications"
            :key="index"
            class="mr-4"
        >
            <UserNotificationItemSmall
                :is="getNotificationComponent"
                v-if="notificationSize === 'small'"
                :display-heading="notification.title"
                :time-date="notification.updated_at"
                :category-text="notification.type"
                :click-callback="() => handleClickNotification(notification.id,notification.resource_id, notification.type,notification.title )"
            />
            <UserNotificationItemLarge
                :is="getNotificationComponent"
                v-else-if="notificationSize === 'large'"
                :display-heading="notification.title"
                :time-date="notification.updated_at"
                :display-author="notification.author_name"
                :display-action="notification.action"
                :category-text="notification.type"
                :click-callback="() => handleClickNotification(notification.id,notification.resource_id, notification.type,notification.title )"
            />
        </div>
    </div>

    <div v-else>
        <p class="ml-1 mt-10">
            No notifications available
        </p>
    </div>
</template>

<style scoped>

</style>
