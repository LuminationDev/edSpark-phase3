<script>
    // Dependencies
    // import { RouterView } from 'vue-router';

    // Components
    import NavBar from './components/global/NavBar.vue';
    import Footer from './components/global/Footer.vue';

    export default {

        components: {
            NavBar,
            Footer
        },

        data() {
            return {
                isFirstVisit: false
            }
        },

        methods: {
            handleFirstLogin(firstVisit) {
                console.log('Hello there friends!', firstVisit);
                this.isFirstVisit = firstVisit;
            }
        }
    }
</script>

<template>
    <NavBar
        :isFirstVisit="this.isFirstVisit"
    />

    <div>
        <router-view @handleFirstLogin="this.handleFirstLogin"></router-view>
    </div>

    <footer class="mt-auto">
        <Footer />
    </footer>
</template>
