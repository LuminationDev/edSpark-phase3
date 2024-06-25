<script setup>
import useSWRV from "swrv";
import {onMounted, ref} from "vue";
import {useRoute} from "vue-router";

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";
import LabelFiltersSearchPage from "@/js/components/search/LabelFiltersSearchPage.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {axiosFetcher} from "@/js/helpers/fetcher";
import router from "@/js/router";
import {softwareService} from "@/js/service/softwareService";

const route = useRoute()


const {
    data: softwareList,
    error: softwareError
} = useSWRV(API_ENDPOINTS.SOFTWARE.FETCH_SOFTWARE_POSTS, axiosFetcher, swrvOptions)
const isLoadingFilters = ref(true)

const softwareFilterList = ref([])

const filterObject = ref({})

onMounted(async () => {
    softwareFilterList.value = await softwareService.fetchSoftwareTypes();
    if (route.params || route.params.filter) {
        switch (route.params.filter) {
        case "nocost":
            preselectedFilterObject.value = {name: "No cost", value: "No cost"}
            break;
        case "assessed":
            preselectedFilterObject.value = {name: "Cyber Assessed", value: "Cyber Assessed"}
            break;
        case "negotiated":
            preselectedFilterObject.value = {
                name: "Negotiated Deals",
                value: "Negotiated Deals"
            }
            break;
        default:
            router.push('/browse/software')        }
    }
    isLoadingFilters.value = false

})


/*
    {name: "No cost", value: "No cost"},
    {name: "Cyber Assessed", value: "Cyber Assessed"},
    {name: "Negotiated Deals", value:"Negotiated Deals"},
 */

// Handle Emit that is emitted by GenericMultiSelectFilter
const handleFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter => filter.value).flat(1)
}

const preselectedFilterObject = ref('');


</script>

<template>
    <BaseSearch
        search-type="software"
        :resource-list="softwareList"
        :live-filter-object="filterObject"
        :hero-title="LandingHeroText['apps']['title']"
        :hero-subtitle="LandingHeroText['apps']['subtitle']"
        hero-background-color="technologyPurple"
    >
        <template #filterBar>
            <GenericMultiSelectFilter
                v-if="!isLoadingFilters"
                id="softwareType"
                placeholder="Filter by type"
                :filter-list="softwareFilterList"
                data-path="type"
                :preselected="preselectedFilterObject"
                @transmit-selected-filters="handleFilter"
            />
        </template>
        <template #additionalFilters>
            <LabelFiltersSearchPage @emit-filter-to-individual-search-page="handleFilter" />
        </template>
    </BaseSearch>
</template>
