<script setup>
import {debounce} from "lodash";
import {storeToRefs} from "pinia";
import {nextTick, onMounted, ref, watch} from 'vue'

import SearchBar from "@/js/components/browseschools/SearchBar.vue";
import SearchResultCard from "@/js/components/search/searchResult/SearchResultCard.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import Close from "@/js/components/svg/Close.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useWindowStore} from "@/js/stores/useWindowStore";

const props = defineProps({})

const {showGlobalSearch} = storeToRefs(useWindowStore())
const searchTerms = ref("")
const searchResults = ref([])
const searchLoading = ref(false)
const globalSearchInputBox = ref(null)


const fetchSearchResults = () => {
    if(searchTerms.value){
        searchLoading.value = true
        axios.get(API_ENDPOINTS.SEARCH.SEARCH_ALL, {params: {search: searchTerms.value}}).then(res => {
            searchResults.value = res.data
        }).catch(e =>{
        }).finally(() =>{
            searchLoading.value = false
        })

    }else{
        searchResults.value =[]
    }
}

const debouncedfetchSearchResults = debounce(() => {
    fetchSearchResults()
}, 400)

watch(searchTerms, debouncedfetchSearchResults);

watch(showGlobalSearch, () =>{
    if(showGlobalSearch.value)document.body.style.overflow = 'hidden'
    else document.body.style.overflow = 'auto'
})
onMounted(async () =>{
    await nextTick()
    globalSearchInputBox.value.focus();

    
})
const handleClickOverlay = () => {
    showGlobalSearch.value = false
}

const handleClearSearchbar = () => {
    if(!searchTerms.value){
        showGlobalSearch.value = false
    }
    searchTerms.value = ''
}

</script>

<template>
    <div class="absolute top-0 right-0 bottom-0 left-0 globalSearchScreenContainer grid place-items-center h-screen w-screen z-50">
        <div
            class="bg-main-navy/80 fixed top-0 left-0 grayoverlay h-full w-full z-40"
            @click="handleClickOverlay"
        />
        <div class="bg-white drop-shadow-xl flex flex-col h-1/2 overflow-y-auto relative rounded-xl searchBox z-50 w-10/12 lg:w-1/2">
            <input
                ref="globalSearchInputBox"
                v-model="searchTerms"
                type="text"
                class="
                    !border-b-[1px]
                    !rounded-t-xl
                    border-0
                    border-gray-300
                    globalSearchInputBox
                    pt-4
                    px-8
                    sticky
                    top-0
                    text-gray-600
                    text-lg
                    focus:outline-none
                    focus:ring-gray-300
                    "
                placeholder="Type in search terms"
            >
            <div
                class="absolute top-4 right-4 cursor-pointer emptySearchField"
                @click="handleClearSearchbar"
            >
                <Close
                    class="cursor-pointer fill-gray-300 h-4 w-4"
                />
            </div>
            <div>
                <template v-if="searchResults && searchResults.length && !searchLoading">
                    <SearchResultCard
                        v-for="(res, index) in searchResults"
                        :key="index"
                        :data="res"
                    />
                </template>
                <template v-else-if="searchLoading">
                    <Loader
                        :loader-color="'#0072DA'"
                        :loader-message="'Results loading'"
                        loader-message-class="text-base font-semibold"
                        class="!text-sm mt-10"
                    />
                </template>
                <template v-else>
                    <div class="mt-10 text-center text-gray-600">
                        Results will appear here
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
<style scoped>
</style>