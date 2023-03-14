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
        }
    }
</script>

<template>
    <NavBar />

    <div>
        <router-view></router-view>
    </div>

    <footer class="mt-auto">
        <Footer />
    </footer>
</template>
