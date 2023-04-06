<script>
    import { RouterLink, RouterView } from 'vue-router';
    import oktaAuth from '../constants/oktaAuth';
    import { useRouter } from 'vue-router';

    // Import components

    export default {
        setup() {
            const router = useRouter();

            return {
                router
            }
        },

        components: {
            // NavBar,
        },

        methods: {
            async login () {
                console.log('Hello');
                console.log(oktaAuth);
                // if (!oktaAuth.isAuthenticated()) {
                //     oktaAuth.signInWithRedirect();
                // } else {
                //     console.log('already signed in you tard 😡');
                //     this.router.push({path: '/dashboard'});
                // }
                // const { claims } = await this.$auth.signInWithRedirect({ originalUri: '/' });
                // console.log(claims);
                oktaAuth.signInWithRedirect();

                /**
                 * NOT ALLOWED
                 */
                // oktaAuth.idx.authenticate({
                //     username: 'year13staffb',
                //     password: 'Career2023!'
                // }).then(transaction => {
                //     switch (transaction.status) {
                //         case 'SUCCESS':
                //             this.$auth.tokenManager.setTokens(transaction.tokens)
                //             this.$router.replace(this.$route.query.redirect || '/')
                //         break
                //         case 'PENDING':
                //         // next IDX step not handled in this app yet
                //             this.error = true
                //             this.msg = transaction.messages[0].message
                //             console.log('TODO: add handling for status: ', transaction.status,
                //                 'message: ', transaction.messages,
                //                 'next step: ', transaction.nextStep)
                //         break
                //         case 'FAILURE':
                //             // failure from idx.authenticate
                //             this.error = true
                //             this.msg = transaction.messages[0].message
                //             console.log(transaction.status, transaction.messages)
                //         break
                //         default:
                //             this.error = true
                //             this.msg = transaction.messages[0].message
                //             console.error('What happened?: ', transaction.status, transaction.messages)
                //     }
                // }).catch(err => {
                //     console.log(err.message);
                // })
            },
        },

        mounted() {
            console.log(oktaAuth.isAuthenticated());
            console.log(oktaAuth);
        }
    }
</script>

<template>


    <h1 class="text-[48px] mt-12 font-bold text-slate-900">Landing Page</h1>

    <button @click="login" class="bg-blue-500 px-4 py-2 text-white font-bold text-[24px]">
        Login
    </button>
    <!-- <router-link :to="{name: 'login'}"></router-link> -->
    <RouterView />

</template>
