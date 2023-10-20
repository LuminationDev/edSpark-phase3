<script setup>
import useSWRV from "swrv";
import { ref} from "vue";
import {useRoute} from "vue-router";

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {swrvOptions} from "@/js/constants/swrvConstants";
import { axiosFetcher} from "@/js/helpers/fetcher";
import router from "@/js/router";
import {useUserStore} from "@/js/stores/useUserStore";
const route = useRoute()


const {data: softwareList, error: softwareError} = useSWRV(API_ENDPOINTS.SOFTWARE.FETCH_SOFTWARE_POSTS, axiosFetcher(useUserStore().getUserRequestParam), swrvOptions)

const softwareFilterList = [
    {name: "Department Provided", value:"Department Provided"},
    {name: "Department Approved", value:"Department Approved"},
    {name: "Department Approved and Negotiated", value:"Approved and Negotiated"},
]

const filterObject = ref({})


// Handle Emit that is emitted by GenericMultiSelectFilter
const handleFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter =>filter.value).flat(1)
}

const preselectedFilterObject = ref('');

if(route.params || route.params.filter){
    switch(route.params.filter){
    case "provided":
        preselectedFilterObject.value = {name: "Department Provided", value:"Department Provided"}
        break;
    case "approved":
        preselectedFilterObject.value = {name: "Department Approved", value:"Department Approved"}
        break;
    case "negotiated":
        preselectedFilterObject.value = {name: "Department Approved and Negotiated", value:"Approved and Negotiated"}
        break;
    default:
        router.push('/browse/software')
    }
}

</script>

<template>
    <BaseSearch
        search-type="software"
        :resource-list="softwareList"
        :live-filter-object="filterObject"
    >
        <template #filterBar>
            <GenericMultiSelectFilter
                id="softwareType"
                placeholder="Filter by software type"
                :filter-list="softwareFilterList"
                data-path="software_type"
                :preselected="preselectedFilterObject"
                @transmit-selected-filters="handleFilter"
            />
        </template>
    </BaseSearch>
</template>
