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
    }
})

const emit = defineEmits(['primary', 'secondary']);

</script>

<template>
    <div
        class="cover-screen flex justify-start items-center flex-col gap-10 h-full p-20 w-full"
        :class="`bg-${props.theme}-flat`"
    >
        <!-- TODO this can be one block with layout handled via tailwind classed -->

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
</template>
