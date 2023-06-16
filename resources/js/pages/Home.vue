<script setup>
// import oktaAuth from '../constants/oktaAuth.js';

// console.log(oktaAuth);

// const handleLoginRedirect = async () => {
//     await oktaAuth.signInWithRedirect({ originalUri: '/dashboard' });
// };

// handleLoginRedirect();
// import oktaAuth from '../constants/oktaAuth.js';

// const login = async () => {
//     await oktaAuth.signInWithRedirect({ originalUri: '/dashboard' });
//     // handleAuthentication();
// };

// const handleAuthentication = async () => {
//     alert('okta is called');
//     /**
//      * Retireve id token from local storage
//      * send it to laravel authentication api
//                  * bypass the laravel authentication
//                  *
//                  */
//     console.log('##########################################################################');
//     let token = localStorage.getItem('okta-token-storage');
//     token = JSON.parse(token);
//     token = token.accessToken.accessToken;
//     console.log("TOKEN", token);

//     await axios({
//         method: 'POST',
//         url: 'http://localhost:8000/api/authenticate',
//         data: {
//             accessToken: token
//         },
//         headers: {
//             'Authorization': `Bearer ${token}`
//         }
//     }).then(response => {
//         console.log("RESPONSE", response);
//         // if (response.data.status === 'success') {
//             //     localStorage.setItem('authenticatedToken', JSON.stringify(response.data));
//         //     // window.open("http://localhost:8000/admin")
//         //     window.location.href = 'http://localhost:8000/admin/autologin';
//         // }
//     }).catch(err => {
//         throw new error(err);
//     })
//     console.log('#############################################################################');
// }

// handleAuthentication();
//import okta vue page
import OktaLogin from "@/js/pages/OktaLogin.vue";
</script>

<template>
  <!-- <h1 class="text-[48px] mt-12 font-bold text-slate-900">Landing Page</h1>
    <button v-if="authState && authState.isAuthenticated" class="bg-blue-500 px-4 py-2 text-white font-bold text-[24px]">
        Logout
    </button>
    <button v-else @click="login" class="bg-blue-500 px-4 py-2 text-white font-bold text-[24px]">
        Login
    </button>
    <RouterView /> -->
  <OktaLogin />
</template>
