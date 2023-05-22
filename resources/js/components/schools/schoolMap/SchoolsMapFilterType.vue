<script setup>
    import { ref } from 'vue';

    const schoolTypeFilter = ref('');

    const emits = defineEmits(['handleEmitTypeFilter']);

    const handleEmitType = () => {
        emits('handleEmitTypeFilter', schoolTypeFilter.value);
    }
</script>

<template>
    <div class="flex-1 w-[25%] flex flex-col">
        <label for="filterType" class="font-normal text-[18px] text-white">Filter by type:</label>
        <select
            id="filterType"
            @change="handleEmitType"
            v-model="schoolTypeFilter"
        >
            <!-- <option disabled selected value="">Select an option</option> -->
            <option value="All">All</option>
            <option value="High School">High Schools</option>
            <option value="Primary School">Primary Schools</option>
            <option value="Area School">Area Schools</option>
        </select>
    </div>
</template>
