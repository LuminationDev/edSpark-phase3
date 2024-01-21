<script setup>
import {ref, watchEffect} from 'vue';
import {useRouter} from "vue-router";


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
        <template v-if="Component">
            <router-view />
        </template>
    </div>
</template>
