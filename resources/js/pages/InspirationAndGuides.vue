<script setup>
import {ref} from "vue";
import {useRouter} from "vue-router";

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import BaseLandingSection from "@/js/components/bases/BaseLandingSection.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import CaseStudyGuides from "@/js/components/inspirationandguides/CaseStudyGuides.vue";
import CaseStudyQuickSearchGuides from "@/js/components/inspirationandguides/CaseStudyQuickSearchGuides.vue";
import InspirationAndGuidesRobot from "@/js/components/inspirationandguides/InspirationAndGuidesRobot.vue";
import PopularGuideShortcuts from "@/js/components/inspirationandguides/PopularGuideShortcuts.vue";
import SchoolProfileGuidesQuickFilters from "@/js/components/inspirationandguides/SchoolProfileGuidesQuickFilters.vue";
import SchoolProfilesGuides from "@/js/components/inspirationandguides/SchoolProfilesGuides.vue";
import BaseSearch from "@/js/components/search/BaseSearch.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import {adviceService} from "@/js/service/adviceService";
import {schoolService} from "@/js/service/schoolService";

const heroTitle = "Inspiration and guides"
const heroParagraph = "Discover software with the Department for Education tick of approval, and find out what is available to you to use as an employee and how to access it. Explore technical specifications, use cases, steps to purchase, and possible costs."
const router = useRouter()
const featuredSites = ref([])
const adviceList = ref([])

schoolService.fetchFeaturedSchool().then(res =>
    featuredSites.value = res
)
adviceService.fetchAllAdvice().then(res =>
    adviceList.value = res
)

const handleClickViewAllGuides = () => {
    return router.push('browse/guide')
}

const handleClickViewAllSchools = () => {
    return router.push('browse/school')
}

</script>

<template>
    <BaseLandingHero
        :title="heroTitle"
        :title-paragraph="heroParagraph"
    >
        <template #robotIllustration>
            <InspirationAndGuidesRobot class="absolute top-10 left-36" />
        </template>
    </BaseLandingHero>
    <BaseLandingSection>
        <template #title>
            <div class="flex justify-between items-center flex-row w-full">
                <div class="font-semibold text-4xl">
                    Unsure how you schools stacks up? <br>
                    Assess your digital maturity.
                </div>
            </div>
        </template>
        <template #button>
            <GenericButton
                :callback="() => {}"
                :type="'teal'"
            >
                View Assessment
            </GenericButton>
        </template>
    </BaseLandingSection>
    <BaseLandingSection background-color="white">
        <template #title>
            Popular guides
        </template>
        <template #button>
            <GenericButton
                :callback="handleClickViewAllGuides"
                :type="'teal'"
            >
                View all guides
            </GenericButton>
        </template>
        <template #content>
            <PopularGuideShortcuts
                v-if="adviceList && adviceList.length"
                :advice-list="adviceList"
            />
            <Loader
                v-else
                loader-message="loading popular guides"
                loader-type="small"
            />
        </template>
    </BaseLandingSection>
    <BaseLandingSection background-color="teal">
        <template #title>
            School profile
        </template>
        <template #subtitle>
            See what technology other schools are using in their classrooms to help their students thrive.
        </template>
        <template #button>
            <GenericButton
                :callback="handleClickViewAllSchools"
                :type="'teal'"
            >
                View all school
            </GenericButton>
        </template>
        <template #sectionAction>
            <SchoolProfileGuidesQuickFilters />
        </template>
        <template #content>
            <SchoolProfilesGuides :school-list="featuredSites" />
        </template>
    </BaseLandingSection>
    <BaseLandingSection background-color="white">
        <template #title>
            Featured case studies
        </template>
        <template #subtitle>
            See how technology is being used within schools, including the impact, learnings and outcomes.
        </template>
        <template #sectionAction>
            <CaseStudyQuickSearchGuides />
        </template>
        <template #content>
            <CaseStudyGuides :advice-list="adviceList" />
        </template>
    </BaseLandingSection>
</template>
