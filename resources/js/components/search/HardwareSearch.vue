<script setup>

import useSWRV from "swrv";
import {onMounted} from "vue";
import axios from 'axios'

import {serverURL} from "@/js/constants/serverUrl";
import {axiosFetcher} from "@/js/helpers/fetcher";
import BaseSearch from "@/js/components/search/BaseSearch.vue";

console.log('inside hardware search')

const resourceUrl = `${serverURL}/fetchAllProducts`
const {data: hardwareList, error } = useSWRV(resourceUrl, axiosFetcher)

onMounted(()=>{
    axios({
        method: "GET",
        url: `${serverURL}/fetchAllBrands`
    }).then(res => {
        console.log(res.data)
    })

    axios({
        method: "GET",
        url: `${serverURL}/fetchAllCategories`
    }).then(res => {
        console.log(res.data)
    })
} )

</script>

<template>
    <BaseSearch
        search-type="hardware"
        :resource-list="hardwareList"
    >
        <template #filterBar>
            <div>some filtering stuff here</div>
        </template>
    </BaseSearch>
    <pre> {{ hardwareList }}</pre>
</template>
