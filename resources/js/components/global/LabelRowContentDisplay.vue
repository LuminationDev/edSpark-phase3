<script setup lang="ts">

import {computed} from "vue";

import {lowerSlugify} from "@/js/helpers/stringHelpers";
import {LabelSelectorItem} from "@/js/types/GlobalLabelTypes";


const props = defineProps({
    labelsArray :{
        type: Array as () => LabelSelectorItem[],
        required: true
    },
    gapSize:{
        type: Number, required: false , default: 4
    }
})
const labelColors = {
    year: 'bg-secondary-banana/40 border-secondary-banana',
    learning: 'bg-secondary-cherry/40 border-secondary-cherry',
    capability: 'bg-secondary-grape/40 border-secondary-grape' ,
    category: 'bg-secondary-blueberry/40 border-secondary-blueberry',
};


const tagClassName = (type: string): string => {
    // return `${baseColor} transition-colors duration-300 hover:text-secondary-${labelColors[type]}`;
    // transition-colors duration-300 hover:text-main-lightTeal
    return ` ${labelColors[type]} border-[1px]  px-6 py-2 rounded-full`;

};

const tailwindGapClass  = computed(() =>{
    if(props.gapSize){
        return 'gap-' + props.gapSize
    } else{
        return 'gap-4'
    }
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
        >
            <div
                class="font-semibold"
                :class="tagClassName(label.label_type)"
            >
                {{ "#" + lowerSlugify(label.label_description) }}
            </div>
        </div>
    </div>
</template>
