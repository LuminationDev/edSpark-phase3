<script setup>
import {computed, ref} from 'vue'
import {useRouter} from "vue-router";

import {lowerSlugify} from "@/js/helpers/stringHelpers";
import {schoolService} from "@/js/service/schoolService";

const router = useRouter()

const schoolFilterList = ref(schoolService.getSchoolFilterList())

const handleClickSchoolQuickFilter = (filterName) => {
    const formattedName = lowerSlugify(filterName)
    console.log('filter clicked ' + formattedName)
    router.push('browse/school/' + formattedName)
}


</script>

<template>
    <div class="flex flex-row flex-wrap gap-4 my-8 w-full">
        <button
            v-for="(filterItem,index) in schoolFilterList"
            :key="index"
            class="bg-white hover:bg-main-darkTeal border-2 border-main-darkTeal cursor-pointer px-4 py-2 rounded text-main-darkTeal hover:text-white"
            @click="() => handleClickSchoolQuickFilter(filterItem.name)"
        >
            {{ filterItem.name }}
        </button>
    </div>
</template>
