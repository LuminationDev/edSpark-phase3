<script setup>
import {storeToRefs} from 'pinia'
import {computed, ref} from 'vue';

import ProfilePlaceholder from '@/assets/images/profilePlaceholder.webp'
import TrixRichEditorInput from "@/js/components/bases/TrixRichEditorInput.vue";
import UserBookmark from "@/js/components/userprofile/UserBookmark.vue";
import UserInfoEdit from "@/js/components/userprofile/UserInfoEdit.vue";
import {useUserStore} from '@/js/stores/useUserStore';

import Edit from '../svg/Edit.vue';
import UserProfileSubmenu from "./UserProfileSubmenu.vue";

const userStore = useUserStore();

const isEditAvatar = ref(false);


const {currentUser, notifications} = storeToRefs(userStore)

const userAvatar = ref(null)
const userBookmarks = ref([])
const subMenuItems = ref(['Info', 'Work', 'Messages'])
const uploadAvatar = ref(null)
const contentTemp = ref('')


const handleClickEditAvatar = () => {
    uploadAvatar.value.click()
}

const imageURL = import.meta.env.VITE_SERVER_IMAGE_API;
const avatarUrl = ref('');
const userMetadata = userStore.getUser.metadata;
if (userMetadata !== undefined) {
    const userAvatarMeta = userMetadata.filter(meta => meta.user_meta_key === 'userAvatar');
    if (userAvatarMeta && userAvatarMeta.length) {
        avatarUrl.value = userAvatarMeta[0].user_meta_value[0].replace(/\\\//g, "/");

    }
}

const displayUserRole = computed(() => {
    switch (currentUser.value.role) {
    case "SCHLDR":
        return "School Principal"
    default:
        return "edSpark User"
    }

})

const userAvatarUrlWithFallback = computed(() => {
    if (avatarUrl.value) {
        return `${imageURL}/${avatarUrl.value}`
    } else {
        return ProfilePlaceholder
    }
})

const handleErrorAvatarFallback = () => {
    avatarUrl.value = ''
}

</script>
<template>
    <div class="UserProfilePage h-full w-full">
        <div
            class="bg-bottom bg-cover h-[19vh] profileCoverImage relative top-0 left-0 w-full"
        >
            <img
                src="@/assets/images/profile_background.jpeg"
                class="absolute -top-10 left-0 h-full object-cover scale-y-125 w-full"
                alt="static art consist of lines"
            >
        </div>
        <div class="flex flex-col profileBodyContainer w-full">
            <div class="ProfilePictureAndDataRow flex flex-col gap-8 px-8 md:!flex-row lg:!px-24">
                <div class="flex justify-center roundProfilePicContainer w-full md:!basis-1/4 md:!justify-end">
                    <div
                        class="-mt-[100px] avatarContainerOverlap relative"
                        @mouseenter="isEditAvatar = !isEditAvatar"
                        @mouseleave="isEditAvatar = !isEditAvatar"
                    >
                        <img
                            class="
                                border-8
                                border-white
                                cursor-pointer
                                h-[200px]
                                rounded-full
                                userAvatar
                                w-[200px]
                                lg:!h-[300px]
                                lg:!min-w-[300px]
                                lg:!w-[300px]
                                "
                            :src="userAvatarUrlWithFallback"
                            alt="user profile picture"
                            @error="handleErrorAvatarFallback"
                        >
                        <div
                            v-show="isEditAvatar"
                            class="
                                absolute
                                top-6
                                right-[24px]
                                bg-white
                                hover:bg-slate-200
                                border-2
                                border-black
                                cursor-pointer
                                flex
                                justify-center
                                items-center
                                h-[48px]
                                rounded-full
                                w-[48px]
                                "
                            @click="handleClickEditAvatar"
                        >
                            <Edit class="h-[24px] w-[24px]" />
                            <input
                                ref="uploadAvatar"
                                type="file"
                                class="hidden"
                            >
                        </div>
                    </div>
                </div>
                <div class="bg-white flex justify-center items-start flex-col userInfoContainer w-full md:!basis-3/4">
                    <div class="UserDisplayName font-semibold my-2 text-3xl">
                        {{ currentUser.full_name }}
                    </div>

                    <div class="UserDisplayRole font-base mb-2 text-lg">
                        {{ displayUserRole }}
                    </div>
                    <div class="flex flex-col informationRow">
                        <div class="flex items-center flex-row mb-2">
                            <div class="flex justify-center items-center mr-4 pb-2 text-xl">
                                {{ '🏫' }}
                            </div>
                            <div class="UserDisplaySite font-base text-lg">
                                {{ currentUser.site.site_name }}
                            </div>
                        </div>
                        <div class="flex items-center flex-row mb-2">
                            <div class="flex justify-center items-center mr-4 text-xl">
                                {{ ' 📧' }}
                            </div>
                            <div class="UserDisplayRole font-base text-lg">
                                {{ currentUser.email }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 flex flex-col min-h-[70vh] mt-10 pb-10 px-4 md:!px-8 lg:!px-24">
                <div class="flex flex-col mt-20 profileSubmenuContainer">
                    <UserProfileSubmenu :submenu-items="subMenuItems" />
                    <router-view />
                </div>
                <!--                <div class="UserInfoEditForm flex my-10">-->
                <!--                    <UserInfoEdit />-->
                <!--                </div>-->
                <div class="UserBookmarkListContainer flex flex-col pt-12">
                    <UserBookmark :bookmark-data="userBookmarks.data" />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.userAvatar {
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
}
</style>
