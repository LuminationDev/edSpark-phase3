<script setup>
import CloseButton from "@/js/components/dma/CloseButton.vue";
import OverlayModal from "@/js/components/dma/OverlayModal.vue";

const emit = defineEmits(['close']);

</script>

<template>
    <OverlayModal
        vertical-align="top"
        :embed="true"
        :click-away="true"
        no-focus-trap
        @close="emit('close')"
    >
        <div class="mt-24 mx-4 md:!mx-0 md:w-full">
            <CloseButton
                class="absolute top-6 md:top-10 left-4 md:left-10 z-50"
                @click.stop="emit('close')"
            />
            <video
                class="w-full md:rounded-3xl"
                :src="src"
                controls
                autoplay
                @ended="emit('close')"
            />
        </div>
        <div class="flex-1 pointer-events-none" />
    </OverlayModal>
</template>
