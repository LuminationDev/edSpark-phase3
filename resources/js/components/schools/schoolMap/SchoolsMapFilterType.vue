<script setup>
import { ref } from 'vue';

const schoolTypeFilter = ref('');

const emits = defineEmits(['update:modelValue']);

const handleEmitType = () => {
    emits('update:modelValue', schoolTypeFilter.value);
};
</script>

<template>
    <div class="flex-1 w-[25%] flex flex-col">
        <label
            for="filterTypeSchoolType"
            class="font-normal text-[18px] text-white"
        >Filter by type:</label>
        <select
            id="filterTypeSchoolType"
            v-model="schoolTypeFilter"
            @change="handleEmitType"
        >
            <!-- <option disabled selected value="">Select an option</option> -->
            <option value="All">
                All
            </option>
            <option value="High/Secondary Schools">
                High Schools
            </option>
            <option value="Primary Schools">
                Primary Schools
            </option>
            <option value="Area Schools">
                Area Schools
            </option>
            <option value="Aboriginal Schools">
                Aboriginal Schools
            </option>
            <option value="Primary/Secondary Special Schools">
                Primary/Secondary Special Schools
            </option>
            <option value="Primary/Secondary Schools">
                Primary/Secondary Schools
            </option>
            <option value="Primary Special Schools">
                Primary Special Schools
            </option>
        </select>
    </div>
</template>
