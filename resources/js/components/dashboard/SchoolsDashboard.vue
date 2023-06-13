<script setup>

import SchoolCard from '../schools/SchoolCard.vue';

const props = defineProps({
    schools: {
        type: Array,
        required: true
    }
});


</script>

<template>
    <div class="grid grid-cols-4 gap-[24px] w-full py-8 px-huge">
        <div
            v-for="(school,index) in schools.splice(0,4)"
            :key="index"
        >
            <SchoolCard
                :school-data="school"
            />
        </div>
    </div>
</template>
