<script>
    import { RouterView } from 'vue-router';
    import { useRouter } from 'vue-router';

    // Import components

    export default {
        setup() {
            const router = useRouter();

            return {
                router
            }
        },

        async mounted() {
            await this.$auth.signInWithRedirect({ originalUri: '/dashboard' });
        }
    }
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
    <RouterView />

</template>
