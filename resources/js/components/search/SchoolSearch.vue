<script setup lang="ts">
import useSWRV from "swrv";
import {computed, ref} from "vue";
import {useRoute, useRouter} from "vue-router";

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {schoolPartnerTech,schoolTech} from "@/js/constants/schoolTech";
import { axiosSchoolFetcherParams} from "@/js/helpers/fetcher";
import {lowerSlugify} from "@/js/helpers/stringHelpers";
import {useUserStore} from "@/js/stores/useUserStore";

const swrvOptions = {
    revalidateOnFocus: false, // disable refresh on every focus, suspect its too often
    refreshInterval: 30000 // refresh or revalidate data every 30 secs
}
const route = useRoute()
const router = useRouter()

const combinedSchoolTech = [...schoolTech,...schoolPartnerTech];
console.log(combinedSchoolTech)

const {data: schoolList, error: schoolError} = useSWRV(API_ENDPOINTS.SCHOOL.FETCH_ALL_SCHOOLS, axiosSchoolFetcherParams(useUserStore().getUserRequestParam), swrvOptions)
const schoolFilterList = [
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

const preselectedFilterObject = ref(null as {name: string, value: string | string[]} | null);


if (route.params && route.params.filter) {
    const filterNameSlug = lowerSlugify(route.params.filter);

    const matchingFilter = schoolFilterList.find(filter => filterNameSlug === lowerSlugify(filter.name));

    if (matchingFilter) {
        preselectedFilterObject.value = matchingFilter;
    } else {
        router.push('/browse/school');
    }
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
                id="schoolType"
                placeholder="Filter by type"
                :filter-list="schoolFilterList"
                data-path="site_type_code"
                :preselected="preselectedFilterObject"

                @transmit-selected-filters="handleFilter"
            />
            <GenericMultiSelectFilter
                id="techType"
                placeholder="Filter by tech"
                :filter-list="schoolTechFilterList"
                data-path="tech_used"
                @transmit-selected-filters="handleFilter"
            />
        </template>
    </BaseSearch>
</template>
