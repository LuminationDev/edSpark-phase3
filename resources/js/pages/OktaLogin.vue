<script setup>
import {onBeforeMount, onMounted, ref} from 'vue';
import {useRouter} from "vue-router";

import {appURL} from "@/js/constants/serverUrl";
import {useAuthStore} from "@/js/stores/useAuthStore";
import {useUserStore} from "@/js/stores/useUserStore";

const authStore = useAuthStore()
const userStore = useUserStore()

const router = useRouter()


const redirectToOkta = () => {
    loginWithOktaButtonPressed.value = true;
    window.location = '/login';
}

onMounted(async () => {
    if (authStore.isAuthenticated instanceof Promise) {
        console.log('before await')
        await authStore.isAuthenticated;
        console.log('after await')
    }
    console.log('here is login page')
    console.log(authStore.isAuthenticated)
    if (authStore.isAuthenticated) {
        console.log('about to send to dashboard')
        await router.push('/dashboard');
    }
})

const loginWithOktaButtonPressed = ref(false);

</script>
<template>
    <div
        class="flex justify-center items-center min-h-[50vh] mt-10 overflow-hidden z-10"
    >
        <div
            class="flex items-center flex-col p-8 rounded-2xl"
        >
            <h2 class="font-bold mb-4 text-2xl">
                Welcome to edSpark!
            </h2>
            <p class="font-thin mb-2">
                edSpark is provided by the Department for Education, South Australia. Access to edSpark is granted
                through EdPass.
            </p>
            <p class="font-thin mb-4">
                You'll be redirected within a few seconds, or click the button below.
            </p>
            <p />
            <button
                id="oktalogin"
                class="bg-blue-500 hover:bg-blue-600 font-bold ml-4 px-4 py-2 rounded-lg text-white"
                @click="redirectToOkta"
            >
                Login via EdPass
            </button>
        </div>
    </div>
</template>
<style>

</style>
