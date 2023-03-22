<script>
    import { RouterLink, RouterView } from 'vue-router';

    // Import components

    export default {
        setup() {

        },

        components: {
            // NavBar,
        },

        methods: {
            async login () {
                console.log('Hello');
                console.log(this.$auth)
                await this.$auth.signInWithRedirect();
            },
        }
    }
</script>

<template>


    <h1 class="text-[48px] mt-12 font-bold text-slate-900">Landing Page</h1>

    <button @click.prevent="login" class="bg-blue-500 px-4 py-2 text-white font-bold text-[24px]">
        Login
    </button>
    <router-link :to="{name: 'login'}"></router-link>
    <RouterView />

</template>
