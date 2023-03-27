<script>
    export default {

    }
</script>

<template>
    <div class="flex-1 w-[25%] flex flex-col">
        <label for="filterType" class="font-normal text-[18px] text-white">Filter by tech:</label>
        <select
            id="filterType"
            @change="$emit('update:modelValue', $event.target.value)"
            v-model="schoolTechFilter"
        >
            <!-- <option disabled selected value="">Select an option</option> -->
            <option value="">All</option>
            <option value="VR">VR</option>
            <option value="AR">AR</option>
            <option value="Drones">Drones</option>
            <option value="Robotics">Robotics</option>
            <option value="IoT">IoT</option>
        </select>
    </div>
</template>
