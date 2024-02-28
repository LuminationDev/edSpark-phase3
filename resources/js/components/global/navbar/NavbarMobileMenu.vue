<script setup>
import ChevronLeftNavIcon from "@/js/components/svg/ChevronLeftNavIcon.vue";
import ChevronRightNavIcon from "@/js/components/svg/ChevronRightNavIcon.vue";
import {storeToRefs} from "pinia";
import {computed, onMounted, ref} from "vue";
import {useRouter} from "vue-router";

import NavItemsMobileMenu from "@/js/components/global/navbar/NavItemsMobileMenu.vue";
import ProfileDropdownMobile from "@/js/components/global/ProfileDropdownMobile.vue";
import Close from "@/js/components/svg/Close.vue";
import Logo from "@/js/components/svg/Logo.vue";
import Search from "@/js/components/svg/Search.vue";
import {APP_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useAuthStore} from "@/js/stores/useAuthStore";
import {useUserStore} from "@/js/stores/useUserStore";
import {useWindowStore} from "@/js/stores/useWindowStore";

const router = useRouter();
const authStore = useAuthStore();
const windowStore = useWindowStore();
const userStore = useUserStore()
// eslint-disable-next-line @typescript-eslint/no-unused-vars
const {currentUser} = storeToRefs(userStore);

const {showMobileNavbar} = storeToRefs(useWindowStore());
const {isAuthenticated} = storeToRefs(authStore);
const {showGlobalSearch} = storeToRefs(windowStore);

const navLinks = ref([]);
const mobileNavParent = ref('')
const mobileNavChildren = ref([])

const profileTargetPath = computed(() => {
    if (currentUser.value.id) {
        return `/profile/${currentUser.value.id}`
    } else return ''
})
const messageTargetPath = computed(() => {
    if (currentUser.value.id) {
        return `/message/${currentUser.value.id}`
    } else return ''
})
const mySchoolTargetPath = computed(() => {
    if (currentUser.value?.site?.site_name) {
        return `/schools/${currentUser.value.site.site_name}`
    } else return ''
})

const handleClickAdmin = () => {
    window.open(window.location.origin + '/admin', '_self')
}

