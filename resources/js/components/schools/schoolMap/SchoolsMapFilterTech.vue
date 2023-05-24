<script setup>
import { ref } from 'vue';
import axios from 'axios';

const schoolTechFilter = ref('');

const emits = defineEmits(['handleEmitTechFilter']);

const handleEmitTech = () => {
    emits('handleEmitTechFilter', schoolTechFilter.value);
};

const allTech = ref([]);
import {serverURL} from "@/js/constants/serverUrl";

    // const loadAllTech = async () => {
    //     await axios.get(`${serverURL}/fetchAllTechs`).then(response => {
    //         console.log(response);
    //     })
    // };
    //
    // loadAllTech();
</script>

<template>
    <div class="flex-1 w-[25%] flex flex-col">
        <label
            for="filterType"
            class="font-normal text-[18px] text-white"
        >Filter by tech:</label>
        <select
            id="filterType"
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
