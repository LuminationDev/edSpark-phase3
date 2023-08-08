<script setup>
import SchoolCard from "@/js/components/schools/SchoolCard.vue";
import {computed, onBeforeMount,  ref} from "vue";
import axios from "axios";
import SearchBar from "@/js/components/browseschools/SearchBar.vue";
import {parseToJsonIfString, schoolContentArrParser} from "@/js/helpers/jsonHelpers";
import useSWRV from "swrv";
const serverURL = import.meta.env.VITE_SERVER_URL_API
const axiosFetcher = (url) => {
    return axios.get(url).then(res => res.data)
}
const {data: allSchoolsArray, error: schoolsError} = useSWRV(API_ENDPOINTS.SCHOOL.FETCH_ALL_SCHOOLS, axiosFetcher)

const allSchoolsData = computed(() => {
    return schoolContentArrParser(allSchoolsArray.value)
})

const filterTerm = ref('')
const handleSearchTerm = (term) => {
    filterTerm.value = term.toLowerCase()
}

const filteredSchool = computed(() =>{
    return allSchoolsData.value.filter(sch => {
        if(filterTerm.value.length < 1) return true
        if(sch.name.toLowerCase().includes(filterTerm.value)) return true
    })
})


</script>
<template>
    <div class="browse-schools-container flex justify-center items-center flex-col mt-16">
        <h3 class="font-semibold text-2xl">
            Browse all schools
        </h3>
        <SearchBar
            @emit-search-term="handleSearchTerm"
        />
        <div
            v-if="allSchoolsArray"
            class="card-iterator-container flex justify-evenly items-center flex-row flex-wrap my-4 w-full"
        >
            <div
                v-for="(school, index) in filteredSchool"
                :key="index"
                class="
                    basis-1/4
                    border-2
                    border-[0.5px]
                    border-black
                    group
                    h-[470px]
                    max-w-[320px]
                    mx-4
                    my-4
                    transition-all
                    hover:shadow-2xl
                    "
            >
                <SchoolCard
                    :school-data="school"
                    class="w-full"
                />
            </div>
            <div
                v-if="filteredSchool.length <= 0"
                class="font-semibold text-xl"
            >
                No search result
            </div>
        </div>
    </div>
</template>

