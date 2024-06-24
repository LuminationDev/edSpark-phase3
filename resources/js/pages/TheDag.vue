<script setup>
import {computed, onMounted, ref} from 'vue'

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import LabelFiltersSearchPage from "@/js/components/search/LabelFiltersSearchPage.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {adviceService} from "@/js/service/adviceService";

const props = defineProps({})

const emits = defineEmits([])
const dags = ref([])
const filterObject = ref({})


onMounted(async () => {
    dags.value = await adviceService.fetchDAG()
})
const handleFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter => filter.value).flat(1)
}

</script>

<template>
    <div>
        <BaseSearch
            search-type="dag"
            :resource-list="dags"
            :hero-title="LandingHeroText['dag']['title']"
            :hero-subtitle="LandingHeroText['dag']['subtitle']"
            :live-filter-object="filterObject"
        >
            <template #additionalFilters>
                <LabelFiltersSearchPage
                    labels-type="learning tasks"
                    @emit-filter-to-individual-search-page="handleFilter"
                />
            </template>
        </BaseSearch>
    </div>
</template>
