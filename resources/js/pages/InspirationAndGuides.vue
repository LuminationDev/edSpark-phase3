<script setup>
import {storeToRefs} from "pinia";
import {onMounted, ref} from "vue";
import {useRouter} from "vue-router";

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import BaseLandingSection from "@/js/components/bases/BaseLandingSection.vue";
import PopularResourceShortcuts from "@/js/components/bases/PopularResourceShortcuts.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import CaseStudyGuides from "@/js/components/inspirationandguides/CaseStudyGuides.vue";
import DAGCardsRowGuides from "@/js/components/inspirationandguides/DAGCardsRowGuides.vue";
import RobotSearch from "@/js/components/svg/RobotSearch.vue";
import SoftwareRobot from "@/js/components/svg/software/SoftwareRobot.vue";
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

const handleClickPopularGuides = (guideId, title) => {
    return router.push({
        name: "guide-single",
        params: {id: guideId, slug: lowerSlugify(title)},
    })
}

const handleClickGoToDMA = () =>{
    return router.push('/inspire/dma')
}
</script>

<template>
    <BaseLandingHero
        :title="LandingHeroText['inspiration']['title']"
        :title-paragraph="LandingHeroText['inspiration']['subtitle']"
        swoosh-color="teal"
    >
    <template #robotIllustration>
        <RobotSearch class="absolute top-16 left-36" />
    </template>
    </BaseLandingHero>
    <BaseLandingSection>
        <template #title>
            <div class="flex justify-between items-center flex-row w-full align-center">
                <div class="font-semibold                 
                        text-3xl    sm:text-3xl         md:text-3xl     lg:text-4xl
                        max-w-full  sm:max-w-[500px]    md:max-w-[600px] lg:max-w-[700px]">

                    Unsure how your school stacks up? 
                    Assess your digital maturity.
                </div>
            </div>
        </template>
        <template #button>
            <GenericButton
                :callback="handleClickGoToDMA"
                :type="'teal'"
            >
                Go to DMA tool
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
                loader-message="Loading popular guides"
                loader-type="small"
            />
        </template>
    </BaseLandingSection>
    <BaseLandingSection background-color="teal">
        <template #title>
            The Digital Adoption Group (DAG)
        </template>
        <template #subtitle>
            The Digital Adoption Group (DAG) offers comprehensive guidance on digital technologies, providing practical,
            system-wide advice for purchasing and adopting high-impact technologies that enhance teaching and learning.
        </template>
        <template #button>
            <GenericButton
                :callback="() =>router.push('/browse/guide/dag')"
                :type="'teal'"
            >
                View all DAG guides
            </GenericButton>
        </template>
        <template #content>
            <DAGCardsRowGuides :advice-list="allAdvice" />
        </template>
    </BaseLandingSection>
    <BaseLandingSection background-color="white">
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
                View all schools
            </GenericButton>
        </template>
        <template #sectionAction>
            <SchoolProfileGuidesQuickFilters />
        </template>
        <template #content>
            <SchoolProfilesGuides :school-list="featuredSites" />
        </template>
    </BaseLandingSection>
    <BaseLandingSection background-color="teal">
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
