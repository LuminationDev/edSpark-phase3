<script setup>
import {ref, computed, onBeforeMount} from 'vue';
import {useUserStore} from '../../stores/useUserStore';
import {useSiteStore} from '../../stores/useSiteStore';
import {storeToRefs} from 'pinia'
import Save from '../svg/Save.vue';
import Close from '../svg/Close.vue';
import Edit from '../svg/Edit.vue';
import UserProfileSubmenu from "./UserProfileSubmenu.vue";
import {serverURL} from "@/js/constants/serverUrl";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import UserBookmark from "@/js/components/userprofile/UserBookmark.vue";

console.log('nside useprofile new')
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


const handleSelectSubmenu = () => {
    console.log('submenu selected')
}

function handleCancelEdit() {
    editingField.value = false;
}

function fetchUserSite(siteId) {
    return siteStore.getSiteById(siteId)
}

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
        break;
    default:
        return "edSpark User"
    }

})

onBeforeMount(() => {
    let userData = {
        user_id: currentUser.value.id
    }
    axios.post(`${serverURL}/fetchAllBookmarksWithTitle`, userData).then(res => {
        console.log(res.data)
        userBookmarks.value = res.data
    }).catch(err => {
        console.log('not successfully fetch bookmarkdata')
    })
})

</script>
<template>
    <div class="UserProfilePage h-full w-full ">
        <div
            class="profileCoverImage h-[19vh] w-full top-0 left-0 bg-cover bg-bottom"
            :style="`background-image: url(https://i.imgur.com/TBgEy0n.jpeg)`"
        />

        <div class="profileBodyContainer w-full flex flex-col ">
            <div class="ProfilePictureAndDataRow flex flex-row gap-8 px-24">
                <div class="roundProfilePicContainer basis-1/4 flex justify-end">
                    <div
                        class="avatarContainerOverlap -mt-[100px] relative"
                        @mouseenter="isEditAvatar = !isEditAvatar"
                        @mouseleave="isEditAvatar = !isEditAvatar"
                    >
                        <img
                            class="userAvatar cursor-pointer h-[300px] w-[300px] rounded-full border-8 border-white"
                            :class="!avatarUrl.length <= 0 ? `bg-[url(${imageURL}/${avatarUrl})]` : ''"
                            :src="`${imageURL}/${avatarUrl}`"
                            alt="user profile picture"
                        >
                        <div
                            v-show="isEditAvatar"
                            class="absolute w-[48px] h-[48px] rounded-full bg-white flex justify-center items-center top-6 right-[24px] border-black border-2 cursor-pointer hover:bg-slate-200"
                            @click="handleClickEditAvatar"
                        >
                            <Edit class="w-[24px] h-[24px]" />
                            <input
                                ref="uploadAvatar"
                                type="file"
                                class="hidden"
                            >
                        </div>
                    </div>
                </div>
                <div class="userInfoContainer basis-3/4 flex flex-col justify-center items-start bg-white">
                    <div class="UserDisplayName font-semibold text-3xl my-2">
                        {{ currentUser.full_name }}
                    </div>

                    <div class="UserDisplayRole font-base text-lg mb-2">
                        {{ displayUserRole }}
                    </div>
                    <div class="informationRow flex flex-row gap-6">
                        <div class="flex flex-row mb-2 items-center">
                            <div class="text-xl flex justify-center items-center mr-4 pb-2">
                                {{ '🏫' }}
                            </div>
                            <div class="UserDisplaySite font-base text-lg">
                                {{ currentUser.site_id }}
                            </div>
                        </div>
                        <div class="flex flex-row mb-2 items-center">
                            <div class="text-xl flex justify-center items-center mr-4">
                                {{ ' 📧' }}
                            </div>
                            <div class="UserDisplayRole font-base text-lg">
                                {{ currentUser.email }}
                            </div>
                        </div>
                    </div>
                </div>
                <!--                                <div class="col-span-12 row-span-2 grid grid-cols-6 justify-center ">-->
                <!--                                    <div-->
                <!--                                        class="userAvatar col-span-1 cursor-pointer h-[200px] w-[200px] bg-orange-500 rounded-full flex justify-center place-items-center relative"-->
                <!--                                        :class="!avatarUrl.length <= 0 ? `bg-[url(${imageURL}/${avatarUrl})]` : ''"-->
                <!--                                        @mouseenter="isEditAvatar = !isEditAvatar"-->
                <!--                                        @mouseleave="isEditAvatar = !isEditAvatar"-->
                <!--                                    >-->
                <!--                                        <h1-->
                <!--                                            v-if="!avatarUrl.length > 0"-->
                <!--                                            class="text-[72px] text-white font-bold"-->
                <!--                                        >-->
                <!--                                            {{ currentUser.display_name }}-->
                <!--                                        </h1>-->
                <!--                                                        <div-->
                <!--                                                            v-if="isEditAvatar"-->
                <!--                                                            class="absolute w-[48px] h-[48px] rounded-full bg-white flex justify-center place-items-center top-0 right-[24px] border border-black border-2"-->
                <!--                                                        >-->
                <!--                                                            <Edit class="w-[24px] h-[24px]" />-->
                <!--                                                        </div>-->
                <!--                                    </div>-->

                <!--                                    <div class="col-span-5 grid grid-rows-4 grid-cols-1">-->
                <!--                                        <div>-->
                <!--                                            &lt;!&ndash;updateField&ndash;&gt;-->
                <!--                                            <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">-->
                <!--                                                <input-->
                <!--                                                    v-if="editingField"-->
                <!--                                                    ref="editName"-->
                <!--                                                    v-model="updatedName"-->
                <!--                                                    class="!w-[320px]"-->
                <!--                                                    type="text"-->
                <!--                                                    :placeholder="currentUser.full_name"-->
                <!--                                                >-->
                <!--                                            </div>-->
                <!--                                            &lt;!&ndash;fieldName&ndash;&gt;-->
                <!--                                            <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">-->
                <!--                                                <p-->
                <!--                                                    ref="beforeRenderInput"-->
                <!--                                                    class="text-[24px] font-semibold"-->
                <!--                                                    for="name"-->
                <!--                                                >-->
                <!--                                                    Name:-->
                <!--                                                </p>-->
                <!--                                            </div>-->
                <!--                                            &lt;!&ndash;currentDetails&ndash;&gt;-->
                <!--                                            <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">-->
                <!--                                                <div-->
                <!--                                                    v-if="!editingField"-->
                <!--                                                    class="group"-->
                <!--                                                >-->
                <!--                                                    <button-->
                <!--                                                        class="flex flex-row gap-4 place-items-center"-->
                <!--                                                        @click.prevent="editField"-->
                <!--                                                    >-->
                <!--                                                        <p-->
                <!--                                                            v-if="!editingField"-->
                <!--                                                            class="text-[24px] font-normal group-hover:underline"-->
                <!--                                                        >-->
                <!--                                                            {{ currentUser['full_name'] }}-->
                <!--                                                        </p>-->
                <!--                                                        <Edit-->
                <!--                                                            v-if="!editingField"-->
                <!--                                                            class="h-[18px] group-hover:scale-110"-->
                <!--                                                        />-->
                <!--                                                    </button>-->
                <!--                                                </div>-->
                <!--                                            </div>-->
                <!--                                            &lt;!&ndash;saveChanges&ndash;&gt;-->
                <!--                                            <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">-->
                <!--                                                <button-->
                <!--                                                    class="w-fit p-2 hover:bg-gray-200"-->
                <!--                                                    @click.prevent="handleSaveChange"-->
                <!--                                                >-->
                <!--                                                    <Save-->
                <!--                                                        v-if="editingField"-->

                <!--                                                        class="h-[24px] w-[24px]"-->
                <!--                                                    />-->
                <!--                                                </button>-->
                <!--                                            </div>-->
                <!--                                            &lt;!&ndash;cancelUpdate&ndash;&gt;-->
                <!--                                            <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">-->
                <!--                                                <button-->
                <!--                                                    class="w-fit p-2 hover:bg-gray-200"-->
                <!--                                                    @click.prevent="handleCancelEdit"-->
                <!--                                                >-->
                <!--                                                    <Close-->
                <!--                                                        v-if="editingField"-->
                <!--                                                        class="h-[24px] w-[24px]"-->
                <!--                                                    />-->
                <!--                                                </button>-->
                <!--                                            </div>-->
                <!--                                        </div>-->

                <!--                                        <div>-->
                <!--                                            <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">-->
                <!--                                                <p-->
                <!--                                                    ref="beforeRenderInput"-->
                <!--                                                    class="text-[24px] font-semibold"-->
                <!--                                                    for="name"-->
                <!--                                                >-->
                <!--                                                    Email:-->
                <!--                                                </p>-->
                <!--                                                <p class="text-[24px] font-normal group-hover:underline">-->
                <!--                                                    {{ currentUser.email }}-->
                <!--                                                </p>-->
                <!--                                            </div>-->
                <!--                                        </div>-->

                <!--                                        <div>-->
                <!--                                            <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">-->
                <!--                                                <p-->
                <!--                                                    ref="beforeRenderInput"-->
                <!--                                                    class="text-[24px] font-semibold"-->
                <!--                                                    for="name"-->
                <!--                                                >-->
                <!--                                                    Role:-->
                <!--                                                </p>-->
                <!--                                                <p class="text-[24px] font-normal group-hover:underline">-->
                <!--                                                    {{ currentUser.role }}-->
                <!--                                                </p>-->
                <!--                                            </div>-->
                <!--                                        </div>-->

                <!--                                        <div>-->
                <!--                                            <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">-->
                <!--                                                <p-->
                <!--                                                    ref="beforeRenderInput"-->
                <!--                                                    class="text-[24px] font-semibold"-->
                <!--                                                    for="name"-->
                <!--                                                >-->
                <!--                                                    Site:-->
                <!--                                                </p>-->
                <!--                                                <p class="text-[24px] font-normal group-hover:underline">-->
                <!--                                                    {{ fetchUserSite(currentUser.site_id) }}-->
                <!--                                                </p>-->
                <!--                                            </div>-->
                <!--                                        </div>-->
                <!--                                    </div>-->
                <!--                                </div>-->

                <!--                                <div class="col-span-12 row-sapn-4 mx-20">-->
                <!--                                    <UserProfileSubmenu :submenu-items="subMenuItems" />-->
                <!--                                    <router-view />-->
                <!--                                </div>-->
            </div>
            <div class="flex flex-col  min-h-[70vh] mt-10 bg-slate-50">
                <div class="profileSubmenuContainer flex flex-col mt-20 px-24">
                    <UserProfileSubmenu :submenu-items="subMenuItems" />
                    <router-view />
                </div>
                <div class="UserBookmarkListContainer flex flex-col px-24 pt-12">
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
