<script setup>

import { computed,ref } from 'vue'

const props = defineProps({
    techUsedList: {
        type: Array,
        required: true
    },
    colorTheme: {
        type: String,
        required: true,
        default: 'teal',
    }
})


import SchoolTechIconGenerator from "@/js/components/global/SchoolTechIconGenerator.vue";
import { schoolColorKeys, schoolColorTheme } from "@/js/constants/schoolColorTheme";

const toggleTooltip = ref(false);
const tooltipIndex = ref(null);

const handleToggleTooltip = (index) => {
    toggleTooltip.value = !toggleTooltip.value;
    tooltipIndex.value = index;
}

const customFill = computed(() => {
    console.log("Got "+props.colorTheme);
    // if (props.colorTheme == 'cherry' || props.colorTheme == 'banana' || props.colorTheme == 'teal' || props.colorTheme == 'peach') {
    //     return `fill-[${schoolColorTheme[props.colorTheme]['dark']}] stroke-[${schoolColorTheme[props.colorTheme]['dark']}]`;

    // } else 
    if (props.colorTheme == 'navy') {
        return `fill-[${schoolColorTheme[props.colorTheme]['med']}] stroke-[${schoolColorTheme[props.colorTheme]['med']}]`;

    } else   
    if (schoolColorKeys.includes(props.colorTheme)) {
        return `fill-[${schoolColorTheme[props.colorTheme]['dark']}] stroke-[${schoolColorTheme[props.colorTheme]['dark']}]`;

    } else {
        return `fill-[${schoolColorTheme['teal']['dark']}] stroke-[${schoolColorTheme['teal']['dark']}]`;
    }
})

</script>

<template>
    <div
        v-for="(tech, index) in props.techUsedList"
        :key="index"
        class="cursor-pointer flex-wrap iconColours min-w-fit relative w-6 z-40 md:!w-14 lg:!block"
        :class="{'z-50' : toggleTooltip && tooltipIndex === index}"
    >
        <div
            :class="customFill"
            @mouseenter="handleToggleTooltip(index)"
            @mouseleave="handleToggleTooltip(index)"
        >
            <SchoolTechIconGenerator
                :tech-name="tech.name"
            />
            <div
                v-if="toggleTooltip && tooltipIndex === index"
                class="absolute bg-main-navy border-l-[3px] border-white px-[24px] py-[18px] shadow-xl text-sm w-[350px] z-50"
            >
                <span class="font-normal text-white">
                    <span class="capitalize font-bold pr-1 text-base">
                        {{ tech.name }}
                    </span>
                    {{ tech.description }}
                </span>
            </div>
        </div>
    </div>
</template>

