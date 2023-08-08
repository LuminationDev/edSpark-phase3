<script setup>

import SearchBar from "@/js/components/browseschools/SearchBar.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useRoute} from "vue-router";
import {serverURL} from "@/js/constants/serverUrl";
import {computed, onBeforeMount, ref} from "vue";
import {axiosFetcher} from "@/js/helpers/fetcher";

import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import useSWRV from "swrv";

let resourceUrl = ''
const filterTerm = ref('')
const route = useRoute()
const searchType = route.params.type

switch (searchType) {
case 'advice':
    console.log('advice')
    resourceUrl = API_ENDPOINTS.ADVICE.FETCH_ADVICE_POSTS
    break;
case 'software':
    console.log('software')
    resourceUrl = API_ENDPOINTS.ADVICE.FETCH_SOFTWARE_POSTS
    break;
default:
    console.log('hmm')
}


const {data: resourceList, error: resourceError} = useSWRV(resourceUrl, axiosFetcher)

const filteredData = computed(() => {
    return resourceList.value.filter(data => {
        if (filterTerm.value.length < 1) return true
        console.log(data)
        if (data.post_title.toLowerCase().includes(filterTerm.value)) return true
    })
})

const handleSearchTerm = (term) => {
    filterTerm.value = term.toLowerCase()
}


</script>
<template>
    <div
        v-if="!resourceError"
        class="browse-schools-container flex justify-center items-center flex-col mt-16"
    >
        <h3 class="font-semibold text-2xl">
            Browse all {{ searchType }}
        </h3>
        <SearchBar
            :placeholder="`Type in ${searchType} name`"
            @emit-search-term="handleSearchTerm"
        />
        <div
            v-if="resourceList"
            class="flex justify-around flex-1 flex-row flex-wrap gap-2 pt-10 px-20 resourceResult w-full"
        >
            <template
                v-for="(data, index) in filteredData"
                :key="index"
            >
                <template
                    v-if="searchType == 'advice'"
                >
                    <AdviceCard
                        :advice-content="data"
                        :number-per-row="1"
                        :show-icon="true"
                    />
                </template>
                <template v-else-if="searchType == 'software'">
                    <SoftwareCard
                        :software="data"
                        :number-per-row="1"
                    />
                </template>
            </template>
            <div
                v-if="filteredData.length <= 0"
                class="font-semibold text-xl"
            >
                No search result
            </div>
        </div>
    </div>
    <div v-else>
        Something has gone wrong. Please try again later
    </div>
</template>