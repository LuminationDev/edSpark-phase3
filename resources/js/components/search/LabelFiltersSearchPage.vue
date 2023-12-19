<script setup lang="ts">
import {computed, onMounted,Ref, ref} from 'vue'

import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import useIsLoading from "@/js/composables/useIsLoading";
import {formService} from "@/js/service/formService";
import {GroupedLabel, LabelSelectorItem} from "@/js/types/GlobalLabelTypes";


const allLabels: Ref<LabelSelectorItem[] > = ref([])
const groupedLabels : Ref<GroupedLabel|null> = ref(null)

const initFetchAllLabels = () : Promise<any> =>  {
    return formService.fetchAllLabels().then(res =>{
        console.log(res.data.data)
        allLabels.value = res.data.data
        groupedLabels.value = formService.groupLabelByType(allLabels.value)
    })
}

const {isLoading } = useIsLoading(initFetchAllLabels)

const emits = defineEmits(['emitFilterToIndividualSearchPage'])

const handleTransmittedFromGenericMultiSelectFilter = (selectedValue, dataPath) : void=> {
    emits('emitFilterToIndividualSearchPage', selectedValue, dataPath)
}
</script>

<template>
    <div class="">
        <div v-if="isLoading">
            <Loader
                :loader-color="'#0072DA'"
                :loader-message="'Loading labels'"
            />
        </div>
        <template v-else>
            <div class="grid grid-cols-2 gap-x-6 w-full">
                <div
                    v-for="(value,key) in groupedLabels"
                    :key="key"
                >
                    <GenericMultiSelectFilter
                        :id="'adviceFilter' + key"
                        :placeholder="'Filter by ' + key"
                        :filter-list="value"
                        data-path="label_value"
                        @transmit-selected-filters="handleTransmittedFromGenericMultiSelectFilter"
                    />
                </div>
            </div>
        </template>
    </div>
</template>
