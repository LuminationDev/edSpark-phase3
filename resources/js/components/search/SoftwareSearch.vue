<script setup>
import axios from "axios";
import {onMounted, ref} from "vue";
import {useRoute} from "vue-router";

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";
import LabelFiltersSearchPage from "@/js/components/search/LabelFiltersSearchPage.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import router from "@/js/router";

const route = useRoute()

const softwareFilterList = [
    {name: "No cost", value: "No cost"},
    {name: "Cyber Assessed", value: "Cyber Assessed"},
    {name: "Negotiated Deals", value:"Negotiated Deals"},
]

const filterObject = ref({})

const handleFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter => filter.value).flat(1)
}

const preselectedFilterObject = ref('')

if (route.params || route.params.filter) {
    switch (route.params.filter) {
    case "provided":
        preselectedFilterObject.value = {name: "Department Provided", value: "Department Provided"}
        break
    case "approved":
        preselectedFilterObject.value = {name: "Department Approved", value: "Department Approved"}
        break
    case "negotiated":
        preselectedFilterObject.value = {
            name: "Department Approved and Negotiated",
            value: "Approved and Negotiated"
        }
        break
    default:
        router.push('/browse/software')
    }
}

onMounted(() => {
    fetchData(9,1)
})

const fetchData = async (perPage, currentPage) => {
    const body = {
        perPage: perPage,
        currentPage: currentPage
    }
    const response = await axios.post(API_ENDPOINTS.SOFTWARE.FETCH_SOFTWARE_POSTS, body).then(res =>res.data)
    console.log(response)
    return response
}

const handlePaginationChange = ({ perPage, currentPage }) => {
    fetchData(perPage, currentPage)
}

</script>

<template>
    <BaseSearch
        search-type="software"
        :live-filter-object="filterObject"
        :hero-title="LandingHeroText['apps']['title']"
        :hero-subtitle="LandingHeroText['apps']['subtitle']"
        hero-background-color="technologyPurple"
        @pagination-change="handlePaginationChange"
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
