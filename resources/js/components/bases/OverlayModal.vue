<script setup>
import {onBeforeUnmount, onMounted, ref} from "vue";

const props = defineProps({
    clickAway: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits(['close']);

// Disable page scrolling while modal is visible
onMounted(() => {
    document.body.style.overflowY = 'hidden';
})
// Enable page scrolling when modal is closed
onBeforeUnmount(() => {
    document.body.style.overflowY = 'auto';
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
    <div
        ref="overlayRef"
        class="bg-black/80 fixed top-0 right-0 bottom-0 left-0 flex justify-center items-center overlay p-10 z-50"
        @click="handleOverlayClick"
    >
        <slot name="content" />
    </div>
</template>

<style scoped>
</style>
