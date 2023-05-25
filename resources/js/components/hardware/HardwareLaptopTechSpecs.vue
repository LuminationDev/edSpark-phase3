<script setup>
import {ref, computed} from 'vue'
import {findNestedKeyValue} from "@/js/helpers/objectHelpers";
import TechSpecsRow from "@/js/components/hardware/TechSpecs/TechSpecsRow.vue";

const props = defineProps({
    extraContent: {
        type: Object,
        required: true
    }
})

/**
 * {
 *     graphics: String
 *     processor: String
 *     screen: String
 *     memory: String
 *     os: String
 *     storage: String
 *     features: String ( comma seperated)
 * }
 * sample
 *  {
 *   "os": "Windows",
 *   "memory": "32 GB, 64 GB",
 *   "screen": "14 inches, 15.6 inches",
 *   "graphic": "RTX 4090Ti, Quadro 6900X",
 *   "storage": "8TB, 16TB",
 *   "features": "Most powerful laptop in the world, Passive cooling only, Ultra compatibility, Ultra portability, 12 hrs of battery under heavy use such as training LLMs",
 *   "processor": "i9 13900HK (5.50 Ghz), i7 13750H (5.30 Ghz)"
 *   }
 * @type {ComputedRef<*>}
 */
const laptopTechSpecs = computed(() => {
    const contents = findNestedKeyValue(props.extraContent, 'hardware_laptop_tech_specs')
    console.log(contents)
    let finalContent;
    for (const content of contents) {
        if (Array.isArray(content['item'])) {
            console.log(content['item'][0])
            finalContent = content['item'][0]
        }
    }
    return finalContent
})


</script>

<template>
    <div class="laptopTechSpecsContainer flex flex-col w-full bg-gray-50 text-xl p-4">
        <template v-if="laptopTechSpecs">
            <div class="laptopTechSpecsTitle font-semibold text-3xl pb-6 ">
                Tech Specs
            </div>
            <TechSpecsRow
                :value="laptopTechSpecs.processor"
                label="Processor"
            />
            <TechSpecsRow
                :value="laptopTechSpecs.graphic"
                label="Graphics"
            />
            <TechSpecsRow
                :value="laptopTechSpecs.memory"
                label="Memory"
            />
            <TechSpecsRow
                :value="laptopTechSpecs.storage"
                label="Storage"
            />
            <TechSpecsRow
                :value="laptopTechSpecs.screen"
                label="Screen size(s)"
            />
            <TechSpecsRow
                :value="laptopTechSpecs.features"
                label="Features"
            />
        </template>
        <template v-else>
            <div class="text-black">
                Sorry Tech Specs is not available for this item
            </div>
        </template>
    </div>
</template>
