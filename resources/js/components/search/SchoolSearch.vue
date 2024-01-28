<script setup lang="ts">
import {storeToRefs} from "pinia";
import {computed, onMounted, ref} from "vue";
import {useRoute, useRouter} from "vue-router";

import SchoolsSearchableMap from "@/js/components/schools/schoolMap/SchoolsSearchableMap.vue";
import BaseSearch from "@/js/components/search/BaseSearch.vue";
import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";
import SchoolSearchListViewIcon from "@/js/components/svg/schoolMapView/SchoolSearchListViewIcon.vue";
import SchoolSearchMapViewIcon from "@/js/components/svg/schoolMapView/SchoolSearchMapViewIcon.vue";
import SchoolsRobot from "@/js/components/svg/schoolsRobot/schoolsRobot.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {schoolPartnerTech,schoolTech} from "@/js/constants/schoolTech";
import {lowerSlugify} from "@/js/helpers/stringHelpers";
import {schoolService} from "@/js/service/schoolService";
import {useSchoolsStore} from "@/js/stores/useSchoolsStore";

const route = useRoute()
const router = useRouter()

const combinedSchoolTech = [...schoolTech,...schoolPartnerTech];
const {allSchools} = storeToRefs(useSchoolsStore())

onMounted(() =>{
    schoolService.fetchAllSchools().then(res =>{
        console.log(res)
        allSchools.value = res
    })

})

const currentSearchResultView = ref('list')
const filterObject = ref({})
const preselectedFilterObject = ref(null as {name: string, value: string | string[]} | null);

const schoolFilterList = schoolService.getSchoolFilterList()

const isCurrentViewCustomView = computed(() =>{
    return currentSearchResultView.value !== 'list';
})

const schoolTechFilterList = computed(() =>{
    return combinedSchoolTech.map(item =>{
        return {name: item.name, value: item.name}
    })
})

const handleFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter => filter.value)
}


if (route.params && route.params.filter) {
    const filterNameSlug = lowerSlugify(route.params.filter);

    const matchingFilter = schoolFilterList.find(filter => filterNameSlug === lowerSlugify(filter.name));

    if (matchingFilter) {
        preselectedFilterObject.value = matchingFilter;
    } else {
        router.push('/browse/school');
    }
}

const handleClickSearchResultView = (viewType) =>{
    currentSearchResultView.value = viewType
}

</script>

<template>
    <BaseSearch
        search-type="school"
        :resource-list="allSchools"
        :live-filter-object="filterObject"
        :hero-title="LandingHeroText['school']['title']"
        :hero-subtitle="LandingHeroText['school']['subtitle']"
        :custom-view="isCurrentViewCustomView"
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
            <div class="border-[1px] border-slate-300 grid grid-cols-2 h-12 rounded text-slate-600 viewSelectorContainer w-56">
                <div
                    class="border-r-[1px] border-slate-300 cursor-pointer flex justify-center items-center flex-row gap-2 h-full"
                    :class="{'bg-slate-200' : currentSearchResultView === 'list'}"
                    @click="() =>handleClickSearchResultView('list')"
                >
                    <SchoolSearchListViewIcon />
                    List view
                </div>
                <div
                    class="border-slate-300 cursor-pointer flex justify-center items-center gap-2"
                    :class="{'bg-slate-200' : currentSearchResultView === 'map'}"
                    @click="() => handleClickSearchResultView('map')"
                >
                    <SchoolSearchMapViewIcon />
                    Map view
                </div>
            </div>
        </template>
        <template #robot>
            <SchoolsRobot
                class="absolute top-10 left-36"
            />
        </template>
        <template #customViewSlot="{filteredData}">
            <SchoolsSearchableMap
                :schools-available="true"
                :schools="filteredData"
            />
        </template>
    </BaseSearch>
</template>
