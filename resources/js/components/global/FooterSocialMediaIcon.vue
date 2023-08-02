<script setup>
const props = defineProps({
    fillColor:{
        type: String,
        required: true,
    }
})
</script>

<template>
    <div
        class="border-[1px] border-white grid place-items-center h-10 p-2 rounded-lg w-10 hover:bg-white hover:cursor-pointer"
        :class="`fill-${props.fillColor}`"
    >
        <slot name="iconSVG" />
    </div>
</template>