<script setup>
import {ref, watchEffect} from 'vue';
import {useRouter} from "vue-router";

import TheTechnology from "@/js/pages/TheTechnology.vue";

const Component = ref(null);

// Assuming you have a router instance defined somewhere
const router = useRouter()

// Watch for changes in the route component
watchEffect(() => {
    Component.value = router.currentRoute.value.matched[router.currentRoute.value.matched.length - 1].components.default;
});
</script>

<template>
    <div>
        <router-view />
        <component :is="Component.value || TheTechnology" />
    </div>
</template>
