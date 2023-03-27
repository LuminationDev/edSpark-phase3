<script>
    export default {
        setup() {

        }

    }
</script>

<template>
   <div class="flex-1 w-[25%] flex flex-col">
        <label for="filterType" class="font-normal text-[18px] text-white">Filter by type:</label>
        <select
            id="filterType"
            @change="$emit('update:modelValue', $event.target.value)"
            v-model="schoolTypeFilter"
        >
            <!-- <option disabled selected value="">Select an option</option> -->
            <option value="">All</option>
            <option value="High">High Schools</option>
            <option value="Primary">Primary Schools</option>
            <option value="Area">Area Schools</option>
        </select>
    </div>
</template>
