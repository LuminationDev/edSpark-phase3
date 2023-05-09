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
    <div class="grid grid-cols-4 gap-[24px] w-full px-20 pt-8 ">
        <div
            v-for="(school,index) in schools.splice(0,4)"
            :key="index"
            class="col-span-1 bg-white cursor-pointer h-[470px] border-[0.5px]  border-black transition-all group hover:shadow-2xl"
        >
            <SchoolCard
                :school-data="school"
            />
        </div>
    </div>
</template>
