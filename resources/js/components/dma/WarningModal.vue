<script setup>
import OverlayModal from "@/js/components/bases/OverlayModal.vue";
import RoundButton from "@/js/components/dma/RoundButton.vue";

const emit = defineEmits(['cancel','reset']);
</script>

<template>
    <OverlayModal
        :z-index="60"
        :shade="60"
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
