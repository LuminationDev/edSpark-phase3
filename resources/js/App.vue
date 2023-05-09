<script setup>

    import NavBar from './components/global/NavBar.vue';
    import Footer from './components/global/Footer.vue';

    import { useRouter } from 'vue-router';
    const router = useRouter();

    import { watch } from 'vue';

    watch(router.currentRoute.value, (newRoute) => {
        console.log('watch me go!');
        console.log(newRoute);
    });

</script>

<template>
    <NavBar
        :key="router.currentRoute.value"
    />

    <div class="pageBodyContentContainer">
        <router-view />
    </div>

    <footer class="mt-auto">
        <Footer />
    </footer>
</template>
