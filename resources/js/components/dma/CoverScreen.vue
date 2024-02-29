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
    },
    disabled: {
        type: Boolean,
        default: false,
    }
})

const emit = defineEmits(['primary', 'secondary']);

</script>

<template>
    <div
        class="cover-screen flex min-h-full w-full md:h-full"
        :class="`CoverScreen-bg-${props.theme} ${props.blurBg ? 'bg-blur' :
            ''}`"
    >
        <div
            class="flex justify-start items-center flex-col gap-10 pb-10 pt-32 px-6 w-full md:!pt-20 md:!px-24 md:!py-20 md:h-full"
        >
            <div class="flex justify-center items-center flex-1 w-full md:h-0">
                <slot name="content" />
            </div>

            <div
                v-if="props.cornerControls"
                class="flex md:justify-between items-center flex-col gap-6 w-full md:!flex-row"
            >
                <PrimaryActionButton
                    v-if="$slots.primaryAction"
                    class="order-0 md:order-1"
                    :disabled="disabled"
                    @click="emit('primary')"
                >
                    <slot name="primaryAction" />
                </PrimaryActionButton>
                <TextButton
                    v-if="$slots.secondaryAction"
                    :disabled="disabled"
                    @click="emit('secondary')"
                >
                    <slot name="secondaryAction" />
                </TextButton>
                <span
                    v-else
                    class="hidden md:!block"
                />
            </div>

            <div
                v-else
                class="flex justify-center items-center flex-col gap-6 w-full md:!gap-10"
            >
                <PrimaryActionButton
                    v-if="$slots.primaryAction"
                    :disabled="disabled"
                    @click="emit('primary')"
                >
                    <slot name="primaryAction" />
                </PrimaryActionButton>
                <TextButton
                    v-if="$slots.secondaryAction"
                    :disabled="disabled"
                    @click="emit('secondary')"
                >
                    <slot name="secondaryAction" />
                </TextButton>
            </div>
        </div>
    </div>
</template>
