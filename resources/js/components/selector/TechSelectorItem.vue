<script setup>
import {computed,ref} from 'vue'

import SchoolTechIconGenerator from "@/js/components/global/SchoolTechIconGenerator.vue";
import { schoolColorKeys,schoolColorTheme} from "@/js/constants/schoolColorTheme";

const props = defineProps({
    item: {
        type: Object, required: true
    },
    colorTheme:{
        type: String,
        required: false,
        default:'teal'
    }
})


</script>
<template>
    <div class="flex flex-row h-full p-2 rounded-xl text-black">
        <div class="basis-1/3 flex justify-center items-center">
            <SchoolTechIconGenerator
                :tech-name="item.name"
                :color-theme="colorTheme"
            />
        </div>
        <div class="basis-2/3 overflow-clip overflow-y-scroll techSelectorItemDescription">
            <p class="line-clamp-3">
                {{ `${item.name} ${item.description}` }}
            </p>
        </div>
    </div>
</template>
<style>

.techSelectorItemDescription::-webkit-scrollbar{
    display:none

}

.techSelectorItemDescription{
    -ms-overflow-style: none;
    scrollbar-width: none;

}

</style>
