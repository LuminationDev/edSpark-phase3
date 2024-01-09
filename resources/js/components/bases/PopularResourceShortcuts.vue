<script setup>
import {computed,} from 'vue'

import {getNRandomElementsFromArray} from "@/js/helpers/cardDataHelper";

const props = defineProps({
    resourceList: {
        type: Array,
        required: true
    }
})

const handleClickResource = () =>{
    console.log('clciked on one please router me away')

}
const filteredResource = computed(() =>{
    return getNRandomElementsFromArray(props.resourceList, 6)
})


</script>

<template>
    <div class="grid grid-cols-2 gap-8 w-full">
        <button
            v-for="(resource,index) in filteredResource"
            :key="index"
            class="
                border-2
                border-main-teal
                cursor-pointer
                popularGuideItem
                px-8
                py-4
                rounded
                text-2xl
                hover:text-white
                w-full
                hover:bg-main-teal
                "
            @click="handleClickResource"
        >
            <div class="flex justify-between flex-row w-full">
                <div class="title">
                    {{ resource.title }}
                </div>
                <div class="Arrow">
                    ->
                </div>
            </div>
        </button>
    </div>
</template>

