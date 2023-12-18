<script setup lang="ts">

import {lowerSlugify} from "@/js/helpers/stringHelpers";
import {LabelSelectorItem} from "@/js/types/GlobalLabelTypes";


const props = defineProps({
    labelsArray :{
        type: Array as () => LabelSelectorItem[],
        required: true

    }
})
const labelColors = {
    year: 'banana',
    learning: 'cherry',
    capability: 'grape',
    category: 'blueberry',
};


const tagClassName = (type: string): string => {
    const baseColor = `bg-secondary-${labelColors[type]}/40 border-[1px] border-secondary-${labelColors[type]} px-6 py-2 rounded-full`;
    // return `${baseColor} transition-colors duration-300 hover:text-secondary-${labelColors[type]}`;
    return `${baseColor} transition-colors duration-300 hover:text-main-lightTeal`;
};


</script>

<template>
    <div class="flex flex-row gap-4 pt-4">
        <div
            v-for="(label,index) in props.labelsArray"
            :key="index"
        >
            <div
                class="cursor-pointer font-semibold"
                :class="tagClassName(label.type)"
            >
                {{ "#" + lowerSlugify(label.description) }}
            </div>
        </div>
    </div>
</template>
