<script setup>
import { ref } from 'vue';
import axios from 'axios';

const schoolTechFilter = ref('');

const emits = defineEmits(['update:modelValue']);

const handleEmitTech = () => {
    emits('update:modelValue', schoolTechFilter.value);
};

</script>

<template>
    <div class="flex-1 w-[25%] flex flex-col">
        <label
            for="filterTypeTech"
            class="font-normal text-[18px] text-white"
        >Filter by tech:</label>
        <select
            id="filterTypeTech"
            v-model="schoolTechFilter"
            @change="handleEmitTech"
        >
            <!-- <option disabled selected value="">Select an option</option> -->
            <option value="All">
                All
            </option>
            <option value="VR">
                VR
            </option>
            <option value="AR">
                AR
            </option>
            <option value="Drones">
                Drones
            </option>
            <option value="Robotics">
                Robotics
            </option>
            <option value="IoT">
                IoT
            </option>
        </select>
    </div>
</template>
