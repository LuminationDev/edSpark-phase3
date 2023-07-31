<script setup>
import { ref } from 'vue';

import { useAuthStore } from '@/js/stores/auth';
import { generateCodeChallenge, generateCodeVerifier } from '../helpers/pkce';

// const store = useAuthStore();
const redirectToOkta = () => {
    console.log("Redirect to okta");
    loginWithOktaButtonPressed.value = true;
    window.location = '/login';
}

const pageTitle = ref('EdSpark Login');
const showModal = ref(true); // Set to true to display the modal by default
const email = ref('');
const password = ref('');
const loginWithOktaButtonPressed = ref(false);

function login() {
    // Implement your login logic here
}

// const loginWithOkta = async () => {
//     try {
//         const codeVerifier = generateCodeVerifier();
//         const codeChallenge = generateCodeChallenge(codeVerifier);
//         const state = Math.random().toString(36).substring(7);

//         // Save the code verifier and state in local storage for later use
//         localStorage.setItem('codeVerifier', codeVerifier);
//         localStorage.setItem('state', state);

//         const oktaParams = {
//             clientId: '0oa2jr6t74AkegzRD3l7',
//             redirectUri: 'http://localhost:8000/login/callback',
//             responseType: 'code',
//             responseMode: 'query',
//             scope: 'openid profile email',
//             state,
//             codeChallenge,
//             codeChallengeMethod: 'S256',
//         };

//         const queryParams = new URLSearchParams(oktaParams).toString();
//         window.location.href = `https://portal-test.edpass.sa.edu.au/oauth2/default/v1/authorize?${queryParams}`;
//     } catch (error) {
//         console.error(error);
//     }
// };
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
            <form
                class="flex items-center flex-col w-full"
                @submit.prevent="login"
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
                        :required="!loginWithOktaButtonPressed"
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
                        :required="!loginWithOktaButtonPressed"
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
            </form>
        </div>
    </div>
</template>
<style>

</style>
