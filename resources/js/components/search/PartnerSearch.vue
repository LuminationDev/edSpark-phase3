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

const {data: partnerList, error: partnerError} = useSWRV(API_ENDPOINTS.PARTNER.FETCH_ALL_PARTNERS, axiosFetcher, swrvOptions)

const filterObject = ref({})

const handleFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter => filter.value)
}

</script>

<template>
    <BaseSearch
        search-type="partner"
        :resource-list="partnerList"
        :live-filter-object="filterObject"
    />
</template>
