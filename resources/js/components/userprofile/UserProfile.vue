<script setup>
import {storeToRefs} from 'pinia'
import {computed, onBeforeMount, ref} from 'vue';

import ProfilePlaceholder from '@/assets/images/profilePlaceholder.webp'
import BaseHero from "@/js/components/bases/BaseHero.vue";
import TrixRichEditor from "@/js/components/bases/form/TrixRichEditor.vue";
import Profile from "@/js/components/svg/Profile.vue";
import UserBookmark from "@/js/components/userprofile/UserBookmark.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {serverURL} from "@/js/constants/serverUrl";

import {useSiteStore} from '../../stores/useSiteStore';
import {useUserStore} from '../../stores/useUserStore';
import Close from '../svg/Close.vue';
import Edit from '../svg/Edit.vue';
import Save from '../svg/Save.vue';
import UserProfileSubmenu from "./UserProfileSubmenu.vue";

const userStore = useUserStore();
const siteStore = useSiteStore();

const isEditAvatar = ref(false);

const updatedName = ref('');

const handleSaveChange = () => {
    console.log('Handle save values here');
    userStore.updateUserName(updatedName.value);
};
const {currentUser, notifications} = storeToRefs(userStore)

const userAvatar = ref(null)
const activeProfileField = ref('Info')
const activeInfoItem = ref('Biography')
const updatedYearLevel = ref(0)
const userBookmarks = ref([])
const editIndex = ref(null)
const subMenuItems = ref(['Info', 'Work', 'Messages'])
const editingField = ref(false)
const editName = ref(null);
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
    avatarUrl.value = userAvatarMeta[0].user_meta_value[0].replace(/\\\//g, "/");
}

const displayUserRole = computed(() => {
    switch (currentUser.value.role) {
    case "SCHLDR":
        return "School Principal"
    default:
        return "edSpark User"
    }

})

onBeforeMount(() => {
    const userData = {
        user_id: currentUser.value.id
    }
    axios.post(API_ENDPOINTS.USER.FETCH_ALL_BOOKMARKS_WITH_TITLE, userData).then(res => {
        console.log(res.data)
        userBookmarks.value = res.data
    }).catch(err => {
        console.log('not successfully fetch bookmarkdata')
    })
})

const userAvatarUrl = computed(() => {
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
                                min-w-[200px]
                                rounded-full
                                userAvatar
                                w-[200px]
                                lg:!h-[300px]
                                lg:!min-w-[300px]
                                lg:!w-[300px]
                                "
                            :src="userAvatarUrl"
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
            <div class="bg-slate-50 flex flex-col min-h-[70vh] mt-10 pb-10">
                <div class="flex flex-col mt-20 profileSubmenuContainer px-4  md:!px-8 lg:!px-24">
                    <UserProfileSubmenu :submenu-items="subMenuItems" />
                    <router-view />
                </div>
                <div class="UserBookmarkListContainer flex flex-col pt-12 px-4 md:!px-8 lg:!px-24">
                    <UserBookmark :bookmark-data="userBookmarks.data" />
                </div>
            </div>
        </div>
    </div>
    <TrixRichEditor v-model="contentTemp" />
</template>

<style scoped>
.userAvatar {
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
}
</style>
