<script setup>
import {computed } from 'vue'
import LaptopGear from "@/js/components/svg/LaptopGear.vue";
import SchoolTechIconGenerator from "@/js/components/global/SchoolTechIconGenerator.vue";

const props = defineProps({
    techList:{
        type: Object,
        required: true
    }
})

const deptTech = computed( () =>{
    return props.techList.filter(el => el.name !== 'Lumination' && el.name !== 'Makers Empire')
})

const partnerTech  =computed(() => {
    return props.techList.filter(el => el.name == 'Lumination' || el.name == 'Makers Empire')
})

</script>
<template>
    <div class="bg-white border-[0.5px] border-black px-4 py-4 flex flex-col relative overflow-hidden">
        <div class="absolute techListBackground -right-28 -top-24 ">
            <LaptopGear />
        </div>
        <div class="techListTitle font-semibold text-lg mb-5">
            Tech used at this school
        </div>
        <div class="text-lg font-semibold underline mb-2">
            Department Supplied Tech
        </div>
        <div
            v-for="(tech,index) in deptTech"
            :key="index"
            class="w-full flex pb-4 mb-4 basis-1/4"
        >
            <SchoolTechIconGenerator
                :tech-name="tech.name"
                class="min-w-[60px] pr-4 m-2"
            />
            <p class="text-base font-light basis-3/4 ">
                {{ `${tech.name} ${tech.description}` }}
            </p>
        </div>
        <div class="text-lg font-semibold underline mb-2">
            Partner Supplied Tech
        </div>
        <div
            v-for="(tech,index) in partnerTech"
            :key="index"
            class="w-full flex pb-4 mb-4 basis-1/4"
        >
            <SchoolTechIconGenerator
                :tech-name="tech.name"
                class="min-w-[60px] pr-4 m-2"
            />
            <p class="text-base font-light basis-3/4 ">
                {{ `${tech.name} ${tech.description}` }}
            </p>
        </div>
    </div>
</template>
