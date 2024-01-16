<script setup>
import {storeToRefs} from "pinia";
import {onMounted, ref} from "vue";
import {useRouter} from "vue-router";

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import BaseLandingSection from "@/js/components/bases/BaseLandingSection.vue";
import PopularResourceShortcuts from "@/js/components/bases/PopularResourceShortcuts.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import CaseStudyGuides from "@/js/components/inspirationandguides/CaseStudyGuides.vue";
import CaseStudyQuickSearchGuides from "@/js/components/inspirationandguides/CaseStudyQuickSearchGuides.vue";
import InspirationAndGuidesRobot from "@/js/components/inspirationandguides/InspirationAndGuidesRobot.vue";
import SchoolProfileGuidesQuickFilters from "@/js/components/inspirationandguides/SchoolProfileGuidesQuickFilters.vue";
import SchoolProfilesGuides from "@/js/components/inspirationandguides/SchoolProfilesGuides.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {lowerSlugify} from "@/js/helpers/stringHelpers";
import {adviceService} from "@/js/service/adviceService";
import {schoolService} from "@/js/service/schoolService";
import {useAdviceStore} from "@/js/stores/useAdviceStore";


const router = useRouter()
const featuredSites = ref([])
const {allAdvice} = storeToRefs(useAdviceStore())

onMounted(() => {
    schoolService.fetchFeaturedSchool().then(res =>
        featuredSites.value = res
    )
    adviceService.fetchAllAdvice().then(res => {
        console.log(res)
        allAdvice.value = res
        console.log(allAdvice.value)

    })

})

const handleClickViewAllGuides = () => {
    return router.push('/browse/guide')
}

const handleClickViewAllSchools = () => {
    return router.push('/browse/school')
}

const handleClickPopularGuides = (guideId,title) =>{
    return router.push({
        name: "guide-single",
        params: {id: guideId, slug: lowerSlugify(title)},
    })
}
</script>

<template>
    <BaseLandingHero
        :title="LandingHeroText['inspiration']['title']"
        :title-paragraph="LandingHeroText['inspiration']['subtitle']"
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
                (Coming soon)
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
            <PopularResourceShortcuts
                v-if="allAdvice && allAdvice.length"
                :resource-list="allAdvice"
                :resource-click-callback="handleClickPopularGuides"
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
        <template #sectionAction />
        <template #content>
            <CaseStudyGuides :advice-list="allAdvice" />
        </template>
    </BaseLandingSection>
</template>
