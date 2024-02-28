<script setup>
import useSWRV from "swrv";
import {ref} from "vue";
import {useRoute} from "vue-router";

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";
import LabelFiltersSearchPage from "@/js/components/search/LabelFiltersSearchPage.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {axiosFetcher} from "@/js/helpers/fetcher";
import router from "@/js/router";
import {useUserStore} from "@/js/stores/useUserStore";

const route = useRoute()


const {
    data: softwareList,
    error: softwareError
} = useSWRV(API_ENDPOINTS.SOFTWARE.FETCH_SOFTWARE_POSTS, axiosFetcher, swrvOptions)

const softwareFilterList = [
    {name: "No cost", value: "No cost"},
    {name: "Cyber Assessed", value: "Cyber Assessed"},
    {name: "Negotiated Deals", value:"Negotiated Deals"},
]

const filterObject = ref({})


// Handle Emit that is emitted by GenericMultiSelectFilter
const handleFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter => filter.value).flat(1)
}

const preselectedFilterObject = ref('');

if (route.params || route.params.filter) {
    switch (route.params.filter) {
    case "provided":
        preselectedFilterObject.value = {name: "Department Provided", value: "Department Provided"}
        break;
    case "approved":
        preselectedFilterObject.value = {name: "Department Approved", value: "Department Approved"}
        break;
    case "negotiated":
        preselectedFilterObject.value = {
            name: "Department Approved and Negotiated",
            value: "Approved and Negotiated"
        }
        break;
    default:
        router.push('/browse/software')
    }
}

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
