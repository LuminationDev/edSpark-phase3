<script setup>
import useSWRV from "swrv";
import { ref } from "vue";

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {axiosFetcher} from "@/js/helpers/fetcher";
import {useUserStore} from "@/js/stores/useUserStore";

const { data: adviceList, error: adviceError } = useSWRV(API_ENDPOINTS.ADVICE.FETCH_ADVICE_POSTS, axiosFetcher(useUserStore().getUserRequestParam), swrvOptions)

const adviceFilterList = [
    { name: "Digital Adoption Group", value: "DAG advice" },
    { name: "Educators", value: ["Your Classroom", "Your Work", "Your Learning"] },
    { name: "Partner", value: "Partner" },
]

const filterObject = ref({})

const handleFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter => filter.value).flat(1)
}

</script>

<template>
    <BaseSearch
        search-type="advice"
        :resource-list="adviceList"
        :live-filter-object="filterObject"
    >
        <template #filterBar>
            <GenericMultiSelectFilter
                id="adviceFilter"
                placeholder="Filter by type"
                :filter-list="adviceFilterList"
                data-path="advice_type"
                @transmit-selected-filters="handleFilter"
            />
           
        </template>
    </BaseSearch>
</template>
