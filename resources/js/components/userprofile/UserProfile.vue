<script setup>
import { ref, computed } from 'vue';
import { useUserStore } from '../../stores/useUserStore';
import { useSiteStore } from '../../stores/useSiteStore';
import {storeToRefs} from 'pinia'
import Save from '../svg/Save.vue';
import Close from '../svg/Close.vue';
import Edit from '../svg/Edit.vue';
import UserProfileSubmenu from "./UserProfileSubmenu.vue";

console.log('nside useprofile new')
const userStore = useUserStore();
const siteStore = useSiteStore();

const isEditAvatar = ref(false);

const updatedName = ref('');

const handleSaveChange = () => {
    console.log('Handle save values here');
    userStore.updateUserName(updatedName.value);
};
const { currentUser, notifications } = storeToRefs(userStore)

const userAvatar = ref(null)

const activeProfileField = ref('Info')
const activeInfoItem = ref('Biography')
const updatedYearLevel = ref(0)
const editIndex = ref(null)

const subMenuItems = ref(['Info', 'Work', 'Messages'])
const editingField = ref(false)

const handleSelectSubmenu = () => {
    console.log('submenu selected')
}

const editName = ref(null);

function editField() {
    editingField.value = true;
    // TODO: make this not rely on a timeout for component to render
    setTimeout(() => {
        editName.value.focus();
    }, 100);
}

function handleCancelEdit() {
    editingField.value = false;
}

function fetchUserSite(siteId) {
    return siteStore.getSiteById(siteId)
}


const imageURL = import.meta.env.VITE_SERVER_IMAGE_API;
const avatarUrl = ref('');
const userMetadata = userStore.getUser.metadata;
if (userMetadata !== undefined) {
    const userAvatarMeta = userMetadata.filter(meta => meta.user_meta_key === 'userAvatar');
    avatarUrl.value = userAvatarMeta[0].user_meta_value[0].replace(/\\\//g, "/");
}

console.log(imageURL + '/' + avatarUrl.value);

</script>
<template>
    <div class="h-full w-full bg-white">
        <div class="w-full flex flex-col">
            <div class="col-span-12 row-span-2 grid grid-cols-6 justify-center mt-[100px] px-[81px]">
                <div
                    class="col-span-1 cursor-pointer h-[200px] w-[200px] bg-orange-500 rounded-full flex justify-center place-items-center relative"
                    :class="!avatarUrl.length <= 0 ? `bg-[url(${imageURL}/${avatarUrl})]` : ''"
                    @mouseenter="isEditAvatar = !isEditAvatar"
                    @mouseleave="isEditAvatar = !isEditAvatar"
                >
                    <h1 class="text-[72px] text-white font-bold" v-if="!avatarUrl.length > 0">
                        {{ currentUser.display_name }}
                    </h1>
                    <div
                        v-if="isEditAvatar"
                        class="absolute w-[48px] h-[48px] rounded-full bg-white flex justify-center place-items-center top-0 right-[24px] border border-black border-2"
                    >
                        <Edit class="w-[24px] h-[24px]" />
                    </div>
                </div>

                <div class="col-span-5 grid grid-rows-4 grid-cols-1">
                    <div>
                        <!--updateField-->
                        <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">
                            <input
                                v-if="editingField"
                                ref="editName"
                                v-model="updatedName"
                                class="!w-[320px]"
                                type="text"
                                :placeholder="currentUser.full_name"
                            >
                        </div>
                        <!--fieldName-->
                        <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">
                            <p
                                ref="beforeRenderInput"
                                class="text-[24px] font-bold"
                                for="name"
                            >
                                Name:
                            </p>
                        </div>
                        <!--currentDetails-->
                        <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">
                            <div
                                v-if="!editingField"
                                class="group"
                            >
                                <button
                                    class="flex flex-row gap-4 place-items-center"
                                    @click.prevent="editField"
                                >
                                    <p
                                        v-if="!editingField"
                                        class="text-[24px] font-normal group-hover:underline"
                                    >
                                        {{ currentUser['full_name'] }}
                                    </p>
                                    <Edit
                                        v-if="!editingField"
                                        class="h-[18px] group-hover:scale-110"
                                    />
                                </button>
                            </div>
                        </div>
                        <!--saveChanges-->
                        <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">
                            <button
                                class="w-fit p-2 hover:bg-gray-200"
                                @click.prevent="handleSaveChange"
                            >
                                <Save
                                    v-if="editingField"

                                    class="h-[24px] w-[24px]"
                                />
                            </button>
                        </div>
                        <!--cancelUpdate-->
                        <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">
                            <button
                                class="w-fit p-2 hover:bg-gray-200"
                                @click.prevent="handleCancelEdit"
                            >
                                <Close
                                    v-if="editingField"
                                    class="h-[24px] w-[24px]"
                                />
                            </button>
                        </div>
                    </div>

                    <div>
                        <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">
                            <p
                                ref="beforeRenderInput"
                                class="text-[24px] font-bold"
                                for="name"
                            >
                                Email:
                            </p>
                            <p class="text-[24px] font-normal group-hover:underline">
                                {{ currentUser.email }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">
                            <p
                                ref="beforeRenderInput"
                                class="text-[24px] font-bold"
                                for="name"
                            >
                                Role:
                            </p>
                            <p class="text-[24px] font-normal group-hover:underline">
                                {{ currentUser.role }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">
                            <p
                                ref="beforeRenderInput"
                                class="text-[24px] font-bold"
                                for="name"
                            >
                                Site:
                            </p>
                            <p class="text-[24px] font-normal group-hover:underline">
                                {{ fetchUserSite(currentUser.site_id) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 row-sapn-4 mx-20">
                <UserProfileSubmenu :submenu-items="subMenuItems" />
                <router-view />
            </div>
        </div>
    </div>
</template>

