<script setup>
import {computed, ref} from "vue";
import axios from 'axios'

import {serverURL} from "@/js/constants/serverUrl";
import BaseSearch from "@/js/components/search/BaseSearch.vue";
import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";
import useSWRV from "swrv";
import {axiosSchoolFetcher} from "@/js/helpers/fetcher";

import {schoolTech, schoolPartnerTech} from "@/js/constants/schoolTech";

const swrvOptions = {
    revalidateOnFocus: false, // disable refresh on every focus, suspect its too often
    refreshInterval: 30000 // refresh or revalidate data every 30 secs
}
const combinedSchoolTech = [...schoolTech,...schoolPartnerTech];
console.log(combinedSchoolTech)

const {data: schoolList, error: schoolError} = useSWRV(`${serverURL}/fetchAllSchools`, axiosSchoolFetcher, swrvOptions)

let schoolFilterList = [
    {name: "Preschool", value:"PRE"},
    {name: "Primary Education", value:"PRIM"},
    {name: "Primary/Secondary", value:"PRSEC"},
    {name: "Secondary Education", value:"SEC"},
    {name: "Special Education", value:"SPEC"},
    {name: "Specialist Facilities", value:"SPFAC"},
    {name: "Aboriginal/Anangu Schools", value:"ABAN"},
]

const schoolTechFilterList = computed(() =>{
    return combinedSchoolTech.map(item =>{
        return {name: item.name, value: item.name}
    })
})

const filterObject = ref({})

const handleFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter => filter.value)
}

</script>

<template>
    <BaseSearch
        search-type="school"
        :resource-list="schoolList"
        :live-filter-object="filterObject"
    >
        <template #filterBar>
            <GenericMultiSelectFilter
                placeholder="Filter by school type"
                :filter-list="schoolFilterList"
                data-path="site_type"
                @transmit-selected-filters="handleFilter"
            />
            <GenericMultiSelectFilter
                placeholder="Filter by tech"
                :filter-list="schoolTechFilterList"
                data-path="tech_used"
                @transmit-selected-filters="handleFilter"
            />
        </template>
    </BaseSearch>
</template>
