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
    <div
        v-if="isLoading"
        class="mt-6"
    >
        <Loader
            :loader-color="'#0072DA'"
            :loader-message="'Loading labels'"
            :loader-type="'small'"
        />
    </div>
    <template v-else>
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
    </template>
</template>
