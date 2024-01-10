<script setup lang="ts">

import {storeToRefs} from "pinia";
import {computed, ref} from "vue";

import NavItemsMobileMenu from "@/js/components/global/navbar/NavItemsMobileMenu.vue";
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
    isRouterLink: {
        type: Boolean,
        required: true
    },
    targetPath: {
        type: String,
        required: false,
        default: '/'
    },
    route: {
        type: Object,
        required: true,
    },
    clickCallback: {
        type: Function,
        required: false,
        default: () => {
        },
    },
});
const navDropdownOpen = ref(false);
const handleDropdownToggle = () => {
    navDropdownOpen.value = !navDropdownOpen.value;
};

const userStore = useUserStore();
const {currentUser} = storeToRefs(userStore);
const showDropdownMenu = ref(false)

const imageURL = import.meta.env.VITE_SERVER_IMAGE_API;

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
        return `/message/${currentUser.value.id}`
    } else return ''
})
const mySchoolTargetPath = computed(() => {
    if (currentUser.value?.site?.site_name) {
        return `/schools/${currentUser.value.site.site_name}`
    } else return ''
})

</script>

<template>
    <li
        class="
            cursor-pointer
            decoration-4
            decoration-[#B8E2DC]
            first-letter:uppercase
            flex
            justify-between
            items-center
            h-full
            mr-3
            py-4
            relative
            transition-all
            lg:!py-0
            "
        @click="clickCallback"
    >
        <div class="absolute h-12 w-12">
            <div
                class="bg-slate-200 cursor-pointer flex h-full relative rounded-full w-full z-50 hover:shadow-2xl"
                @click="clickCallback"
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
            >
                <div class="absolute -top-6 left-[24px] bg-[#637D99] flex flex-col px-4 py-6 shadow-lg w-[240px] z-50 z-50">
                    <div class="border-b border-white font-bold h-fit pb-3 text-[24px] text-center text-white w-full">
                        <h5>{{ currentUser.full_name }}</h5>
                    </div>
                    <div class="border-b border-white flex flex-col gap-3 py-3">
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
                            My School
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
                            class="font-medium px-2 py-3 text-[18px] text-white w-full hover:underline"
                            @click.prevent="handleLogoutUser"
                        >
                            Sign out
                        </button>
                    </div>
                </div>
            </div>
            <router-link
                v-if="props.isRouterLink"
                :to="props.targetPath"
            >
                <button
                    class="
                        flex
                        justify-start
                        flex-row
                        font-medium
                        place-items-center
                        px-2
                        py-3
                        text-[18px]
                        text-white
                        w-full
                        gap-4
                        hover:bg-[#405974]
                        "
                >
                    <slot />
                </button>
            </router-link>
            <button
                v-else
                class="
                    flex
                    justify-start
                    flex-row
                    font-medium
                    place-items-center
                    px-2
                    py-3
                    text-[18px]
                    text-white
                    w-full
                    gap-4
                    hover:bg-[#405974]
                    "
                @click="props.clickCallback"
            >
                <slot />
            </button>
        </div>
    </li>
</template>
