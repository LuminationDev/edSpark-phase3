<script>
import { useRoute, useRouter } from 'vue-router';
import { ref } from 'vue';

export default {
    setup() {
        const route = useRoute();
        const router = useRouter();

        return {
            route,
            router
        }
    }, 

    data() {
        return {
            breadCrumbPrev: 'Schools',
            breadCrumbName: this.route.params.name
        }
    },

    mounted() {
        console.log(window.location.origin);
    }
}
</script>

<template>
    <div>
        <slot name="hero" />
    </div>
</template>
