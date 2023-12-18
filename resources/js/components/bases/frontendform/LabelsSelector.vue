<script setup lang="ts">
import {capitalize, onMounted, reactive, Ref, ref, watch} from 'vue'
import Multiselect from "vue-multiselect";

import {formService} from "@/js/service/formService";
import {GroupedLabel, LabelSelectorItem} from "@/js/types/GlobalLabelTypes";



const displayTextByLabelKey = {
    year: "Year Levels",
    learning: "Learning Areas",
    capability: 'General Capabilities',
    category: "Categories"
}

const props = defineProps({
    modelValue: {
        type: Object,
        required: true
    }
})


function groupByType(data: LabelSelectorItem[]): GroupedLabel {
    // Create an object to store grouped items
    const groupedData: GroupedLabel = {} as GroupedLabel;

    // Loop through the array and group items by their 'type'
    data.forEach((item) => {
        const { type, id, value, description } = item;

        // If the type doesn't exist in the groupedData object, create an array for it
        if (!groupedData[type]) {
            groupedData[type] = [];
        }

        // Push the item into the corresponding type array
        groupedData[type].push({id, value, name: capitalize(description) });
    });

    return groupedData;
}
const emits  = defineEmits(['emitSelectedOptions'])
const allLabels: Ref<LabelSelectorItem[] > = ref([])
const groupedLabels : Ref<GroupedLabel|null> = ref(null)
const selectedOptions = reactive(props.modelValue)

watch(selectedOptions, () =>{
    emits('emitSelectedOptions', selectedOptions)
})


onMounted(() =>{
    formService.fetchAllLabels().then(res =>{
        console.log(res.data.data)
        allLabels.value = res.data.data
        groupedLabels.value = groupByType(allLabels.value)
    })
})


</script>

<template>
    <div class="flex flex-col labelSelectors">
        <div class="selectorTitle">
            Pick appropriate label by type
        </div>
        <div class="flex justify-around items-center flex-row flex-wrap selectorRows">
            <div
                v-for="(value,key) in groupedLabels"
                :key="key"
                class="flex justify-center items-center flex-col min-h-[150px] p-6 w-72"
            >
                {{ displayTextByLabelKey[key] }}
                <Multiselect
                    :id="key + `selector`"
                    v-model="selectedOptions[key]"
                    :options="value"
                    :multiple="true"
                    :close-on-select="false"
                    track-by="name"
                    aria-expanded="false"
                    aria-controls="resourceResult"
                    label="name"
                    deselect-label="X"
                    select-label=""
                />
            </div>
        </div>
    </div>
</template>
<style scoped>
:deep(.multiselect__tag){
    background: #1D7982 !important;
}
</style>
