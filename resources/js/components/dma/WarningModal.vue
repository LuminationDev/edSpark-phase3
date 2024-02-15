<script setup>
import OverlayModal from "@/js/components/dma/OverlayModal.vue";
import RoundButton from "@/js/components/dma/RoundButton.vue";

const props = defineProps({
    embed: {
        type: Boolean,
        default: false,
    },
    showCancel: {
        type: Boolean,
        default: true,
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
            class="bg-white flex flex-col gap-3 m-4 max-w-[400px] p-7 rounded-2xl text-black sm:w-[400px] md:!m-10"
        >
            <h2 class="font-semibold text-medium">
                <slot name="title" />
            </h2>
            <p class="text-base">
                <slot name="message" />
            </p>
            <div class="flex justify-end flex-row gap-3 mt-5">
                <RoundButton
                    v-if="props.showCancel"
                    @click="emit('cancel')"
                >
                    Cancel
                </RoundButton>
                <RoundButton
                    color="orangered"
                    text-color="white"
                    @click="emit('reset')"
                >
                    <slot name="confirm">
                        OK
                    </slot>
                </RoundButton>
            </div>
        </div>
    </OverlayModal>
</template>
