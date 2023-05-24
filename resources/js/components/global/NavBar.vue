<script setup>
    /**
     * Import Dependencies
     */
    import { ref, onMounted } from 'vue';
    import { useRouter } from 'vue-router';

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
    import NavItems from './NavItems.vue';

    const props = defineProps({
        isFirstVisit: {
            type: Boolean,
            required: true
        }
    });

    const router = useRouter();
    const userStore = useUserStore();
    const navDropdownToggle = ref(false);
    const profileDropdown = ref(false);
    const currentUser = ref({});
    const avatarUrl = ref('');
    const navLinks = ref([]);

    onMounted(() => {
        if (!Object.keys(userStore.getUser).length <= 0) {
            currentUser.value = userStore.getUser;

            currentUser.value.metadata.forEach(meta => {
                if (meta.user_meta_key === 'userAvatar') {
                    console.log(meta);
                    avatarUrl.value = meta.user_meta_value[0].replace(/\\\//g, "/");;
                }
            })
        }
    });

    const handleAvatarClick = () => {
        profileDropdown.value = !profileDropdown.value;
    };

    const setupRoutes = () => {
        const tempNavArray = [];
        router.options.routes.forEach(route => {
            if (Object.keys(route).includes('meta') && route.meta.navigation) {
                // TODO: Make the dropdown element dynamic
                tempNavArray.push(route);
            }
        });
        navLinks.value = tempNavArray;
    };

    setupRoutes();
</script>

<template>
    <div class="w-full h-[240px] relative z-20">
        <div class="nav-background w-full h-full pt-7 bg-[url(http://localhost:5173/resources/assets/images/children-vr.png)] bg-no-repeat bg-cover">
            <nav class="bg-[#002856]/50 py-2 px-12 w-full" >
                <ul class="flex flex-row flex-wrap gap-8 text-white text-[24px] font-semibold font-['Poppins']">
                    <NavItems
                        v-for="(route, i) in navLinks"
                        :route="route"
                    />
                </ul>
                <!-- <ul class="flex flex-wrap gap-8 text-white text-[24px] font-semibold font-['Poppins']">
                    <router-link to="/dashboard">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Dash
                        </li>
                    </router-link>

                    <router-link to="/schools">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Schools
                        </li>
                    </router-link>

                    <router-link to="/advice">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Advice
                        </li>
                    </router-link>
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
                                        <router-link
                                            class="flex"
                                            to="/software"
                                        >
                                            <li class="px-4 mx-auto cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                                                Software
                                            </li>
                                        </router-link>
                                        <router-link to="/hardware">
                                            <li class="px-4 mx-auto cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                                                Hardware
                                            </li>
                                        </router-link>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>


                    <router-link to="/community">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Community
                        </li>
                    </router-link>

                    <router-link to="/partners">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Partners
                        </li>
                    </router-link>

                    <router-link to="/events">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Events
                        </li>
                    </router-link>
                </ul> -->
            </nav>
        </div>

        <ProfileDropdown
            :key="currentUser"
            :current-user="currentUser"
            :profile-dropdown="profileDropdown"
            :avatar-url="avatarUrl"
            @handleAvatarClick="handleAvatarClick"
        />

        <Logo class="absolute right-20 top-36 z-30 md:w-44 md:h-44 md:top-24 sm:w-36 sm:h-36 sm:top-32 w-36 h-36 lg:top-24" />
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
