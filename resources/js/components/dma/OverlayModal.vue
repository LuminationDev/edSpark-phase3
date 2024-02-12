<script setup>
import {FocusTrap} from "focus-trap-vue";
import {onBeforeUnmount, onMounted, ref} from "vue";

const props = defineProps({
    clickAway: {
        type: Boolean,
        default: false,
    },
    shade: {
        type: Number,
        default: 80,
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
        document.body.style.overflowY = 'hidden';
    }
})
// Enable page scrolling when modal is closed
onBeforeUnmount(() => {
    if(!props.embed) {
        document.body.style.overflowY = 'auto';
    }
})

const overlayRef = ref(null)

// If clickAway is true, clicking on the overlay outside the modal content will fire a close event
const handleOverlayClick = (event) => {
    if (props.clickAway) {
        if (event.target === overlayRef.value) {
            emit('close');
        }
    }
}

// TODO implement focus trap to restrict tabbing within the modal

</script>

<template>
    <focus-trap active>
        <div
            ref="overlayRef"
            tab-index="-1"
            class="fixed top-0 right-0 bottom-0 left-0 flex justify-center items-center overlay p-10"
            :class="`bg-black/${props.shade} ${embed ? 'absolute' : 'fixed'}`"
            :style="{zIndex: props.zIndex}"
            @click="handleOverlayClick"
        >
            <slot />
        </div>
    </focus-trap>
</template>

<style scoped>
</style>
