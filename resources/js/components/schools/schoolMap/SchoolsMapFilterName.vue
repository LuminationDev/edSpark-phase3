<script setup>
import { ref } from 'vue';

const schoolNameFilter = ref('');

const emits = defineEmits(['update:modelValue']);

const handleEmitFilterName = () => {
    emits('update:modelValue', schoolNameFilter.value);
};
</script>

<template>
    <div class="flex-1 w-[50%]">
        <label
            for="searchInput"
            class="font-normal text-[18px] text-white mb-4"
        >Search by school name</label>
        <input
            id="searchInput"
            v-model="schoolNameFilter"
            type="text"
            placeholder="Search for a school"
            @input="handleEmitFilterName"
        >
    </div>
</template>
