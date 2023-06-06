<script setup>

import useSWRV from "swrv";
import {onMounted,ref} from "vue";
import axios from 'axios'

import {serverURL} from "@/js/constants/serverUrl";
import {axiosFetcher} from "@/js/helpers/fetcher";
import BaseSearch from "@/js/components/search/BaseSearch.vue";
import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";

console.log('inside hardware search')

const resourceUrl = `${serverURL}/fetchAllProducts`
const {data: hardwareList, error } = useSWRV(resourceUrl, axiosFetcher)

const brandList = ref([])
const categoryList = ref([])

onMounted(()=>{
    axios({
        method: "GET",
        url: `${serverURL}/fetchAllBrands`
    }).then(res => {
        console.log(res.data)
        brandList.value =  res.data.map(filter => {
            return ({name: filter.brandName, value: filter.brandName})
        })
    })

    axios({
        method: "GET",
        url: `${serverURL}/fetchAllCategories`
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
                placeholder="Filter by Brand"
                :filter-list="brandList"
                data-path="brandName"
                @transmit-selected-filters="handleBrandFilter"
            />
            <GenericMultiSelectFilter
                placeholder="Filter by Category"
                :filter-list="categoryList"
                data-path="categoryName"
                @transmit-selected-filters="handleCategoryFilter"
            />
        </template>
    </BaseSearch>
</template>
