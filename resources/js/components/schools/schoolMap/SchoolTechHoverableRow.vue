<script setup>
import {ref} from 'vue'

const props = defineProps({
    techUsedList :{
        type: Array,
        required: true
    }
})

import SchoolTechIconGenerator from "@/js/components/global/SchoolTechIconGenerator.vue";

const toggleTooltip = ref(false);
const tooltipIndex = ref(null);

const handleToggleTooltip = (index) => {
    toggleTooltip.value = !toggleTooltip.value;
    tooltipIndex.value = index;
}
</script>

<template>
    <div
        v-for="(tech, index) in props.techUsedList"
        :key="index"
        class="cursor-pointer hidden relative w-6 md:!w-14 lg:!block"
    >
        <div
            @mouseenter="handleToggleTooltip(index)"
            @mouseleave="handleToggleTooltip(index)"
        >
            <SchoolTechIconGenerator
                :tech-name="tech.name"
                class="cursor-pointer m-2 min-w-[30px] pr-1 relative w-8 md:!min-w-[60px] md:!pr-4"
            />
            <div
                v-if="toggleTooltip && tooltipIndex === index"
                class="absolute bg-main-navy border-l-[3px] border-white px-[24px] py-[18px] shadow-xl w-[450px]"
            >
                <h3
                    class="font-semibold text-[20px] text-white"
                >
                    {{ tech.name }}
                </h3>
                <p
                    class="font-normal text-sm text-white xl:!text-base"
                >
                    {{ tech.description }}
                </p>
            </div>
        </div>
    </div>
</template>
