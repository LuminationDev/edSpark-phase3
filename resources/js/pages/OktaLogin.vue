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
    <div class="fixed inset-0 flex items-center justify-center z-10 overflow-hidden">
        <!-- Modal -->
        <!-- class="bg-gradient-to-b from-[#f9fffe] to-[#f4fefc] w-1/4 p-8 rounded-2xl flex flex-col items-center" -->
        <div
            class="w-1/4 p-8 rounded-2xl flex flex-col items-center">
            <h2 class="text-2xl font-bold mb-4">{{ pageTitle }}</h2>
            <form @submit.prevent="login" class="flex flex-col items-center w-full">
                <div class="mb-4 w-full">
                    <label for="email" class="block text-gray-700 mb-1">Email Address
                        <sup class="text-danger-700 whitespace-nowrap font-medium dark:text-danger-400">
                            *
                        </sup>
                    </label>
                    <input v-model="email" type="email" id="email" placeholder="Enter you email address"
                        class="border rounded-lg px-3 py-2 w-full focus:outline-none focus:border-[#8dc9c5]" :required="!loginWithOktaButtonPressed">
                </div>
                <div class="mb-4 w-full">
                    <label for="password" class="block text-gray-700 mb-1">Password
                        <sup class="text-danger-700 whitespace-nowrap font-medium dark:text-danger-400">
                            *
                        </sup>
                    </label>
                    <input v-model="password" type="password" id="password" placeholder="Enter your password"
                        class="border rounded-lg px-3 py-2 w-full focus:outline-none focus:border-[#8dc9c5]" :required="!loginWithOktaButtonPressed">
                </div>
                <div class="flex justify-center w-full">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg cursor-not-allowed opacity-50"
                        id="login" :disabled="loginWithOktaButtonPressed">
                        Login
                    </button>
                    <button @click="redirectToOkta"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg ml-4" id="oktalogin">
                        Login with Okta
                    </button>
                </div>
            </form>
        </div>
        <!-- End of Modal -->
    </div>
</template>
<style>
.shadow-xl {
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
}
</style>
