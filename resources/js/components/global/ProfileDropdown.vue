<script setup>
/**
 * Import Dependencies
 */
import {ref, onMounted} from "vue";
/**
 * Import SVG's
 */
import Profile from "../svg/Profile.vue";

/**
 * Import stores
 */
import { useUserStore } from "@/js/stores/useUserStore";
import { storeToRefs } from "pinia";
import axios from "axios";
/**
 * Import components
 */

const props = defineProps({
    // currentUser: {
    //     type: Object,
    //     required: true,
    // },
    profileDropdown: {
        type: Boolean,
        required: true,
    },
    avatarUrl: {
        type: String,
        required: true,
    },
});

const emits = defineEmits(["handleAvatarClick"]);

const userStore = useUserStore();
const { currentUser } = storeToRefs(userStore);
const imageURL = import.meta.env.VITE_SERVER_IMAGE_API;
const userMetadata = userStore.getUser.metadata;
import { appURL } from "@/js/constants/serverUrl";


//commented for now
// if (userMetadata !== undefined) {
//     const userAvatarMeta = userMetadata.filter(meta => meta.user_meta_key === 'userAvatar');
//     console.log(userAvatarMeta);
//     avatarUrl.value = userAvatarMeta[0].user_meta_value[0].replace(/\\\//g, "/");
// };

const notificationCount = userStore.getNotifications;

const handleAvatar = () => {
    emits("handleAvatarClick");
};

const handleLogoutUser = async () => {
    // console.log("clicked the button");
    // // await oktaAuth.signOut(); //old but working code if okta is done on vue end

    // // Send a request to laravel logout route
    // await axios.post('/logout');
    // this.userStore.clearStore();

    // // Redirect the user to the login page or perform any other necessary actions
    // window.location.href = '/';

    // Call a logout API endpoint in laravel backend
    try {
        const response = await fetch(`${appURL}/logout`, {
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

const isAdmin = ref(false);
const checkUserRole = () => {
    let userRole = userStore.getUser.role;
    switch (userRole) {
    case "Administrator":
    case "Moderator":
    case "PSACT":
    case "SCHLDR":
        isAdmin.value = true;
        break;
    default:
        isAdmin.value = false;
        break;
    }
};
onMounted(() => {
    checkUserRole();
})

const handleClickAdmin = () =>{
    window.open(window.location.origin + '/admin','_self')
}
</script>

<template>
    <div class="w-[48px] h-[48px] absolute top-56 right-72">
        <div class="z-50 relative h-full w-full bg-slate-200 flex rounded-full cursor-pointer hover:shadow-2xl overflow-hidden"
            @click.prevent="handleAvatar">
            <img v-if="!avatarUrl.length <= 0" class="w-full m-auto" :src="`${imageURL}/${avatarUrl}`" alt="" />
            <p v-else class="text-[1.25rem] text-white font-bold m-auto">

                {{ currentUser.display_name }}
            </p>
        </div>

        <div
            v-show="profileDropdown"
            class="relative w-full h-full z-50"
            @mouseleave="handleAvatar"
        >
            <div class="z-50 absolute py-6 px-4 -top-6 left-[24px] z-50 w-[240px] bg-[#637D99] flex flex-col shadow-lg">
                <div class="w-full h-fit text-white text-[24px] font-bold text-center border-b border-white pb-3">
                    <h5>{{ currentUser.full_name }}</h5>
                </div>
                <div class="flex flex-col gap-3 py-3 border-b border-white">
                    <router-link :to="`/profile/${currentUser.id}`">
                        <button
                            class="flex flex-row gap-4 justify-start py-3 px-2 text-white text-[18px] font-medium place-items-center hover:bg-[#405974] w-full"
                        >
                            <Profile />
                            Profile
                        </button>
                    </router-link>
                    <router-link :to="`/message/${currentUser.id}`">
                        <button
                            class="flex flex-row gap-4 justify-start py-3 px-2 text-white text-[18px] font-medium place-items-center hover:bg-[#405974] w-full"
                        >
                            <Profile />
                            Messages
                        </button>
                    </router-link>

                    <button
                        class="flex flex-row gap-4 justify-start py-3 px-2 text-white text-[18px] font-medium place-items-center hover:bg-[#405974] w-full"
                    >
                        <Profile />
                        Help
                    </button>
                    <template
                        v-if="isAdmin"
                    >
                        <button
                            class="flex flex-row gap-4 justify-start py-3 px-2 text-white text-[18px] font-medium place-items-center hover:bg-[#405974] w-full"
                            @click="handleClickAdmin"
                        >
                            <Profile />
                            Admin
                        </button>
                    </template>
                </div>
                <div class="pt-3">
                    <button
                        class="py-3 px-2 text-white text-[18px] font-medium w-full hover:underline"
                        @click.prevent="handleLogoutUser"
                    >
                        Sign out
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
