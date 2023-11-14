<script setup>
import {ref, computed} from 'vue'
import { findNestedKeyValue} from "@/js/helpers/objectHelpers";
import TechSpecsRow from "@/js/components/hardware/TechSpecs/TechSpecsRow.vue";
const props = defineProps({
    extraContent:{
        type:Object, required: true
    }
})

const audioVisualTechSpecs = computed(() => {
    let finalContent
    const contents = findNestedKeyValue(props.extraContent, 'hardware_audio_visual_tech_specs')
    console.log(contents)
    for(const content of contents){
        if(Array.isArray(content['item'])){
            console.log(content['item'][0])
            finalContent = content['item'][0]
        }
    }
    return finalContent
})

</script>

<template>
    <div class="AudioVisualTechSpecsContainer flex flex-col w-full bg-gray-50 text-xl p-4">
        <template v-if="audioVisualTechSpecs">
            <div class="audioVisualTechSpecsTitle font-semibold text-3xl pb-6 ">
                Tech specs
            </div>
            <TechSpecsRow
                :value="audioVisualTechSpecs.screen"
                label="Screen size(s)"
            />
            <TechSpecsRow
                :value="audioVisualTechSpecs['number_student']"
                label="Number of students covered"
            />
            <TechSpecsRow
                :value="audioVisualTechSpecs.features"
                :is-list="true"
                label="Features"
            />
        </template>
        <template v-else>
            <div class="text-black">
                Sorry, tech specs are not available for this item
            </div>
        </template>
    </div>
</template>
