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
    year: 'bg-secondary-banana/40 border-secondary-banana',
    learning: 'bg-secondary-cherry/40 border-secondary-cherry',
    capability: 'bg-secondary-grape/40 border-secondary-grape' ,
    category: 'bg-secondary-blueberry/40 border-secondary-blueberry',
};


const tagClassName = (type: string): string => {
    const baseColor = ` ${labelColors[type]} border-[1px]  px-6 py-2 rounded-full`;
    // return `${baseColor} transition-colors duration-300 hover:text-secondary-${labelColors[type]}`;
    return `${baseColor} transition-colors duration-300 hover:text-main-lightTeal`;
};


</script>

<template>
    <div class="flex flex-row flex-wrap gap-4 h-auto max-w-full pt-4 text-white">
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
