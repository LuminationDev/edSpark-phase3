<script setup>
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {useUserStore} from "@/js/stores/useUserStore";
import {ref} from "vue";
import BaseSearch from "@/js/components/search/BaseSearch.vue";
import useSWRV from "swrv";
import {axiosSchoolFetcherParams} from "@/js/helpers/fetcher";

const {data: eventList, error: eventError} = useSWRV(API_ENDPOINTS.EVENT.FETCH_EVENT_POSTS, axiosSchoolFetcherParams(useUserStore().getUserRequestParam), swrvOptions)

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
