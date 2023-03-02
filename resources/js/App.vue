<script>
    // Dependencies
    // import { RouterView } from 'vue-router';

    // Components
    import NavBar from './components/global/NavBar.vue'

    export default {

        components: {
            NavBar,
        }
    }
</script>

<template>
    <NavBar />

    <div>
        <router-view></router-view>
    </div>

</template>
