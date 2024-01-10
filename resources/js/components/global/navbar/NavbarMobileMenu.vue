<script setup>

import {storeToRefs} from "pinia";
import {computed, onMounted, ref} from "vue";
import {useRouter} from "vue-router";

import NavItemsMobileMenu from "@/js/components/global/navbar/NavItemsMobileMenu.vue";
import ProfileDropdown from "@/js/components/global/ProfileDropdown.vue";
import ProfileDropdownMobile from "@/js/components/global/ProfileDropdownMobile.vue";
import Close from "@/js/components/svg/Close.vue";
import Logo from "@/js/components/svg/Logo.vue";
import Search from "@/js/components/svg/Search.vue";
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
const profileLinks = ref([]);
const mobileNavParent = ref('')
const mobileNavChildren = ref([])


const avatarUrl = computed(() => {
    const meta = currentUser.value?.metadata?.find(m => m.user_meta_key === 'userAvatar');
    return meta ? meta.user_meta_value[0].replace(/\\\//g, "/") : '';
});
const handleGlobalsearchClick = () => {
    showGlobalSearch.value = true
}

const handleClickMobileNavItems = (route) => {
    if (route.children && route.children.length) {
        mobileNavParent.value = route.name
        mobileNavChildren.value = route.children

        console.log(mobileNavParent)
    }
}


const toggleNavbar = () => {
    showMobileNavbar.value = !showMobileNavbar.value
    mobileNavParent.value = ''
    mobileNavChildren.value = []
}

const BackNavbar = () => {
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
        <span class="bg-white block h-1 w-10" />
        <span class="bg-white block h-1 w-10" />
        <span class="bg-white block h-1 w-10" />
    </nav>
    <Transition name="slide-fade">
        <div
            v-if="showMobileNavbar"
            class="
                bg-main-navy
                fixed
                top-0
                left-0
                h-screen
                min-w-[300px]
                mobileNavbarFull
                px-5
                py-10
                sidebarMenu
                text-2xl
                text-white
                transition
                w-[45vw]
                z-[60]
                "
        >
            <!--            Main Listing Condition    -->
            <ul
                v-if="!mobileNavChildren.length && !mobileNavParent"
                class="flex flex-col font-semibold text-3xl text-white"
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
                                class="absolute top-8 left-5 h-16 nav-logo transition-all w-16 z-30"
                            />
                        </router-link>
                    </div>
                    <button
                        @click="toggleNavbar"
                    >
                        <Close class="fill-white hover:fill-slate-200 h-6 w-6 hover:cursor-pointer" />
                    </button>
                </li>
                <li class="-mt-2 flex font-semibold py-4 text-3xl" />
                <NavItemsMobileMenu
                    v-for="(route, i) in navLinks"
                    :key="i"
                    :route="route"
                    :click-callback="() => handleClickMobileNavItems(route)"
                />


                <li
                    class="cursor-pointer flex justify-between items-center mt-4"
                    @click="handleGlobalsearchClick"
                >
                    <div class="searchText">
                        Search
                    </div>
                    <div>
                        <Search class="ml-10" />
                    </div>
                </li>
                <li class="-mt-2 flex font-semibold py-4 text-3xl">
                    <ProfileDropdownMobile
                        v-if="isAuthenticated"
                        :key="currentUser"
                        :current-user="currentUser"
                        :avatar-url="avatarUrl"
                    />
                </li>
            </ul>
            <!--            Children Listing Condition    -->
            <ul v-else>
                <li
                    class="cursor-pointer flex justify-between font-bold ml-auto text-2xl hover:text-main-teal"
                >
                    <button
                        class="-ml-2 hover:cursor-pointer hover:fill-slate-200 fill-white flex justify-between h-6 w-6"
                        @click="BackNavbar"
                    >
                        <div
                            class="-mt-0 hover:fill-slate-200 over:cursor-pointer mr-2"
                        >
                            <svg
                                width="30"
                                height="30"
                                viewBox="0 0 20 23"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    id="Icons / Bold / arrow-right-1"
                                    d="M6.99689 11.2999C6.99634 10.8843 7.18593 10.4885 7.51721 10.2138L14.9565 4.0509C15.4692 3.64578 16.2316 3.70018 16.6737 4.17342C17.1157 4.64667 17.0757 5.36566 16.5835 5.7928L10.0781 11.1818C10.042 11.2117 10.0213 11.2547 10.0213 11.2999C10.0213 11.3452 10.042 11.3882 10.0781 11.418L16.5835 16.8071C17.0757 17.2342 17.1157 17.9532 16.6737 18.4264C16.2316 18.8997 15.4692 18.9541 14.9565 18.549L7.51988 12.3879C7.1878 12.1128 6.99731 11.7165 6.99689 11.2999Z"
                                    fill="white"
                                />
                            </svg>
                        </div>
                        <div
                            class="-mt-1 hover:cursor-pointer hover:fill-slate-200 font-sans font-thin text-white"
                        >
                            Back
                        </div>
                    </button>
                    <button
                        @click="toggleNavbar"
                    >
                        <Close class="fill-white hover:fill-slate-200 h-6 w-6 hover:cursor-pointer" />
                    </button>
                </li>
                <li class="flex font-semibold mt-8 py-4 text-3xl">
                    {{ mobileNavParent }}
                </li>
                <NavItemsMobileMenu
                    v-for="(route, i) in mobileNavChildren"
                    :key="i"
                    :route="route"
                    :click-callback="() => handleClickMobileNavItems(route)"
                />


                <li
                    class="cursor-pointer flex justify-between items-center mt-4"
                    @click="handleGlobalsearchClick"
                >
                    <div class="searchText">
                        Search
                    </div>
                    <div>
                        <Search class="ml-auto" />
                    </div>
                </li>
            </ul>
        </div>
    </Transition>
    <div
        v-if="showMobileNavbar"
        class="absolute top-0 right-0 bg-main-navy/50 h-screen overlay w-[70vw] z-50"
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
