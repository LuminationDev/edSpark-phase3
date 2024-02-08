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
        default: 'black',
    }
});

const emit = defineEmits(['click'])

</script>
<template>
    <button
        class="font-bold p-2 px-5 rounded-full text-lg hover:text-gray-700 uppercase hover:brightness-95"
        :class="{'opacity-50': disabled}"
        :style="{backgroundColor: props.color, color: props.textColor}"
        :disabled="disabled"
        @click="emit('click')"
    >
        <slot />
    </button>
</template>
<style></style>
