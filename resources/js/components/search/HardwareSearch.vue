<script setup>

import axios from 'axios'
import useSWRV from "swrv";
import {onMounted, ref} from "vue";

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {axiosFetcher} from "@/js/helpers/fetcher";
import {useUserStore} from "@/js/stores/useUserStore";

console.log('inside hardware search')

const resourceUrl = API_ENDPOINTS.HARDWARE.FETCH_HARDWARE_POSTS
const {data: hardwareList, error} = useSWRV(resourceUrl, axiosFetcher(useUserStore().getUserRequestParam), swrvOptions)

const brandList = ref([])
const categoryList = ref([])

onMounted(() => {
    axios({
        method: "GET",
        url: API_ENDPOINTS.HARDWARE.FETCH_ALL_BRANDS
    }).then(res => {
        console.log(res.data)
        brandList.value = res.data.map(filter => {
            return ({name: filter.brandName, value: filter.brandName})
        })
    })

    axios({
        method: "GET",
        url: API_ENDPOINTS.HARDWARE.FETCH_ALL_CATEGORIES
    }).then(res => {
        console.log(res.data)
        categoryList.value = res.data.map(filter => {
            return ({name: filter.categoryName, value: filter.categoryName})
        })
    })
})

// from 2 different multi select -- example below
// let filterBy = {
//     brandName: ["HP", "Microsoft"],
//     categoryName: ["Laptop", "Tablet"]
// };
const filterObject = ref({})

const handleBrandFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter => filter.name)
}

const handleCategoryFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter => filter.name)
}
</script>

<template>
    <BaseSearch
        search-type="hardware"
        :resource-list="hardwareList"
        :live-filter-object="filterObject"
        :hero-title="LandingHeroText['equipment']['title']"
        :hero-subtitle="LandingHeroText['equipment']['subtitle']"
        hero-background-color="purple"
    >
        <template #filterBar>
            <GenericMultiSelectFilter
                id="brandName"
                placeholder="Filter by brand"
                :filter-list="brandList"
                data-path="brandName"
                @transmit-selected-filters="handleBrandFilter"
            />
            <GenericMultiSelectFilter
                id="categoryName"
                placeholder="Filter by category"
                :filter-list="categoryList"
                data-path="categoryName"
                @transmit-selected-filters="handleCategoryFilter"
            />
        </template>
    </BaseSearch>
</template>
