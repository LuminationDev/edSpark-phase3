<script setup>
import {computed,} from 'vue'

import ArrowRightIcon from "@/js/components/svg/ArrowRightIcon.vue";
import {getNRandomElementsFromArray} from "@/js/helpers/cardDataHelper";

const props = defineProps({
    resourceList: {
        type: Array,
        required: true
    },
    borderColor: {
        type: String,
        required: false,
        default: 'teal'
    },
    resourceClickCallback: {
        required: true,
        type: Function
    }
})

const handleClickResource = (id, title) => {
    props.resourceClickCallback(id, title)

}
const filteredResource = computed(() => {
    return getNRandomElementsFromArray(props.resourceList, 6)
})

const borderColorClass = computed(() => {
    switch (props.borderColor) {
    case 'teal':
        return 'border-main-teal hover:bg-main-teal'
    case 'purple':
        return 'border-secondary-grape hover:bg-secondary-grape'
    default:
        return 'border-main-teal hover:bg-main-teal'
    }
})


</script>

<template>
    <div class="grid grid-cols-1 gap-8 w-full lg:!grid-cols-2">
        <button
            v-for="(resource,index) in filteredResource"
            :key="index"
            class="border-2 cursor-pointer popularGuideItem px-8 py-4 rounded text-2xl hover:text-white w-full"
            :class="borderColorClass"
            @click="() => handleClickResource(resource.id, resource.title)"
        >
            <div class="flex justify-between flex-row w-full">
                <div class="h-8 overflow-hidden text-ellipsis text-start title w-full">
                    {{ resource.title }}
                </div>
                <div class="Arrow grid place-items-center">
                    <ArrowRightIcon />
                </div>
            </div>
        </button>
    </div>
</template>

