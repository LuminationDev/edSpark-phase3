<script setup>
import {computed,ref} from 'vue'

import CarouselGenerator from "@/js/components/card/CarouselGenerator.vue";

const props = defineProps({
    schoolList:{
        type: Array,
        required: true
    }
})

</script>

<template>
    <CarouselGenerator
        :show-count="3"
        data-type="school"
        :data-array="schoolList ? schoolList : []"
    />
</template>
