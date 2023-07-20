<script setup>
import {ref} from "vue";

import {serverURL} from "@/js/constants/serverUrl";
import BaseSearch from "@/js/components/search/BaseSearch.vue";
import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";
import useSWRV from "swrv";
import {axiosFetcher} from "@/js/helpers/fetcher";

const swrvOptions = {
    revalidateOnFocus: false, // disable refresh on every focus, suspect its too often
    refreshInterval: 30000 // refresh or revalidate data every 30 secs
}

const {data: adviceList, error: adviceError} = useSWRV(`${serverURL}/fetchAdvicePosts`, axiosFetcher, swrvOptions)

let adviceFilterList = [
    {name: "Digital Adoption Group", value:"D.A.G advice"},
    {name: "Educators", value:["Your Classroom", "Your Work", "Your Learning"]},
    {name: "Partner", value:"Partner"},
]

const filterObject = ref({})

const handleFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter =>filter.value).flat(1)
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
                placeholder="Filter by advice type"
                :filter-list="adviceFilterList"
                data-path="advice_type"
                @transmit-selected-filters="handleFilter"
            />
        </template>
    </BaseSearch>
</template>
