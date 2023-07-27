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
        class="tooltip rounded shadow-lg px-3 py-1 bg-gray-700 rounded-xl text-white"
    >{{ tip }}</span>
</template>

<style scoped>
.tooltip {
    @apply invisible absolute;
}

.has-tooltip:hover .tooltip {
    visibility: visible;
    animation: appear 1s;
}

@keyframes appear {
    0% {
        opacity: 0
    }

    100% {
        opacity: 1;
        z-index: 50;
    }
}
</style>
