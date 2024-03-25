<script setup>
import {onMounted, ref} from "vue";

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import LabelFiltersSearchPage from "@/js/components/search/LabelFiltersSearchPage.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {LandingHeroText} from "@/js/constants/PageBlurb";

const filterObject = ref({})

const currentPage = ref(1)
const perPage = ref(9)

const handleFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter => filter.value)
}

onMounted(() => {
    fetchData(perPage.value, currentPage.value);
});

const fetchData = async (perPage, currentPage) => {
    const response = await fetch(API_ENDPOINTS.EVENT.FETCH_EVENT_POSTS, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            perPage: perPage,
            currentPage: currentPage
        })
    });
    return await response.json();
};

const handlePaginationChange = ({ perPage, currentPage }) => {
    fetchData(perPage, currentPage);
};

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
