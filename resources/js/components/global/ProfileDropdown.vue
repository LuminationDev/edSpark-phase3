<script setup lang="ts">

import {storeToRefs} from "pinia";
import {ref} from "vue";

import ProfileDropdownItem from "@/js/components/global/ProfileDropdownItem.vue";
import Profile from "@/js/components/svg/Profile.vue";
import AdminIcon from "@/js/components/svg/profileDropdown/AdminIcon.vue";
import CreateIcon from "@/js/components/svg/profileDropdown/CreateIcon.vue";
import MessageIcon from "@/js/components/svg/profileDropdown/MessageIcon.vue";
import SchoolGradHat from "@/js/components/svg/SchoolGradHat.vue";
import {APP_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({
    avatarUrl: {
        type: String,
        required: true,
    },
});


const userStore = useUserStore();
const {currentUser} = storeToRefs(userStore);
const showDropdownMenu = ref(false)

const imageURL = import.meta.env.VITE_SERVER_IMAGE_API;

const toggleDropdownMenu = () : void => {
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
</script>

<template>
    <div class="absolute h-12 hidden w-12  lg:!right-48 lg:!top-52 lg:block xl:!right-72 xl:!top-56">
        <div
            class="bg-slate-200 cursor-pointer flex h-full overflow-hidden relative rounded-full w-full z-50 hover:shadow-2xl"
            @click="toggleDropdownMenu"
        >
            <img
                v-if="avatarUrl"
                class="object-center object-cover w-full"
                :src="`${imageURL}/${avatarUrl}`"
                alt=""
            >
            <p
                v-else
                class="font-bold m-auto text-[1.25rem] text-white"
            >
                {{ currentUser.display_name }}
            </p>
        </div>

        <div
            v-show="showDropdownMenu"
            class="h-full relative w-full z-50"
            @mouseleave="toggleDropdownMenu"
        >
            <div class="absolute -top-6 left-[24px] bg-[#637D99] flex flex-col px-4 py-6 shadow-lg w-[240px] z-50 z-50">
                <div class="border-b border-white font-bold h-fit pb-3 text-[24px] text-center text-white w-full">
                    <h5>{{ currentUser.full_name }}</h5>
                </div>
                <div class="border-b border-white flex flex-col gap-3 py-3">
                    <ProfileDropdownItem
                        :is-router-link="true"
                        :target-path="`/profile/${currentUser.id}`"
                    >
                        <Profile />
                        Profile
                    </ProfileDropdownItem>
                    <ProfileDropdownItem
                        :is-router-link="true"
                        :target-path="`/message/${currentUser.id}`"
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
                    <!--                    <ProfileDropdownItem-->
                    <!--                        :is-router-link="true"-->
                    <!--                        :target-path="`/schools/${currentUser.site.site_name}`"-->
                    <!--                    >-->
                    <!--                        <SchoolGradHat />-->
                    <!--                        My School-->
                    <!--                    </ProfileDropdownItem>-->
                    <template
                        v-if="userStore.getIfUserIsAdmin"
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
                        class="font-medium px-2 py-3 text-[18px] text-white w-full hover:underline"
                        @click.prevent="handleLogoutUser"
                    >
                        Sign out
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
