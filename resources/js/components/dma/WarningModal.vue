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
        <div
            class="bg-white flex flex-col gap-3 max-w-full p-7 rounded-2xl text-black w-[400px]"
        >
            <h2 class="font-semibold text-medium">
                <slot name="title" />
            </h2>
            <p class="text-base">
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
