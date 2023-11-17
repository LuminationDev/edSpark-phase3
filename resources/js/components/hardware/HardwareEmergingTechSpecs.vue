<script setup>
import { computed} from 'vue'
import { findNestedKeyValue} from "@/js/helpers/objectHelpers";
import TechSpecsRow from "@/js/components/hardware/TechSpecs/TechSpecsRow.vue";
const props = defineProps({
    extraContent:{
        type:Object, required: true
    }
})
console.log(props.extraContent)

const emergingTechTechSpecs = computed(() => {
    let finalContent
    const contents = findNestedKeyValue(props.extraContent, 'hardware_emerging_tech_tech_specs')
    console.log(contents)
    for(const content of contents){
        if(Array.isArray(content['item'])){
            console.log(content['item'][0])
            finalContent = content['item'][0]
        }
    }
    return finalContent
})

const techAreasToString = computed(() =>{
    console.log(emergingTechTechSpecs.value.tech_area.toString())
    return emergingTechTechSpecs.value.tech_area.toString()
})

</script>

<template>
    <div class="emergingTechSpecsContainer flex flex-col w-full bg-gray-50 text-xl p-4">
        <template v-if="emergingTechTechSpecs">
            <div class="audioVisualTechSpecsTitle font-semibold text-3xl pb-6 ">
                Tech specs
            </div>
            <TechSpecsRow
                :value="emergingTechTechSpecs.units"
                label="Number of units per set"
            />
            <TechSpecsRow
                :value="techAreasToString"
                label="Technology areas"
                :is-list="true"
            />
            <TechSpecsRow
                :value="emergingTechTechSpecs.features"
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
