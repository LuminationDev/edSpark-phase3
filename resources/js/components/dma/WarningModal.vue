<script setup>
import OverlayModal from "@/js/components/dma/OverlayModal.vue";
import RoundButton from "@/js/components/dma/RoundButton.vue";

const props = defineProps({
    embed: {
        type: Boolean,
        default: false,
    }
})

const emit = defineEmits(['cancel','reset']);

</script>

<template>
    <OverlayModal
        :embed="props.embed"
        :z-index="101"
    >
        <div class="bg-white max-w-full p-5 rounded-3xl text-black w-96">
            <div><strong><slot name="title" /></strong></div>
            <p class="font-light">
                <slot name="message" />
            </p>
            <div class="flex justify-end flex-row gap-3 mt-5">
                <RoundButton @click="emit('cancel')">
                    Cancel
                </RoundButton>
                <RoundButton
                    color="orangered"
                    text-color="white"
                    @click="emit('reset')"
                >
                    Reset
                </RoundButton>
            </div>
        </div>
    </OverlayModal>
</template>
