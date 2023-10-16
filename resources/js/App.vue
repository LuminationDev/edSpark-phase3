<script setup>

import {throttle} from "lodash";
import {storeToRefs} from "pinia";
import {onBeforeMount, onBeforeUnmount} from "vue";
import {onMounted, ref} from "vue";
import { useRouter} from 'vue-router';

import Footer from '@/js/components/global/Footer/Footer.vue';
import Navbar from '@/js/components/global/navbar/Navbar.vue';
import NavbarMobileMenu from "@/js/components/global/navbar/NavbarMobileMenu.vue";
import GlobalSearch from "@/js/components/search/GlobalSearch.vue";
import {appURL} from "@/js/constants/serverUrl";
import {useAuthStore} from "@/js/stores/useAuthStore";
import {useUserStore} from "@/js/stores/useUserStore";
import {useWindowStore} from "@/js/stores/useWindowStore";


const router = useRouter();

const userStore = useUserStore()
const {currentUser, userEntryLink} = storeToRefs(userStore)

const windowStore = useWindowStore()
const {isMobile, windowWidth, showGlobalSearch} = storeToRefs(windowStore)

const authStore = useAuthStore()


const setWindowWidth = () => {
    windowWidth.value = window.innerWidth
    windowStore.updateIsMobile()
    windowStore.updateIsTablet()
}


onBeforeMount(async () => {
    if (!window.location.origin.includes('test.edspark.sa.edu.au')) {
        // will fill in isAuthenticated with a Promise<boolean>
        authStore.checkAuthenticationStatus();
        console.log(authStore.isAuthenticated)
        await authStore.isAuthenticated
        if (!authStore.isAuthenticated) {
            window.location = '/login'
        } else {
            await axios.get(`${appURL}/sanctum/csrf-cookie`);
            await userStore.fetchCurrentUserAndLoadIntoStore();
            if (userStore.userEntryLink) {
                let urlObj;
                try{
                    urlObj = new URL(userStore.userEntryLink).pathname
                } catch(_){
                    urlObj = userStore.userEntryLink
                }finally {
                    await router.push(urlObj).then(() => {
                        userEntryLink.value = ''
                    })
                }
                console.log(urlObj + 'pushing path after thisss')
            } else {
                await router.push('/dashboard')
            }
        }


    }

    setWindowWidth();
    window.addEventListener('resize', setWindowWidth);
});


// const userStore = useUserStore();
const navScrolled = ref(false);
let scrollHandler;

onMounted(() => {
    if (Object.keys(userStore.getUser).length > 0) {
        currentUser.value = userStore.getUser;
    }
    scrollHandler = throttle(() => {
        const navbar = document.getElementById('navbarMobileBurger') || document.getElementById('navbarFullsize');

        if (navbar) {
            navScrolled.value = window.scrollY > navbar.offsetTop;
            if (navScrolled.value) {
                navbar.classList.add('navbarScrolled');
            } else {
                navbar.classList.remove('navbarScrolled');
            }
        }
    }, 100);

    window.addEventListener('scroll', scrollHandler);
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', setWindowWidth);
    window.removeEventListener('scroll', scrollHandler);
})

</script>

<template>
    <div class="relative w-full z-50">
        <Navbar
            :key="router.currentRoute.value"
        />
        <NavbarMobileMenu
            v-if="isMobile"
            class="absolute top-2 left-2 lg:hidden"
        />
    </div>
    <template v-if="showGlobalSearch">
        <GlobalSearch />
    </template>


    <div class="pageBodyContentContainer">
        <router-view />
    </div>
    <footer class="mt-auto">
        <Footer />
    </footer>
</template>
