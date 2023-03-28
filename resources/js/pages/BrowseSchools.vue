<script setup>
import SchoolCard from "@/js/components/schools/SchoolCard.vue";
import {computed, onBeforeMount,  ref} from "vue";
import axios from "axios";
import SearchBar from "@/js/components/browseschools/SearchBar.vue";
const serverURL = import.meta.env.VITE_SERVER_URL_API


const allSchoolsArray = ref([])
onBeforeMount(() =>{
    axios.get(`${serverURL}/fetchAllSchools`).then(res => {
        allSchoolsArray.value = res.data
    })
})
const filterTerm = ref('')
const handleSearchTerm = (term) => {
    filterTerm.value = term.toLowerCase()
}

const filteredSchool = computed(() =>{
    return allSchoolsArray.value.filter(sch => {
        if(filterTerm.value.length < 1) return true
        if(sch.name.toLowerCase().includes(filterTerm.value)) return true
    })
})


</script>
<template>
    <div class="browse-schools-container mt-16 flex flex-col justify-center items-center">
        <h3 class="font-semibold text-2xl">
            Browse all schools
        </h3>
        <SearchBar
            @emit-search-term="handleSearchTerm"
        />
        <div
            v-if="allSchoolsArray"
            class="card-iterator-container w-full flex flex-row flex-wrap my-4 items-center justify-evenly"
        >
            <div
                v-for="(school, index) in filteredSchool"
                :key="index"
                class="border-2 mx-4 my-4 basis-1/4  h-[470px] transition-all group hover:shadow-2xl rounded-xl"
            >
                <SchoolCard
                    :school-data="school"
                    class="w-full"
                />
            </div>
            <div
                v-if="filteredSchool.length <= 0"
                class="text-xl font-semibold"
            >
                No search result
            </div>
        </div>
    </div>
</template>