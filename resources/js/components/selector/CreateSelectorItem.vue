<script setup>

const props = defineProps({
    bgColorClass:{
        type: String,
        required: true
    },
    clickCallback:{
        type: Function,
        required: true
    },
    disabled: {
        type: Boolean,
        required: false,
        default: false
    }
})
</script>

<template>
    <div
        class="flex justify-center items-center font-semibold h-32 rounded-2xl text-white text-xl w-40 hover:cursor-pointer"
        :class="{
            [props.bgColorClass]: true,
            'grayscale ': props.disabled
        }
        "
        @click="props.clickCallback"
    >
        <div class="absolute z-10">
            <slot name="backgroundImage" />
        </div>
        <div class="z-20">
            <slot name="selectorText" />
        </div>
    </div>
</template>
