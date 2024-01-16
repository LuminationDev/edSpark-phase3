<script setup>
import {storeToRefs} from "pinia";
import {computed, onMounted, ref} from 'vue';
import {useRouter} from "vue-router";

import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import BaseLandingCardRow from "@/js/components/bases/BaseLandingCardRow.vue";
import BaseLandingSection from "@/js/components/bases/BaseLandingSection.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import CardLoading from '@/js/components/card/CardLoading.vue';
import DashboardHero from '@/js/components/dashboard/DashboardHero.vue';
import SoftwareIllustration from "@/js/components/dashboard/SoftwareIllustration.vue";
import SchoolProfileGuidesQuickFilters from "@/js/components/inspirationandguides/SchoolProfileGuidesQuickFilters.vue";
import SchoolCard from "@/js/components/schools/SchoolCard.vue";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import {getNRandomElementsFromArray} from "@/js/helpers/cardDataHelper";
import {adviceService} from "@/js/service/adviceService";
import {eventService} from "@/js/service/eventService";
import {schoolService} from "@/js/service/schoolService";
import {softwareService} from "@/js/service/softwareService";
import {useSchoolsStore} from "@/js/stores/useSchoolsStore";
import {useSoftwareStore} from "@/js/stores/useSoftwareStore";
import {useUserStore} from '@/js/stores/useUserStore';
import {useWindowStore} from "@/js/stores/useWindowStore";

const router = useRouter()
const userStore = useUserStore();
const windowStore = useWindowStore()

const {featuredSchools} = storeToRefs(useSchoolsStore())
const guideList = ref([])
const {allSoftware} = storeToRefs(useSoftwareStore())

const eventList = ref([])

onMounted(async () => {
    try {
        const [fetchedFeaturedSchool, fetchedAdvice, fetchedSoftware, fetchedEvent] = await Promise.all([
            schoolService.fetchFeaturedSchool(),
            adviceService.fetchAllAdvice(),
            softwareService.fetchAllSoftware(),
            eventService.fetchAllEvent()
        ]);

        featuredSchools.value = fetchedFeaturedSchool;
        guideList.value = fetchedAdvice;
        allSoftware.value = fetchedSoftware;
        eventList.value = fetchedEvent;
    } catch (error) {
        // Handle errors here
        console.error("Error fetching data:", error);
    }
})


</script>

