<script setup>
import {computed } from 'vue'

import SchoolTechIconGenerator from "@/js/components/global/SchoolTechIconGenerator.vue";
import LaptopGear from "@/js/components/svg/LaptopGear.vue";
import { schoolColorKeys, schoolColorTheme } from "@/js/constants/schoolColorTheme";

const props = defineProps({
    techList:{
        type: Object,
        required: true
    },
    colorTheme: {
        type: String,
        required: true,
        default: 'teal',
    }
})

const deptTech = computed( () =>{
    return props.techList.filter(el => el.name !== 'Lumination' && el.name !== 'Makers Empire')
})

const partnerTech = computed(() => {
    return props.techList.filter(el => el.name === 'Lumination' || el.name === 'Makers Empire')
})

const customFill = computed(() => {
    if (schoolColorKeys.includes(props.colorTheme)) {
        return `fill-[${schoolColorTheme[props.colorTheme]['med']}] stroke-[${schoolColorTheme[props.colorTheme]['med']}]`;
    } else {
        return `fill-[${schoolColorTheme['teal']['med']}] stroke-[${schoolColorTheme['teal']['med']}]`;

    }
})

</script>
<template>
    <div class="bg-white border-[0.5px] border-black flex flex-col overflow-hidden px-4 py-4 relative w-full">
        <div class="absolute -top-24 -right-28 techListBackground">
            <LaptopGear />
        </div>
        <div class="font-bold mb-5 techListTitle text-xl">
            Tech used at this school
        </div>
        <template v-if="!deptTech.length && !partnerTech.length">
            They're keeping their tech toolkit under wraps!
        </template>
        <template v-if="deptTech.length">
            <div class="font-semibold mb-4 text-lg">
                Department supplied tech
            </div>
            <div
                v-for="(tech,index) in deptTech"
                :key="index"
                :class="customFill"
                class="basis-1/4 flex gap-4 mb-10 w-full"
            >
                <SchoolTechIconGenerator
                    :tech-name="tech.name"
                    :color-theme="colorTheme"
                />
                <p class="basis-3/4 font-light leading-tight text-base">
                    {{ `${tech.name} ${tech.description}` }}
                </p>
            </div>
        </template>
        <template v-if="partnerTech.length">
            <div class="font-semibold mb-4 text-lg">
                Partner supplied tech
            </div>
            <div
                v-for="(tech,index) in partnerTech"
                :key="index"
                :class="customFill"
                class="basis-1/4 flex gap-4 mb-10 w-full"
            >
                <SchoolTechIconGenerator
                    :tech-name="tech.name"
                    :color-theme="colorTheme"
                />
                <p class="basis-3/4 font-light leading-tight text-base">
                    {{ `${tech.name} ${tech.description}` }}
                </p>
            </div>
        </template>
    </div>
</template>
