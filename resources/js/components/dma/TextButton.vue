<script setup lang="ts">

const props = defineProps({
    class: {
        type: String,
        required: false,
        default: '',
    },
});

const emit = defineEmits(['click'])

</script>
<template>
    <button
        class="font-bold text-small active:hover:text-gray-200 hover:text-gray-100 md:text-base uppercase"
        :class="props.class"
        @click="emit('click')"
    >
        <slot />
    </button>
</template>
