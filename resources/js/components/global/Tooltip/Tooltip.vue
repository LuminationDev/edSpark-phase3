<script setup>
defineProps({
    tip: {
        type: String,
        required: true
    },
})
</script>

<template>
    <span
        class="bg-gray-700 px-3 py-1 rounded rounded-xl shadow-lg text-white tooltip"
    >{{ tip }}</span>
</template>

<style scoped>
.tooltip {
    @apply invisible fixed;
}

.has-tooltip:hover .tooltip {
    visibility: visible;
    animation: appear 1s;
    position: fixed;
    z-index: 999;
}

@keyframes appear {
    0% {
        opacity: 0
    }

    100% {
        opacity: 1;
        z-index: 99;
    }
}
</style>
