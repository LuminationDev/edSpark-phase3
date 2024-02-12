<script setup lang="ts">

const props = defineProps({
    hint: {
        type: String,
        required: false,
    },
    outline: {
        type: Boolean,
        required: false,
    }
})

const emit = defineEmits(['click'])

</script>
<template>
    <button
        class="flex justify-between items-center flex-row font-bold mt-3 p-5 rounded-xl text-lg text-white w-full"
        :class="outline ? 'bg-black border-2 border-gray-800 hover:border-gray-700' : 'bg-gray-800 hover:bg-gray-700'"
        @click="emit('click')"
    >
        <slot />
        <span
            v-if="props.hint"
            class="font-light hint text-gray-500 text-sm"
        >{{ props.hint }}</span>
    </button>
</template>
<style></style>
