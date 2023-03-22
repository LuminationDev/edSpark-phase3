<script>
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

        components: {
            Profile,
        },

        methods: {
            handleAvatar() {
                console.log(this.profileDropdown);
                this.$emit('handleAvatarClick');
            },

            async logout () {
                await this.$auth.signOut()
            }
        },
    }
</script>

<template>
    <div class="w-[48px] h-[48px] absolute top-64 right-96">
        <div @click.prevent="handleAvatar" class="z-50 relative h-full w-full bg-orange-500 flex rounded-full cursor-pointer hover:shadow-2xl">
            <p class="text-[1.25rem] text-white font-bold m-auto">{{ this.currentUser.display_name }}</p>

        </div>

        <div v-show="this.profileDropdown" @mouseleave="handleAvatar" class="relative w-full h-full z-40">
            <div class="absolute py-6 px-4 -top-6 left-[24px] z-50 w-[240px] h-[350px] bg-[#637D99] flex flex-col shadow-lg">
                <div class="w-full h-fit text-white text-[24px] font-bold text-center border-b border-white pb-3">
                    <h5>{{ this.currentUser.full_name }}</h5>
                </div>
                <div class="flex flex-col gap-3 py-3 border-b border-white">
                    <router-link :to="`/profile/${this.currentUser.id}`">
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
                    <button class="py-3 px-2 text-white text-[18px] font-medium w-full hover:underline" @click.prevent="logout">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
