<script setup>
import useSWRV from "swrv";
import {ref} from "vue";

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {axiosFetcher} from "@/js/helpers/fetcher";
import {useUserStore} from "@/js/stores/useUserStore";

const {
    data: partnerList,
    error: partnerError
} = useSWRV(API_ENDPOINTS.PARTNER.FETCH_ALL_PARTNERS, axiosFetcher(useUserStore().getUserRequestParam), swrvOptions)

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
        :hero-title="LandingHeroText['partner']['title']"
        :hero-subtitle="LandingHeroText['partner']['subtitle']"
        hero-background-color="navy"
    />
</template>
