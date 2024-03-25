<script setup>
import axios from "axios";
import {onMounted, ref} from "vue";

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import LabelFiltersSearchPage from "@/js/components/search/LabelFiltersSearchPage.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {LandingHeroText} from "@/js/constants/PageBlurb";

const filterObject = ref({})

const handleFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter => filter.value)
}

onMounted(() => {
    fetchData(9, 1)
})

const fetchData = async (perPage, currentPage) => {
    const body = {
        perPage: perPage,
        currentPage: currentPage
    }
    const response = await axios.post(API_ENDPOINTS.EVENT.FETCH_EVENT_POSTS, body).then(res =>res.data)
    console.log(response)
    return response
}

const handlePaginationChange = ({ perPage, currentPage }) => {
    fetchData(perPage, currentPage)
}

</script>

<template>
    <BaseSearch
        search-type="event"
        :live-filter-object="filterObject"
        :hero-title="LandingHeroText['eventSearch']['title']"
        :hero-subtitle="LandingHeroText['eventSearch']['subtitle']"
        hero-background-color="partnerBlue"
        @pagination-change="handlePaginationChange"
    >
        <template #additionalFilters>
            <LabelFiltersSearchPage @emit-filter-to-individual-search-page="handleFilter" />
        </template>
    </BaseSearch>
</template>
