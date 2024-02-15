<script setup>
import CloseButton from "@/js/components/dma/CloseButton.vue";
import OverlayModal from "@/js/components/dma/OverlayModal.vue";
import RoundButton from "@/js/components/dma/RoundButton.vue";

const props = defineProps({
    src: {
        type: String,
        required: true,
    }
})

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
        <div class="mt-24 w-full">
            <CloseButton
                class="absolute top-10 left-10 z-50"
                @click.stop="emit('close')"
            />
            <video
                class="rounded-3xl w-full"
                :src="src"
                controls
                autoplay
                @ended="emit('close')"
            />
        </div>
        <div class="flex-1 pointer-events-none" />
    </OverlayModal>
</template>
