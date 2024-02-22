<script setup lang="ts">

const props = defineProps({
    disabled: {
        type: Boolean,
        required: false,
    },
    color: {
        type: String,
        required: false,
        default: 'white',
    },
    textColor: {
        type: String,
        required: false,
        default: 'var(--color-black-0)',
    }
});

</script>
<template>
    <button
        class="p-2 px-7 rounded-full text-button hover:active:brightness-75 hover:brightness-90"
        :class="{'opacity-50': disabled}"
        :style="{backgroundColor: props.color, color: props.textColor}"
        :disabled="disabled"
    >
        <slot />
    </button>
</template>
<style></style>
