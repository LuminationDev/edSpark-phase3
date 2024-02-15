<script setup>
import {FocusTrap} from "focus-trap-vue";
import {onBeforeUnmount, onMounted, ref, watch} from "vue";

const props = defineProps({
    clickAway: {
        type: Boolean,
        default: false,
    },
    zIndex: {
        type: Number,
        default: 50,
    },
    embed: {
        type: Boolean,
        default: false,
    }
})

const emit = defineEmits(['close']);

// Disable page scrolling while modal is visible
// (embedded modals do not block scrolling)
onMounted(() => {
    if(!props.embed) {
        // add a right inset to account for the scroll bar disappearing
        // if it was visible
        const scrollBarWidth = window.innerWidth - document.documentElement.clientWidth
        document.body.style.paddingRight = `${scrollBarWidth}px`;
        document.body.style.overflowY = 'hidden';
    }
})
// Enable page scrolling when modal is closed
onBeforeUnmount(() => {
    if(!props.embed) {
        document.body.style.overflowY = 'auto';
        document.body.style.paddingRight = '0px';
    }
})

const overlayRef = ref(null)

// If clickAway is true, clicking on the overlay outside the modal content will fire a close event
const handleOverlayClick = (event) => {
    if (props.clickAway) {
        if (event.target === overlayRef.value.children[0]) {
            emit('close');
        }
    }
}

</script>

<template>
    <div
        ref="overlayRef"
        class="dma-app-root fixed top-0 right-0 bottom-0 left-0"
        :class="embed ? 'absolute' : 'fixed'"
        :style="{zIndex: props.zIndex}"
        @click="handleOverlayClick"
    >
        <focus-trap
            active
            fallback-focus=".dma-app-root"
        >
            <div
                tab-index="-1"
                class="bg-black/80 flex justify-center items-center h-full overlay p-4 w-full md:!p-10"
            >
                <slot />
            </div>
        </focus-trap>
    </div>
</template>

<style scoped>
</style>
