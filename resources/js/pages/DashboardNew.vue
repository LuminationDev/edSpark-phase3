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
import EventsCard from "@/js/components/events/EventsCard.vue";
import SchoolProfileGuidesQuickFilters from "@/js/components/inspirationandguides/SchoolProfileGuidesQuickFilters.vue";
import SchoolCard from "@/js/components/schools/SchoolCard.vue";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {serverURL} from "@/js/constants/serverUrl";
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
                    :id="schoolsBtn"
                >
                    View all schools
                </GenericButton>
            </template>
            <template #sectionAction>
                <SchoolProfileGuidesQuickFilters />
            </template>
            <template #content>
                <BaseLandingCardRow :resource-list="featuredSchools" v-if="windowStore.isMed">
                    <template #rowContent>
                        <SchoolCard
                            v-for="(school,index) in getNRandomElementsFromArray(featuredSchools,2)"
                            :key="index"
                            :data="school"
                        />
                    </template>
                </BaseLandingCardRow>
                <BaseLandingCardRow :resource-list="featuredSchools" v-if="!windowStore.isMed">
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
                    :id="guidesBtn"
            >
                View all guides
            </GenericButton>
        </template>
        <template #content>
            <BaseLandingCardRow :resource-list="guideList" v-if="windowStore.isMed">
                <template #rowContent>
                    <AdviceCard
                        v-for="(guide,index) in getNRandomElementsFromArray(guideList,2)"
                        :key="index"
                        :data="guide"
                    />
                </template>
            </BaseLandingCardRow>
            <BaseLandingCardRow :resource-list="guideList" v-if="!windowStore.isMed">
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
                :callback="() => router.push('browse/software')"
                :type="'teal'"
                    :id="techBtn"
            >
                View all technology
            </GenericButton>
        </template>
        <template #content>
            <div class="flex flex-col gap-6 group/bg h-full relative lg:!flex-row">
                <div class="flex flex-col max-h-[1000px] place-items-center w-full lg:w-[40%] xl:pl-8">
                    <SoftwareIllustration />
                </div>
                <template v-if="!allSoftware || !allSoftware.length">
                    <CardLoading
                        :number-of-rows="1"
                        :number-per-row="2"
                    />
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
            <BaseLandingCardRow :resource-list="eventList" v-if="windowStore.isMed">
                <template #rowContent>
                    <EventsCard
                        v-for="(event,index) in getNRandomElementsFromArray(eventList,2)"
                        :key="index"
                        :data="event"
                    />
                </template>
            </BaseLandingCardRow>
            <BaseLandingCardRow :resource-list="eventList" v-if="!windowStore.isMed">
                <template #rowContent>
                    <EventsCard
                        v-for="(event,index) in getNRandomElementsFromArray(eventList,3)"
                        :key="index"
                        :data="event"
                    />
                </template>
            </BaseLandingCardRow>
        </template>
    </BaseLandingSection>
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