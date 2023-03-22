<script>
    /**
     * Import Dependencies
     */
    import { ref } from 'vue';
    import { RouterLink } from 'vue-router';

    /**
     * Import Components
     */
    import ProfileDropdown from './ProfileDropdown.vue';

    /**
     * Import SVG's
     */
    import NavSwoosh from '../svg/NavSwoosh.vue';
    import Logo from '../svg/Logo.vue';
    import Profile from '../svg/Profile.vue';

    /**
     * Import Stores
     */
    import { useUserStore } from '../../stores/useUserStore';

    export default {
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

        components: {
            NavSwoosh,
            Logo,
            Profile,
            ProfileDropdown
        },

        data() {
            return {
                currentUser: {}
            }
        },

        methods: {
            handleAvatarClick() {
                console.log('This has been clicked!!!');
                this.profileDropdown = !this.profileDropdown
            }
        },

        mounted() {
            this.currentUser = this.userStore.getUser
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
                                @mouseover="navDropdownToggle = true"
                                @mouseleave="navDropdownToggle = false"
                                class="h-fit"
                            >
                                Technology
                                <div v-show="navDropdownToggle" @mouseover="navDropdownToggle = true" class="navDropdown absolute  ">
                                    <div class="bg-[#002856]/50 mt-[8px]">
                                        <ul class="flex flex-col gap-4 py-4 text-white text-center text-[24px] font-semibold font-['Poppins']">
                                            <RouterLink class="flex" to="/software">
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

        <profileDropdown
            :currentUser="this.currentUser"
            :profileDropdown="this.profileDropdown"
            @handleAvatarClick="handleAvatarClick"
        />

        <Logo class="absolute right-20 top-4 z-30"/>
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
