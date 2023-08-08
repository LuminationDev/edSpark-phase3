<script setup>
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {ref} from "vue";
import {serverURL} from "@/js/constants/serverUrl";
import BaseSearch from "@/js/components/search/BaseSearch.vue";
import useSWRV from "swrv";
import {axiosFetcher} from "@/js/helpers/fetcher";

const swrvOptions = {
    revalidateOnFocus: false, // disable refresh on every focus, suspect its too often
    refreshInterval: 30000 // refresh or revalidate data every 30 secs
}

const {data: eventList, error: eventError} = useSWRV(API_ENDPOINTS.EVENT.FETCH_EVENT_POSTS, axiosFetcher, swrvOptions)

const filterObject = ref({})

const handleFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter => filter.value)
}

</script>

<template>
    <BaseSearch
        search-type="event"
        :resource-list="eventList"
        :live-filter-object="filterObject"
    />
</template>
