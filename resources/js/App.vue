<script setup>

    import NavBar from './components/global/NavBar.vue';
    import Footer from './components/global/Footer.vue';

</script>

<template>
    <NavBar />

    <div class="pageBodyContentContainer">
        <router-view />
    </div>

    <footer class="mt-auto">
        <Footer />
    </footer>
</template>
