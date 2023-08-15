<script setup>

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {useUserStore} from "@/js/stores/useUserStore";
import useSWRV from "swrv";
import {onMounted,ref} from "vue";
import axios from 'axios'
import { axiosFetcherParams} from "@/js/helpers/fetcher";
import BaseSearch from "@/js/components/search/BaseSearch.vue";
import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";

console.log('inside hardware search')

const resourceUrl = API_ENDPOINTS.HARDWARE.FETCH_HARDWARE_POSTS
const {data: hardwareList, error } = useSWRV(resourceUrl, axiosFetcherParams(useUserStore().getUserRequestParam), swrvOptions)

const brandList = ref([])
const categoryList = ref([])

onMounted(()=>{
    axios({
        method: "GET",
        url: API_ENDPOINTS.HARDWARE.FETCH_ALL_BRANDS
    }).then(res => {
        console.log(res.data)
        brandList.value =  res.data.map(filter => {
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
    >
        <template #filterBar>
            <GenericMultiSelectFilter
                placeholder="Filter by brand"
                :filter-list="brandList"
                data-path="brandName"
                @transmit-selected-filters="handleBrandFilter"
            />
            <GenericMultiSelectFilter
                placeholder="Filter by category"
                :filter-list="categoryList"
                data-path="categoryName"
                @transmit-selected-filters="handleCategoryFilter"
            />
        </template>
    </BaseSearch>
</template>