const handleLogoutUser = async () => {
    console.log('handleLogoiut callback is called')
    try {
        const response = await fetch(APP_ENDPOINTS.LOGOUT, {
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

const profileChildren = [
    {
        name: 'userProfileInfo',
        path: profileTargetPath,
        meta: {
            customText: 'User Profile'
        }
    }, {
        name: 'userProfileMessages',
        path: messageTargetPath,
        meta: {
            customText: 'Message'
        }
    }
    , {
        name: 'create-pages',
        path: '/create',
        meta: {
            customText: 'Create'
        }
    }, {
        name: 'school',
        path: mySchoolTargetPath,
        meta: {
            customText: 'My School'
        }
    },
    {
        name: 'Admin',
        type: 'admin',
        clickCallback: handleClickAdmin,

    },
    {
        name: 'Sign Out',
        type: 'signout',
        clickCallback: handleLogoutUser,

    }
]
const isSearchVisible = ref(false);

const handleClickMobileProfile = () => {

    mobileNavParent.value = "My account"
    mobileNavChildren.value = profileChildren
    isSearchVisible.value = false;
}

const avatarUrl = computed(() => {
    const meta = currentUser.value?.metadata?.find(m => m.user_meta_key === 'userAvatar');
    return meta ? meta.user_meta_value[0].replace(/\\\//g, "/") : '';
});
const handleGlobalSearchClick = () => {
    showGlobalSearch.value = true
}

const handleClickMobileNavItems = (route) => {
    if (route.children && route.children.length) {
        mobileNavParent.value = route.name
        mobileNavChildren.value = route.children
        isSearchVisible.value = true;
        console.log(mobileNavParent)
    } else {
        toggleNavbar()
        isSearchVisible.value = false;
    }
}


const toggleNavbar = () => {
    showMobileNavbar.value = !showMobileNavbar.value
    mobileNavParent.value = ''
    mobileNavChildren.value = []

    if(showMobileNavbar.value){        
        document.body.classList.add('overflow-y-hidden');
        document.body.classList.add('h-screen');
    } else {
        document.body.classList.remove('overflow-y-hidden');
        document.body.classList.remove('h-screen');
    }
    
}

const handleClickBackNavbar = () => {
    mobileNavChildren.value = []
    mobileNavParent.value = ''
}

const setupRoutes = () => {
    const tempNavArray = [];
    const currentUserIsPartner = userStore.getUserRoleName === 'SITESUPP' // TODO
    router.options.routes.forEach(route => {
        if (Object.keys(route).includes('meta') && route.meta['navigation']) {
            if (currentUserIsPartner && Object.keys(route.meta).includes('partnerCanAccess')) {
                if (route.meta['partnerCanAccess']) {
                    tempNavArray.push(route);
                }
            } else { // no partnerCanAccess meta here
                tempNavArray.push(route)
            }
        }
    });
    navLinks.value = tempNavArray;
};
setupRoutes();
</script>

<template>
    <nav
        v-if="isAuthenticated"
        id="navbarMobileBurger"
        class="HAMBURGER-ICON absolute top-2 left-2 bg-[#002856]/50 p-4 rounded space-y-2 z-50 hover:cursor-pointer"
        :class="{navbarScrolled : navScrolled}"
        @click="toggleNavbar"
    >
        <span class="bg-white block h-1 w-10"/>
        <span class="bg-white block h-1 w-10"/>
        <span class="bg-white block h-1 w-10"/>
    </nav>
    <Transition name="slide-fade">
        <div
            v-if="showMobileNavbar"
            class="
                bg-main-navy
                fixed
                overflow-y-auto
                top-0
                left-0
                h-full
                min-w-[300px]
                max-w-[800px]
                mobileNavbarFull
                px-5
                py-6
                sidebarMenu
                text-2xl
                text-white
                transition
                w-[85vw]
                z-[60]
                "
        >
            <!--            Main Listing Condition    -->
            <ul
                v-if="!mobileNavChildren.length && !mobileNavParent"
                class="flex flex-col font-light text-xl text-white"
            >
                <li
                    class="cursor-pointer font-bold ml-auto text-2xl hover:text-main-teal"
                >
                    <div
                        id="edSparkLogo"
                        title="edSpark logo"
                    >
                        <router-link
                            :to="{name: 'dashboard'}"
                            title="Go to dashboard"
                        >
                            <Logo
                                class="absolute top-4 left-5 h-16 nav-logo transition-all w-16 z-30"
                            />
                        </router-link>
                    </div>
                    <button
                        @click="toggleNavbar"
                    >
                        <Close class="fill-white hover:fill-slate-200 h-6 w-6 hover:cursor-pointer  mb-6"/>
                    </button>
                </li>
                <li class="-mt-2 flex font-medium py-4 text-2xl"/>
                <NavItemsMobileMenu
                    v-for="(route, i) in navLinks"
                    :key="i"
                    :route="route"
                    :click-callback="() => handleClickMobileNavItems(route)"
                />

                <li>
                    <div class="bg-white h-px my-6"/>
                </li>

                

                <li
                    class="cursor-pointer flex justify-between items-center mb-4"
                    @click="handleGlobalSearchClick"
                >
                    <div class="searchText">
                        Search
                    </div>
                    <div>
                        <Search class="ml-10"/>
                    </div>
                </li>
                <!-- <li>
                    <div class="bg-white h-px mt-12 my-4"/>
                </li> -->
                
                <li
                    class="cursor-pointer flex items-center flex-row font-medium mt-4 mb-8"
                >
                    <ProfileDropdownMobile
                        v-if="isAuthenticated"
                        :key="currentUser"
                        class="mr-10"
                        :click-callback="handleClickMobileProfile"
                        :current-user="currentUser"
                        :avatar-url="avatarUrl"
                        :is-router-link="false"
                    />
                    <div
                        class="ml-12 searchText"
                        @click="handleClickMobileProfile"
                    >
                        Profile
                    </div>
                </li>
            </ul>
            <!--            Children Listing Condition    -->
            <ul v-else>
                <li
                    class="cursor-pointer flex justify-between font-bold ml-auto text-xl font-light h-0 mb-16"
                >
                    <button
                        class="-ml-2 hover:cursor-pointer fill-white flex justify-between "
                        @click="handleClickBackNavbar"
                    >
                        <div
                            class="gap-x-2 over:cursor-pointer mr-2 flex flex-row"
                        >
                            <ChevronLeftNavIcon/>
                            Back
                        </div>
                    </button>
                    <button
                        @click="toggleNavbar"
                    >
                        <Close class="fill-white hover:fill-slate-200 h-6 w-6 hover:cursor-pointer"/>
                    </button>
                </li>
                <li class="flex font-medium mt-8 py-4 text-2xl">
                    {{ mobileNavParent }}
                </li>
                
                <NavItemsMobileMenu
                    v-for="(route, i) in mobileNavChildren"
                    :key="i"
                    :route="route"
                    :click-callback="() => handleClickMobileNavItems(route)"
                />

                
                <li>
                    <div class="bg-white h-px my-6"/>
                </li>

                

                <li
                    v-if="isSearchVisible"
                    class="cursor-pointer flex justify-between items-center mt-4"
                    @click="handleGlobalSearchClick"
                >
                    <div class="searchText">
                        Search
                    </div>
                    <div>
                        <Search class="ml-auto"/>
                    </div>
                </li>
            </ul>
        </div>
    </Transition>
    <div
        v-if="showMobileNavbar"
        class="absolute top-0 right-0 bg-main-navy/50 h-screen w-screen overlay z-50"
        @click="toggleNavbar"
    />
</template>
<style scoped>

#searchItem {

    display: flex;
    align-self: center;
    flex-direction: column;
    align-items: center;
}

#searchIconMobile {
    width: 28px;
    margin-top: 16px;
    z-index: -1;
}

.svg-content {
    display: inline-block;
    position: absolute;
    top: 0;
    left: 0;
}

.svg-container {
    display: inline-block;
    position: relative;
    width: 100%;
    padding-bottom: 70%;
    vertical-align: middle;
    overflow: hidden;
}

.slide-fade-enter-active {
    transition: all 0.2s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(-20px);
    opacity: 0;
}

@media screen and (min-width: 375px) {
    #navbarMobileBurger {
        left: 10px;
    }
}

#navbarMobileBurger {
    transition: 300ms;
    position: fixed;
    top: 10px;
    left: 10px;
}

.navbarScrolled {
    /* top: 10px !important; */
    background-color: #002856 !important;
}

</style>
