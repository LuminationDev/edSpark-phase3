<script>
    /**
     * Import Dependencies
     */
    import { ref } from 'vue';
    /**
     * Import SVG's
     */
import Profile from '../svg/Profile.vue';

/**
 * Import stores
 */
import { useUserStore } from '../../stores/useUserStore';

/**
 * Import components
 */

export default {

    components: {
        Profile,
    },
    props: {
        currentUser: Object,
        profileDropdown: Boolean
    },

    setup() {
        const userStore = useUserStore();

        return {
            userStore
        }
    },

    methods: {
        handleAvatar() {
            this.$emit('handleAvatarClick');
        },

        setup() {
            const userStore = useUserStore();

            const avatarUrl = ref(null);

            const imageURL = import.meta.env.VITE_SERVER_IMAGE_API;
            const userMetadata = userStore.getUser.metadata;
            if (userMetadata !== undefined) {
                const userAvatarMeta = userMetadata.filter(meta => meta.user_meta_key === 'userAvatar');
                avatarUrl.value = userAvatarMeta[0].user_meta_value[0].replace(/\\\//g, "/");
            }

            console.log(avatarUrl);

            return {
                userStore,
                imageURL,
                avatarUrl
            }
        },

        components: {
            Profile,
        },

        methods: {
            handleAvatar() {
                console.log(this.profileDropdown);
                this.$emit('handleAvatarClick');
            },

            async logout () {
                await this.$auth.signOut();
                this.userStore.clearStore();
            }
        },
    }
}
</script>

<template>
    <div class="w-[48px] h-[48px] absolute top-64 right-96">
        <div
            class="z-50 relative h-full w-full bg-orange-500 flex rounded-full cursor-pointer hover:shadow-2xl"
            @click.prevent="handleAvatar"
        >
            <p class="text-[1.25rem] text-white font-bold m-auto">
                {{ currentUser.display_name }}
            </p>
        </div>

        <div
            v-show="profileDropdown"
            class="relative w-full h-full z-40"
            @mouseleave="handleAvatar"
        >
            <div class="absolute py-6 px-4 -top-6 left-[24px] z-50 w-[240px] h-[350px] bg-[#637D99] flex flex-col shadow-lg">
                <div class="w-full h-fit text-white text-[24px] font-bold text-center border-b border-white pb-3">
                    <h5>{{ currentUser.full_name }}</h5>
                </div>
                <div class="flex flex-col gap-3 py-3 border-b border-white">
                    <router-link :to="`/profile/${currentUser.id}`">
                        <button class="flex flex-row gap-4 justify-start py-3 px-2 text-white text-[18px] font-medium place-items-center hover:bg-[#405974] w-full">
                            <Profile />
                            Profile
                        </button>
                    </router-link>
                    <button class="flex flex-row gap-4 justify-start py-3 px-2 text-white text-[18px] font-medium place-items-center hover:bg-[#405974] w-full">
                        <Profile />
                        Messages
                    </button>

                    <button class="flex flex-row gap-4 justify-start py-3 px-2 text-white text-[18px] font-medium place-items-center hover:bg-[#405974] w-full">
                        <Profile />
                        Help
                    </button>
                </div>
                <div class="pt-3">
                    <button
                        class="py-3 px-2 text-white text-[18px] font-medium w-full hover:underline"
                        @click.prevent="logout"
                    >
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
