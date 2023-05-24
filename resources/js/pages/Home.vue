<script setup>
    import oktaAuth from '../constants/oktaAuth.js';

    console.log(oktaAuth);

    const handleLoginRedirect = async () => {
        await oktaAuth.signInWithRedirect({ originalUri: '/dashboard' });
    };

    handleLoginRedirect();
</script>

<template>


    <h1 class="text-[48px] mt-12 font-bold text-slate-900">Landing Page</h1>
    <button
        v-if="authState && authState.isAuthenticated"
        class="bg-blue-500 px-4 py-2 text-white font-bold text-[24px]"
    >
        Logout
    </button>
    <button
        v-else
        @click="login"
        class="bg-blue-500 px-4 py-2 text-white font-bold text-[24px]"
    >
        Login
    </button>
    <!-- <RouterView /> -->

</template>
