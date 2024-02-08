<script setup lang="ts">

const props = defineProps({
    disabled: {
        type: Boolean,
        required: false,
    },
});

const emit = defineEmits(['click'])

</script>
<template>
    <button
        class="bg-white font-bold p-2 px-5 rounded-full text-black hover:text-gray-700 text-lg uppercase"
        :class="{'opacity-50': disabled}"
        :disabled="disabled"
        @click="emit('click')"
    >
        <slot />
    </button>
</template>
<style></style>
