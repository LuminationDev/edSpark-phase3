<script setup>
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {useUserStore} from "@/js/stores/useUserStore";
import {ref} from "vue";
import BaseSearch from "@/js/components/search/BaseSearch.vue";
import useSWRV from "swrv";
import { axiosFetcherParams} from "@/js/helpers/fetcher";

const {data: partnerList, error: partnerError} = useSWRV(API_ENDPOINTS.PARTNER.FETCH_ALL_PARTNERS, axiosFetcherParams(useUserStore().getUserRequestParam), swrvOptions)

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
