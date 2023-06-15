<script setup>
import { useAuthStore } from '@/js/stores/auth';
import { generateCodeChallenge, generateCodeVerifier } from '../helpers/pkce';

// const store = useAuthStore();

const redirectToOkta = () => {
    console.log("Redirect to okta");
    window.location = '/login';
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
    <h1 class="text-[48px] mt-12 font-bold text-slate-900">Landing Page</h1>
    <!-- <button v-if="authState && authState.isAuthenticated" class="bg-blue-500 px-4 py-2 text-white font-bold text-[24px]">
        Logout
    </button>
    <button v-else @click="login" class="bg-blue-500 px-4 py-2 text-white font-bold text-[24px]">
        Login
    </button>
    <RouterView /> -->
    <div>
        <button @click="redirectToOkta" class="bg-blue-500 px-4 py-2 text-white font-bold text-[24px]">Login with Okta</button>
    </div>
</template>
