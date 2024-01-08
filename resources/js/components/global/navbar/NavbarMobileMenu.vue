<script setup>

import {storeToRefs} from "pinia";
import {onMounted, ref} from "vue";
import {useRouter} from "vue-router";

import NavItemsMobileMenu from "@/js/components/global/navbar/NavItemsMobileMenu.vue";
import Close from "@/js/components/svg/Close.vue";
import Search from "@/js/components/svg/Search.vue";
import {useAuthStore} from "@/js/stores/useAuthStore";
import {useWindowStore} from "@/js/stores/useWindowStore";

const router = useRouter();
const authStore = useAuthStore();
const windowStore = useWindowStore();

const {showMobileNavbar} = storeToRefs(useWindowStore());
const {isAuthenticated} = storeToRefs(authStore);
const {showGlobalSearch} = storeToRefs(windowStore);

const navLinks = ref([]);


const handleGlobalsearchClick = () => {
    showGlobalSearch.value = true
}


const handleToggleNavbar = () => {
    showMobileNavbar.value = !showMobileNavbar.value
}

const setupRoutes = () => {
    const tempNavArray = [];
    router.options.routes.forEach(route => {
        if (Object.keys(route).includes('meta') && route.meta['navigation']) {
            // TODO: Make the dropdown element dynamic
            tempNavArray.push(route);
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
        @click="handleToggleNavbar"
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
                w-[30vw]
                z-[60]
                "
        >
            <ul
                class="flex flex-col font-semibold text-white"
            >
                <li
                    class="cursor-pointer font-bold ml-auto text-2xl hover:text-main-teal"
                    @click="handleToggleNavbar"
                >
                    <Close class="fill-white hover:fill-slate-200 h-6 w-6 hover:cursor-pointer" />
                </li>
                <NavItemsMobileMenu
                    v-for="(route, i) in navLinks"
                    :key="i"
                    :route="route"
                    :click-callback="handleToggleNavbar"
                />
                <li
                    class="cursor-pointer flex items-center flex-row gap-28 mt-4"
                    @click="handleGlobalsearchClick"
                >
                    <div class="searchText">
                        Search
                    </div>
                    <div>
                        <Search class="ml-10" />
                    </div>
                </li>
            </ul>
        </div>
    </Transition>
    <div
        v-if="showMobileNavbar"
        class="absolute top-0 right-0 bg-main-navy/50 h-screen overlay w-[70vw] z-50"
        @click="handleToggleNavbar"
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

/* @media screen and (min-width: 640px){
    #navbarMobileBurger{
        left: 4vw !important;
    }
}
@media screen and (min-width: 700px){
    #navbarMobileBurger{
        left: 8vw !important;
    }
}
@media screen and (min-width: 900px){
    #navbarMobileBurger{
        left: 12vw !important;
    }
} */

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
