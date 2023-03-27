<script>
    /**
     * Import Dependencies
     */
import { ref } from 'vue';

/**
 * Import SVG's
 */
import NavSwoosh from '../svg/NavSwoosh.vue';
import Logo from '../svg/Logo.vue';
import Profile from '../svg/Profile.vue';

/**
 * Import Stores
 */
import { useUserStore } from '@/js/stores/useUserStore';

/**
 * Import Components
 */
import ProfileDropdown from './ProfileDropdown.vue';

export default {

    components: {
        NavSwoosh,
        Logo,
        Profile,
        ProfileDropdown
    },
    props: {
        isFirstVisit: Boolean
    },

    setup() {
        const userStore = useUserStore();

        const navDropdownToggle = ref(false);
        const profileDropdown = ref(false);

        return {
            navDropdownToggle,
            profileDropdown,
            userStore,
        }
    },

    data() {
        return {
            currentUser: {}
        }
    },

    mounted() {
        this.currentUser = this.userStore.getUser
    },

    methods: {
        handleAvatarClick() {
            console.log('This has been clicked!!!');
            this.profileDropdown = !this.profileDropdown
        }
    }
}

</script>

<template>
    <div class="w-full h-[240px] relative">
        <div class="nav-background w-full h-full pt-7 bg-[url(http://localhost:5173/resources/assets/images/children-vr.png)] bg-no-repeat bg-cover">
            <nav class="bg-[#002856]/50 py-2 px-12 w-full">
                <ul class="flex flex-wrap gap-8 text-white text-[24px] font-semibold font-['Poppins']">
                    <RouterLink to="/dashboard">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Dash
                        </li>
                    </RouterLink>

                    <RouterLink to="/schools">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Schools
                        </li>
                    </RouterLink>

                    <RouterLink to="/advice">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Advice
                        </li>
                    </RouterLink>
                    <li class="relative cursor-pointer">
                        <div
                            class="h-fit"
                            @mouseover="navDropdownToggle = true"
                            @mouseleave="navDropdownToggle = false"
                        >
                            Technology
                            <div
                                v-show="navDropdownToggle"
                                class="navDropdown absolute  "
                                @mouseover="navDropdownToggle = true"
                            >
                                <div class="bg-[#002856]/50 mt-[8px]">
                                    <ul class="flex flex-col gap-4 py-4 text-white text-center text-[24px] font-semibold font-['Poppins']">
                                        <RouterLink
                                            class="flex"
                                            to="/software"
                                        >
                                            <li class="px-4 mx-auto cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                                                Software
                                            </li>
                                        </RouterLink>
                                        <RouterLink to="/hardware">
                                            <li class="px-4 mx-auto cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                                                Hardware
                                            </li>
                                        </RouterLink>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>


                    <RouterLink to="/community">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Community
                        </li>
                    </RouterLink>

                    <RouterLink to="/partners">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Partners
                        </li>
                    </RouterLink>

                    <RouterLink to="/events">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Events
                        </li>
                    </RouterLink>
                </ul>
            </nav>
        </div>
        <!-- Hover Settings for Profile Dropdown -->
        <!-- @mouseenter="this.profileDropdown = true " @mouseleave="this.profileDropdown = false" -->
        <!-- <div @click.prevent="handleAvatar" class="w-fit h-fit" >

        </div> -->

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
                            <button class="flex flex-row gap-4 justify-start py-3 px-2 text-white text-[18px] font-medium place-items-center hover:bg-[#405974]">
                                <Profile />
                                Profile
                            </button>
                        </router-link>
                        <button class="flex flex-row gap-4 justify-start py-3 px-2 text-white text-[18px] font-medium place-items-center hover:bg-[#405974]">
                            <Profile />
                            Messages
                        </button>

                        <button class="flex flex-row gap-4 justify-start py-3 px-2 text-white text-[18px] font-medium place-items-center hover:bg-[#405974]">
                            <Profile />
                            Help
                        </button>
                    </div>
                    <div class="pt-3">
                        <button class="py-3 px-2 text-white text-[18px] font-medium w-full hover:underline">
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <profileDropdown
            :current-user="currentUser"
            :profile-dropdown="profileDropdown"
            @handleAvatarClick="handleAvatarClick"
        />

        <Logo class="absolute right-20 top-4 z-30 md:w-48 md:h-48 sm:w-36 sm:w-36" />
        <NavSwoosh class="w-full absolute -bottom-6 left-0 right-0 pointer-events-none" />
    </div>
</template>

<style>
    .nav-background {
        clip-path: inset(0 0 round 0 0 75%);
    }

    .navDropdown {
        width: 170px;
        left: 50%;
        transform: translateX(-50%);
    }
</style>
