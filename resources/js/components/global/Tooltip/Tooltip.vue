<script setup>
defineProps({
    tip: {
        type: String,
        required: true
    },

    toolTipMargin: {
        type: String,
        default: '-ml-1.5'
    }
})
</script>

<template>
    <span
        :class="toolTipMargin"
        class="tooltip rounded shadow-lg px-3 py-1 bg-gray-700 rounded-xl text-white -mt-8"
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