<template>
    <div>
        <DashboardHero />
        <BaseLandingSection>
            <template #title>
                Latest school profiles
            </template>
            <template #button>
                <GenericButton
                    :callback="() => router.push('browse/school')"
                    :type="'teal'"
                >
                    View all school
                </GenericButton>
            </template>
            <template #sectionAction>
                <SchoolProfileGuidesQuickFilters />
            </template>
            <template #content>
                <BaseLandingCardRow :resource-list="featuredSchools">
                    <template #rowContent>
                        <SchoolCard
                            v-for="(school,index) in getNRandomElementsFromArray(featuredSchools,3)"
                            :key="index"
                            :data="school"
                        />
                    </template>
                </BaseLandingCardRow>
            </template>
        </BaseLandingSection>
    </div>

    <BaseLandingSection
        background-color="white"
    >
        <template #title>
            Guides and resources
        </template>
        <template #button>
            <GenericButton
                :callback="() => router.push('browse/guide')"
                :type="'teal'"
            >
                View all guides
            </GenericButton>
        </template>
        <template #content>
            <BaseLandingCardRow :resource-list="guideList">
                <template #rowContent>
                    <AdviceCard
                        v-for="(guide,index) in getNRandomElementsFromArray(guideList,3)"
                        :key="index"
                        :data="guide"
                    />
                </template>
            </BaseLandingCardRow>
        </template>
    </BaseLandingSection>

    <BaseLandingSection
        background-color="teal"
    >
        <template #title>
            Latest technology
        </template>
        <template #button>
            <GenericButton
                :callback="() => router.push('tech/hardware')"
                :type="'teal'"
            >
                View all technology
            </GenericButton>
        </template>
        <template #content>
            <div class="flex flex-col gap-6 group/bg h-full relative lg:!flex-row">
                <div class="flex flex-col max-h-[1000px] pl-8 place-items-center w-full lg:w-[40%]">
                    <SoftwareIllustration />
                </div>
                <template v-if="!allSoftware || !allSoftware.length">
                    <div class="">
                        <CardLoading
                            :number-of-rows="1"
                            :number-per-row="2"
                        />
                    </div>
                </template>
                <template v-else>
                    <div
                        class="
                            grid
                            sm:grid-cols-1
                            md:grid-cols-2
                            lg:grid-cols-1
                            xl:grid-cols-2
                            grid-cols-1
                            gap-10
                            place-items-center
                            h-fit
                            max-h-[1000px]
                            overflow-hidden
                            px-8
                            xl:px-2
                            "
                    >
                        <SoftwareCard
                            v-for="software in getNRandomElementsFromArray(allSoftware,2)"
                            :key="software.guid"
                            :data="software"
                        />
                    </div>
                </template>
            </div>
        </template>
    </BaseLandingSection>
    <BaseLandingSection
        background-color="white"
    >
        <template #title>
            New events
        </template>
        <template #button>
            <GenericButton
                :callback="() => router.push('browse/event')"
                :type="'teal'"
            >
                View all events
            </GenericButton>
        </template>
        <template #content>
            <BaseLandingCardRow :resource-list="eventList">
                <template #rowContent>
                    <AdviceCard
                        v-for="(event,index) in getNRandomElementsFromArray(eventList,3)"
                        :key="index"
                        :data="event"
                    />
                </template>
            </BaseLandingCardRow>
        </template>
    </BaseLandingSection>

    <!--        <SectionHeader-->
    <!--            :classes="'bg-secondary-darkBlue'"-->
    <!--            :section="'software'"-->
    <!--            :title="'Latest Software'"-->
    <!--            :button-text="'View all software'"-->
    <!--            :button-callback="() => router.push('/browse/software')"-->
    <!--        />-->

    <!--        &lt;!&ndash; Software Section Here &ndash;&gt;-->
    <!--        <div class="flex flex-col gap-6 group/bg h-full relative lg:!flex-row lg:!px-huge xl:py-8">-->
    <!--            <div-->
    <!--                class="-->
    <!--                    -translate-y-1/2-->
    <!--                    md:!block-->
    <!--                    -z-10-->
    <!--                    absolute-->
    <!--                    top-1/2-->
    <!--                    left-1/4-->
    <!--                    duration-700-->
    <!--                    group-hover/bg:left-[15%]-->
    <!--                    group-hover/bg:scale-125-->
    <!--                    hidden-->
    <!--                    opacity-10-->
    <!--                    softwareRobot-->
    <!--                    transition-all-->
    <!--                    "-->
    <!--            >-->
    <!--                <SoftwareRobot />-->
    <!--            </div>-->
    <!--            <div class="flex flex-col max-h-[1000px] pl-8 place-items-center w-full lg:w-[40%]">-->
    <!--                <SoftwareIllustration />-->
    <!--            </div>-->
    <!--            <template v-if="softwaresData">-->
    <!--                <div-->
    <!--                    class="-->
    <!--                        grid-->
    <!--                        sm:grid-cols-1-->
    <!--                        md:grid-cols-2-->
    <!--                        lg:grid-cols-1-->
    <!--                        xl:grid-cols-2-->
    <!--                        grid-cols-1-->
    <!--                        gap-10-->
    <!--                        place-items-center-->
    <!--                        h-fit-->
    <!--                        max-h-[1000px]-->
    <!--                        overflow-hidden-->
    <!--                        px-8-->
    <!--                        xl:px-2-->
    <!--                        "-->
    <!--                >-->
    <!--                    <SoftwareCard-->
    <!--                        v-for="software in softwareResponsiveData"-->
    <!--                        :key="software.guid"-->
    <!--                        :data="software"-->
    <!--                    />-->
    <!--                </div>-->
    <!--            </template>-->
    <!--            <template v-else>-->
    <!--                <div class="px-8 w-full lg:!px-20">-->
    <!--                    <CardLoading-->
    <!--                        :number-of-rows="2"-->
    <!--                        :number-per-row="windowStore.getNumberOfCardLoading"-->
    <!--                    />-->
    <!--                </div>-->
    <!--            </template>-->
    <!--        </div>-->

    <!--        <SectionHeader-->
    <!--            :classes="'bg-main-teal'"-->
    <!--            :section="'events'"-->
    <!--            :title="'New Events'"-->
    <!--            :button-text="'View all events'"-->
    <!--            :button-callback="() => router.push('/browse/event')"-->
    <!--        />-->
    <!--        <CarouselGenerator-->
    <!--            data-type="events"-->
    <!--            :data-array="eventsData? eventsData : []"-->
    <!--        />-->


    <!--        <SectionHeader-->
    <!--            :classes="'bg-main-navy'"-->
    <!--            :section="'schools'"-->
    <!--            :title="'Latest School Profiles'"-->
    <!--            :button-text="'View all schools'"-->
    <!--            :button-callback="() => router.push('/browse/school')"-->
    <!--        />-->
    <!--        <SchoolSectionDashboard v-if="shouldStartSwrv" />-->
</template>


<style>
.InfoSection {
    width: auto !important;
    margin: auto !important;
    padding-right: 2rem !important;
}

.CardSection {
    max-width: fit-content !important;
    margin: auto !important;
}
</style>