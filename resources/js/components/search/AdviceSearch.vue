<script setup lang="ts">
import useSWRV from "swrv";
import { ref} from "vue";
import {useRoute, useRouter} from "vue-router";

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";
import LabelFiltersSearchPage from "@/js/components/search/LabelFiltersSearchPage.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {axiosFetcher} from "@/js/helpers/fetcher";

const route = useRoute()
const router = useRouter()

const {
    data: adviceList,
    error: adviceError
} = useSWRV(API_ENDPOINTS.ADVICE.FETCH_ADVICE_POSTS, axiosFetcher, swrvOptions)

const adviceFilterList = [
    {name: "Digital Adoption Group", value: "DAG"},
    {name: "Partner", value: "Partner"},
    {name: "Your Classroom", value: "Your Classroom"},
    {name: "Your Work", value: "Your Work"},
    {name: "Your Learning", value: "Your Learning"},
]

const filterObject = ref({})

const handleFilter = (filters, dataPath) => {
    console.log('handlefilter called ' + filters + dataPath)
    filterObject.value[dataPath] = filters.map(filter => filter.value).flat(1)
}


const preselectedFilterObject = ref(null as {name: string, value: string | string[]} | null);

if (route.params || route.params.filter) {
    switch (route.params.filter) {
    case "dag":
        preselectedFilterObject.value = {name: "Digital Adoption Group", value: "DAG"}
        break;
    case "educators":
        preselectedFilterObject.value = {name: "Educators", value: ["Your Classroom", "Your Work", "Your Learning"]}
        break;
    case "partner":
        preselectedFilterObject.value = {name: "Partner", value: "Partner"}
        break;
    default:
        router.push('/browse/guide')
    }
}
</script>

<template>
    <BaseSearch
        search-type="guide"
        :resource-list="adviceList"
        :live-filter-object="filterObject"
        :hero-title="LandingHeroText['guideSearch']['title']"
        :hero-subtitle="LandingHeroText['guideSearch']['subtitle']"
    >
        <template #filterBar>
            <GenericMultiSelectFilter
                id="adviceFilter"
                placeholder="Filter by type"
                :filter-list="adviceFilterList"
                data-path="type"
                :preselected="preselectedFilterObject"
                @transmit-selected-filters="handleFilter"
            />
        </template>
        <template #additionalFilters>
            <LabelFiltersSearchPage
                labels-type="advice"
                @emit-filter-to-individual-search-page="handleFilter"
            />
        </template>
    </BaseSearch>
</template>
