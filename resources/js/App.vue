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
        :is-first-visit="isFirstVisit"
    />

    <div class="pageBodyContentContainer">
        <router-view @handleFirstLogin="handleFirstLogin" />
    </div>

    <footer class="mt-auto">
        <Footer />
    </footer>
</template>
