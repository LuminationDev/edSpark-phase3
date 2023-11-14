<script setup>
import { ref } from 'vue';

const schoolNameFilter = ref('');

const emits = defineEmits(['update:modelValue']);

const handleEmitFilterName = () => {
    emits('update:modelValue', schoolNameFilter.value);
};
</script>

<template>
    <div class="flex-1 w-auto max-w-[500px]">
        <label
            for="searchInput"
            class="font-normal mb-4 text-[18px] text-white"
        >School name</label>
        <input
            id="searchInput"
            class="!h-[50px] border-solid border-black"
            v-model="schoolNameFilter"
            type="text"
            placeholder="Search for a school"
            @input="handleEmitFilterName"
        >
    </div>
</template>
