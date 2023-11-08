<script setup>
import {onBeforeMount, ref} from 'vue';
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

onBeforeMount(() =>{
    if(authStore.isAuthenticated && userStore.currentUser.full_name){
        router.push('/dashboard')

    }
})

const pageTitle = ref('edSpark Login');
const email = ref('');
const password = ref('');
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
                {{ pageTitle }}
            </h2>
            <div
                class="flex items-center flex-col w-full"
            >
                <div class="mb-4 w-full">
                    <label
                        for="email"
                        class="block mb-1 text-gray-700"
                    >Email Address
                        <sup
                            class="font-medium text-danger-700 dark:text-danger-400 whitespace-nowrap"
                        >
                            *
                        </sup>
                    </label>
                    <input
                        id="email"
                        v-model="email"
                        type="email"
                        placeholder="Enter you email address"
                        class="border focus:border-[#8dc9c5] px-3 py-2 rounded-lg w-full focus:outline-none"
                    >
                </div>
                <div class="mb-4 w-full">
                    <label
                        for="password"
                        class="block mb-1 text-gray-700"
                    >Password
                        <sup
                            class="font-medium text-danger-700 dark:text-danger-400 whitespace-nowrap"
                        >
                            *
                        </sup>
                    </label>
                    <input
                        id="password"
                        v-model="password"
                        type="password"
                        placeholder="Enter your password"
                        class="border focus:border-[#8dc9c5] px-3 py-2 rounded-lg w-full focus:outline-none"
                    >
                </div>
                <div class="flex justify-center w-full">
                    <button
                        id="login"
                        type="submit"
                        class="
                            bg-blue-500
                            hover:bg-blue-600
                            cursor-not-allowed
                            font-bold
                            opacity-50
                            px-4
                            py-2
                            rounded-lg
                            text-white
                            "
                        :disabled="loginWithOktaButtonPressed"
                    >
                        Login
                    </button>
                    <button
                        id="oktalogin"
                        class="bg-blue-500 hover:bg-blue-600 font-bold ml-4 px-4 py-2 rounded-lg text-white"
                        @click="redirectToOkta"
                    >
                        Login with Okta
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<style>

</style>
