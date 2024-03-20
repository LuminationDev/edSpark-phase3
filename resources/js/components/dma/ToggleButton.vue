<script setup lang="ts">

const props = defineProps({
    disabled: {
        type: Boolean,
        default: false,
    },
});

const selected = defineModel();

const handleClick = () => {
    selected.value = !selected.value;
}

</script>
<template>
    <button
        class="block border-2 border-black flex justify-center items-center h-6 mr-4 rounded-md w-6 active:bg-black/20 hover:bg-black/10"
        :disabled="props.disabled"
        @click="handleClick"
    >
        <i
            class="fa-check fas h-4 mt-[-2px] text-sm w-4"
            :class="{ 'opacity-0': !selected}"
        />
    </button>
</template>
<style></style>
