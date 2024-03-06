<script setup lang="ts">

import {storeToRefs} from "pinia";
import {onMounted, ref} from "vue";
import {useRouter} from "vue-router";

import Loader from "@/js/components/spinner/Loader.vue";
import UserNotificationItemSmall
    from "@/js/components/userprofile/userprofileupdate/usernotification/UserNotificationItemSmall.vue";
import {lowerSlugify} from "@/js/helpers/stringHelpers";
import {notificationService} from "@/js/service/notificationService";
import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({})

const notifications = ref([])

const {currentUser} = storeToRefs(useUserStore())
const router = useRouter()

onMounted(() => {
    notificationService.getNotifications(currentUser.value.id).then(res => {
        notifications.value = res.data.data
    }).catch(err => {
        console.log(err.message)
    })
});

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


</script>

<template>
    <div
        v-if="notifications.length"
        class="ListingNotificationLayout"
    >
        <div
            v-for="(notification, index) in notifications"
            :key="index"
            class="bg-gray-50 cursor-pointer flex items-center flex-row m-2 mt-4 rounded-3xl hover:!bg-gray-100"
        >
            <UserNotificationItemSmall
                :display-heading="notification.title"
                :time-date="notification.updated_at"
                :category-text="notification.type"
                :click-callback="() => handleClickNotification(notification.id,notification.resource_id, notification.type,notification.title )"
            />
        </div>
    </div>
    <div
        v-else
        class="loader"
    >
        <Loader
            loader-type="small"
            loader-message="Fetching your notifications"
        />
    </div>
</template>

<style scoped>

</style>
