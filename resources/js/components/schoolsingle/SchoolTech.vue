<script setup>
import LaptopGear from "@/js/components/svg/LaptopGear.vue";
import SchoolTechIconGenerator from "@/js/components/global/SchoolTechIconGenerator.vue";

const props = defineProps({
    techList:{
        type: Object,
        required: true
    }
})

</script>
<template>
    <div class="bg-white border-[0.5px] border-black px-5 py-8 flex flex-col relative overflow-hidden max-w-xs">
        <div class="absolute techListBackground -right-28 -top-24 ">
            <LaptopGear />
        </div>
        <div class="techListTitle font-semibold text-lg mb-5">
            Tech used at this school
        </div>
        <div
            v-for="(tech,index) in techList"
            :key="index"
            class="w-full flex pb-4 basis-1/4"
        >
            <SchoolTechIconGenerator
                :tech-name="tech.name"
                class="min-w-[60px] pr-4 m-2"
            />
            <p class="text-base font-light basis-3/4 ">
                {{ tech.description }}
            </p>
        </div>
    </div>
</template>
