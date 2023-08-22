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
    <div class="absolute h-12 hidden w-12  lg:!right-48 lg:!top-52 lg:block xl:!right-72 xl:!top-56">
        <div
            class="bg-slate-200 cursor-pointer flex h-full overflow-hidden relative rounded-full w-full z-50 hover:shadow-2xl"
            @click.prevent="handleAvatar"
        >
            <img
                v-if="!avatarUrl.length <= 0"
                class="m-auto w-full"
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
            v-show="profileDropdown"
            class="h-full relative w-full z-50"
            @mouseleave="handleAvatar"
        >
            <div class="absolute -top-6 left-[24px] bg-[#637D99] flex flex-col px-4 py-6 shadow-lg w-[240px] z-50 z-50">
                <div class="border-b border-white font-bold h-fit pb-3 text-[24px] text-center text-white w-full">
                    <h5>{{ currentUser.full_name }}</h5>
                </div>
                <div class="border-b border-white flex flex-col gap-3 py-3">
                    <router-link :to="`/profile/${currentUser.id}`">
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
                            <Profile />
                            Profile
                        </button>
                    </router-link>
                    <router-link :to="`/message/${currentUser.id}`">
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
                            <Profile />
                            Messages
                        </button>
                    </router-link>

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
                        <Profile />
                        Help
                    </button>
                    <template
                        v-if="isAdmin"
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
                            @click="handleClickAdmin"
                        >
                            <Profile />
                            Admin
                        </button>
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
