<script setup>

import PrimaryActionButton from "@/js/components/dma/PrimaryActionButton.vue";
import TextButton from "@/js/components/dma/TextButton.vue";

const props = defineProps({
    theme: {
        type: String,
        required: true,
    },
    cornerControls: {
        type: Boolean,
        required: false,
    },
    blurBg: {
        type: Boolean,
        required: false,
    }
})

const emit = defineEmits(['primary', 'secondary']);

</script>

<template>
    <div
        class="cover-screen h-full w-full"
        :class="`CoverScreen-bg-${props.theme} ${props.blurBg ? 'bg-blur' :
            ''}`"
    >
        <div
            class="flex justify-start items-center flex-col gap-10 h-full pb-10 pt-20 px-3 w-full md:!px-24 md:!py-20"
        >
            <div class="flex justify-center items-center flex-1 w-full">
                <slot name="content" />
            </div>

            <div
                v-if="props.cornerControls"
                class="flex justify-between items-center w-full"
            >
                <TextButton
                    v-if="$slots.secondaryAction"
                    @click="emit('secondary')"
                >
                    <slot name="secondaryAction" />
                </TextButton>
                <span v-else />
                <PrimaryActionButton
                    v-if="$slots.primaryAction"
                    @click="emit('primary')"
                >
                    <slot name="primaryAction" />
                </PrimaryActionButton>
            </div>

            <div
                v-else
                class="flex justify-center items-center flex-col gap-10 w-full"
            >
                <PrimaryActionButton
                    v-if="$slots.primaryAction"
                    @click="emit('primary')"
                >
                    <slot name="primaryAction" />
                </PrimaryActionButton>
                <TextButton
                    v-if="$slots.secondaryAction"
                    @click="emit('secondary')"
                >
                    <slot name="secondaryAction" />
                </TextButton>
            </div>
        </div>
    </div>
</template>
