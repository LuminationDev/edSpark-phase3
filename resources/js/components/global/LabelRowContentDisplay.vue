<script setup lang="ts">

import {computed} from "vue";

import {lowerSlugify} from "@/js/helpers/stringHelpers";
import {LabelSelectorItem} from "@/js/types/GlobalLabelTypes";


const props = defineProps({
    labelsArray: {
        type: Array as () => LabelSelectorItem[],
        required: true
    },
    gapSize: {
        type: Number, required: false, default: 2
    },
    displayType: {
        type: String as "Card" | "Hero",
        required: false,
        default: 'Hero'
    }
})
const labelColors = {
    year: 'bg-secondary-blueberry/20 border-secondary-blueberry',
    learning: 'bg-secondary-blueberry/20 border-secondary-blueberry',
    capability: 'bg-secondary-blueberry/20 border-secondary-blueberry',
    category: 'bg-secondary-blueberry/20 border-secondary-blueberry',
};


const tagClassName = (type: string): string => {
    // return `${baseColor} transition-colors duration-300 hover:text-secondary-${labelColors[type]}`;
    // transition-colors duration-300 hover:text-main-lightTeal
    const fontColor = props.displayType == 'Card' ? 'text-secondary-blueberry h-[28px]  ' : 'text-white'
    return ` ${labelColors[type]} border-[1px] px-2 py-1 rounded-full text-sm ${fontColor}`;

};

const tailwindGapClass = computed(() => {
    return 'gap-' + props.gapSize
})

</script>

<template>
    <div
        class="flex flex-row flex-wrap h-auto max-w-full pt-4 text-white"
        :class="tailwindGapClass"
    >
        <div
            v-for="(label,index) in props.labelsArray"
            :key="index"
            class="font-medium"
            :class="tagClassName(label.label_type)"
        >
            {{ "#" + lowerSlugify(label.label_description) }}
        </div>
    </div>
</template>
