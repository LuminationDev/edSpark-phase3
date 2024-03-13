<script setup lang="ts">

import {storeToRefs} from "pinia";
import {computed, onMounted, ref, watch, watchEffect} from "vue";

import ProfileDropdownItem from "@/js/components/global/ProfileDropdownItem.vue";
import NotificationIcon from "@/js/components/svg/NotificationIcon.vue";
import Profile from "@/js/components/svg/Profile.vue";
import AdminIcon from "@/js/components/svg/profileDropdown/AdminIcon.vue";
import CreateIcon from "@/js/components/svg/profileDropdown/CreateIcon.vue";
import MessageIcon from "@/js/components/svg/profileDropdown/MessageIcon.vue";
import SchoolGradHat from "@/js/components/svg/SchoolGradHat.vue";
import {APP_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {avatarUIFallbackURL} from "@/js/constants/serverUrl";
import {notificationService} from "@/js/service/notificationService";
import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({
    avatarUrl: {
        type: String,
        required: true,
    },
});

const notificationsNumber = ref(0)
const userStore = useUserStore();
const {currentUser} = storeToRefs(userStore);
const showDropdownMenu = ref(false)

const imageURL = import.meta.env.VITE_SERVER_IMAGE_API;
const imageError = ref(false)
const toggleDropdownMenu = (): void => {
    showDropdownMenu.value = !showDropdownMenu.value
}

const handleLogoutUser = async () => {
    try {
        const response = await fetch(APP_ENDPOINTS.LOGOUT, {
            method: 'POST',
        });
        // Handle the response from the server
        const data = await response.json();
        console.log(data.message);

        userStore.clearStore();

        window.location.href = '/';

    } catch (error) {
        console.error('Logout failed: ', error);
    }
};

const handleClickAdmin = () => {
    window.open(window.location.origin + '/admin', '_self')
}

const profileTargetPath = computed(() => {
    if (currentUser.value.id) {
        return `/profile/${currentUser.value.id}`
    } else return ''
})

const messageTargetPath = computed(() => {
    if (currentUser.value.id) {
        // return `/message/${currentUser.value.id}`
        return `/profile/${currentUser.value.id}/messages`
    } else return ''
})

const mySchoolTargetPath = computed(() => {
    if (currentUser.value?.site?.site_name) {
        return `/schools/${currentUser.value.site.site_name}`
    } else return ''
})

const avatarUrlWithFallback  = computed(() =>{
    if(imageError.value){
        return avatarUIFallbackURL + currentUser.value.display_name
    } else{
        return `${imageURL}/${props.avatarUrl}`
    }
})

const handleImageLoadError = () => {
    imageError.value = true
}

const fetchNotificationsAndUpdate = async () => {
    try {
        const res = await notificationService.getAllNotifications(currentUser.value.id);
        const unreadNotification = res.data.data.filter(notification => !notification.read_at);
        notificationsNumber.value = unreadNotification.length;
    } catch (error) {
        console.error('Error fetching notifications:', error);
    }
};

onMounted(fetchNotificationsAndUpdate);





</script>

<template>
    <div class="absolute h-12 hidden w-12 lg:!right-5 lg:!top-4 lg:block xl:!right-5 xl:!top-4">
        <div
            v-show="notificationsNumber!==0"
            class="absolute bottom-8 left-8 bg-red-700 h-4 rounded-full w-4 z-50"
        />
        <div
            class="bg-slate-200 cursor-pointer flex h-full overflow-hidden relative rounded-full w-full z-40 hover:shadow-2xl"
            @click="toggleDropdownMenu"
        >
            <img
                class="object-center object-cover w-full"
                :src="avatarUrlWithFallback"
                alt="profile picture"
                @error="handleImageLoadError"
            >
        </div>
        <div
            v-show="showDropdownMenu"
            class="h-full relative w-full z-50"
            @mouseleave="toggleDropdownMenu"
        >
            <div
                class="
                    absolute
                    top-2
                    -right-5
                    bg-white
                    border-[1px]
                    border-slate-300
                    flex
                    flex-col
                    px-4
                    py-6
                    rounded-lg
                    shadow-lg
                    text-main-darkGrey
                    w-[240px]
                    z-50
                    "
            >
                <div class="border-b border-white font-bold h-fit pb-3 text-center w-full">
                    <h5>{{ currentUser.full_name }}</h5>
                </div>
                <div class="border-b-2 border-slate-100 flex flex-col py-3  gap-3">
                    <ProfileDropdownItem
                        :is-router-link="true"
                        target-path="/notifications"
                    >
                        <NotificationIcon />
                        Notification
                        <div
                            id="notification-element"
                            class="bg-red-700 h-7 ml-auto notification-element rounded-2xl w-7"
                        >
                            <div class="text-white">
                                {{ notificationsNumber }}
                            </div>
                        </div>
                    </ProfileDropdownItem>
                    <ProfileDropdownItem
                        v-if="profileTargetPath"
                        :is-router-link="true"
                        :target-path="profileTargetPath"
                    >
                        <Profile />
                        Profile
                    </ProfileDropdownItem>
                    <ProfileDropdownItem
                        v-if="messageTargetPath"
                        :is-router-link="true"
                        :target-path="messageTargetPath"
                    >
                        <MessageIcon />
                        Messages
                    </ProfileDropdownItem>
                    <ProfileDropdownItem
                        :is-router-link="true"
                        target-path="/create"
                    >
                        <CreateIcon />
                        Create
                    </ProfileDropdownItem>
                    <ProfileDropdownItem
                        v-if="mySchoolTargetPath"
                        :is-router-link="true"
                        :target-path="mySchoolTargetPath"
                    >
                        <SchoolGradHat />
                        My school
                    </ProfileDropdownItem>
                    <template
                        v-if="userStore.getIfUserIsModerator"
                    >
                        <ProfileDropdownItem
                            :is-router-link="false"
                            :click-callback="handleClickAdmin"
                        >
                            <AdminIcon />
                            Admin
                        </ProfileDropdownItem>
                    </template>
                </div>
                <div class="pt-3">
                    <button
                        class="
                            font-medium
                            px-2
                            py-3
                            text-[18px]
                            hover:text-white
                            text-main-darkGrey
                            w-full
                            hover:bg-main-teal
                            hover:rounded-lg
                            hover:stroke-white
                            "
                        @click.prevent="handleLogoutUser"
                    >
                        Sign out
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
