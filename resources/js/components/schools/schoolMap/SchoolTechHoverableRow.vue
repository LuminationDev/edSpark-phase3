<script setup>

import { ref, computed } from 'vue'

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
    if (schoolColorKeys.includes(props.colorTheme)) {
        return `fill-[${schoolColorTheme[props.colorTheme]['med']}] stroke-[${schoolColorTheme[props.colorTheme]['med']}]`;
    } else {
        return `fill-[${schoolColorTheme['teal']['med']}] stroke-[${schoolColorTheme['teal']['med']}]`;

    }
})

</script>

<template>
    <div v-for="(tech, index) in props.techUsedList" :key="index"
        class="cursor-pointer relative w-6 md:!w-14 lg:!block iconColours z-30 min-w-fit flex-wrap">
        <div @mouseenter="handleToggleTooltip(index)" @mouseleave="handleToggleTooltip(index)" :class="customFill">
            <SchoolTechIconGenerator :tech-name="tech.name"
                class="cursor-pointer m-2 min-w-[30px] pr-1 relative w-8 md:!min-w-[60px] md:!pr-4" />
            <div v-if="toggleTooltip && tooltipIndex === index"
                class="absolute text-base bg-main-navy border-l-[3px] border-white px-[24px] py-[18px] shadow-xl w-[450px]">

                <span class="font-normal text-white">
                    <span class="font-bold text-lg pr-1 capitalize">
                        {{ tech.name }}
                    </span>
                    {{ tech.description }}
                </span>
            </div>
        </div>
    </div>
</template>

