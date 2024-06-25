<script setup>
import useSWRV from "swrv";
import {onMounted, ref} from "vue";

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";
import LabelFiltersSearchPage from "@/js/components/search/LabelFiltersSearchPage.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {axiosFetcher} from "@/js/helpers/fetcher";
import {eventService} from "@/js/service/eventService";

const {data: eventList, error: eventError} = useSWRV(API_ENDPOINTS.EVENT.FETCH_EVENT_POSTS, axiosFetcher, swrvOptions)
const isLoadingFilters = ref(true)
const eventTypesFilterList = ref([]);
const eventFormatsFilterList = ref([]);
const preselectedFilterObject = ref('');

const filterObject = ref({})

const handleFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter => filter.value)
}

onMounted(async () => {
    eventTypesFilterList.value = await eventService.fetchEventTypes();
    eventFormatsFilterList.value = await eventService.fetchEventFormats();
    isLoadingFilters.value = false
    console.log(eventTypesFilterList.value)
})


</script>

<template>
    <BaseSearch
        search-type="event"
        :resource-list="eventList"
        :live-filter-object="filterObject"
        :hero-title="LandingHeroText['eventSearch']['title']"
        :hero-subtitle="LandingHeroText['eventSearch']['subtitle']"
        hero-background-color="partnerBlue"
    >
        <template #filterBar>
            <GenericMultiSelectFilter
                v-if="!isLoadingFilters"
                id="eventType"
                placeholder="Filter by type"
                :filter-list="eventTypesFilterList"
                data-path="type"
                :preselected="preselectedFilterObject"
                @transmit-selected-filters="handleFilter"
            />
            <GenericMultiSelectFilter
                v-if="!isLoadingFilters"

                id="eventFormat"
                placeholder="Filter by format"
                :filter-list="eventFormatsFilterList"
                data-path="format"
                :preselected="preselectedFilterObject"
                @transmit-selected-filters="handleFilter"
            />
        </template>
        <template #additionalFilters>
            <LabelFiltersSearchPage @emit-filter-to-individual-search-page="handleFilter" />
        </template>
    </BaseSearch>
</template>
