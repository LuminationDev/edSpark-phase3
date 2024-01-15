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



const emits  = defineEmits(['emitSelectedOptions'])
const allLabels: Ref<LabelSelectorItem[] > = ref([])
const groupedLabels : Ref<GroupedLabel|null> = ref(null)
const selectedOptions = reactive(props.modelValue)

watch(selectedOptions, () =>{
    emits('emitSelectedOptions', selectedOptions)
})


onMounted(() =>{
    formService.fetchAllLabels().then(res =>{
        allLabels.value = res.data.data
        groupedLabels.value = formService.groupLabelByType(allLabels.value)
    })
})


</script>

<template>
    <div class="flex flex-col labelSelectors">
        <div class="grid grid-cols-2 gap-x-16 gap-y-8">
            <div
                v-for="(value,key) in groupedLabels"
                :key="key"
                class="flex justify-center items-center flex-col"
            >
                <span class="flex flex-start w-full">{{ displayTextByLabelKey[key] }} </span>
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
                    class="border-[1px] rounded w-full"
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
