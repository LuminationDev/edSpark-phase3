<script setup>
import {storeToRefs} from "pinia";
import {computed, onMounted, ref} from "vue";
import {useRouter} from "vue-router";

import BaseLandingCardRow from "@/js/components/bases/BaseLandingCardRow.vue";
import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import BaseLandingSection from "@/js/components/bases/BaseLandingSection.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import CardLoading from "@/js/components/card/CardLoading.vue";
import PartnerCard from "@/js/components/partners/PartnerCard.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import HardwareRobot from "@/js/components/svg/hardwareRobot/HardwareRobot.vue";
import RobotEvents from "@/js/components/svg/RobotEvents.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {cardDataWithGuid} from "@/js/helpers/cardDataHelper";
import {eventService} from "@/js/service/eventService";
import {useEventsStore} from "@/js/stores/useEventsStore";

import EventsCalendar from '../components/events/EventsCalendar.vue';
import EventCard from "../components/events/EventsCard.vue";
import EventsView from '../components/events/EventsView.vue';

const router = useRouter()
const {allEvents} = storeToRefs(useEventsStore())
const allPartners = ref([])


import {useWindowStore} from "@/js/stores/useWindowStore";
const windowStore = useWindowStore()

onMounted(() => {
    axios.get(API_ENDPOINTS.PARTNER.FETCH_ALL_PARTNERS).then(res => {
        allPartners.value = cardDataWithGuid(res.data)
    })
})


onMounted(() => {
    eventService.fetchAllEvent().then(res => {
        allEvents.value = res
    })
})


</script>

<template>
    <BaseLandingHero
        :title="LandingHeroText['event']['title']"
        :title-paragraph="LandingHeroText['event']['subtitle']"
        class=""
        background-color="blue"
        swoosh-color="partnerBlue"
    >
        <template #robotIllustration>
            <RobotEvents
                class="absolute top-16 left-32 scale-120 py-4"
            />
            <!--            <img-->
            <!--                class="absolute top-32 left-36 scale-150"-->
            <!--                src="@/assets/images/PartnersPuzzle.png"-->
            <!--                alt="Industry Partners connecting pieces of the puzzle"-->
            <!--            >-->
        </template>
    </BaseLandingHero>
    <BaseLandingSection background-color="blue">
        <template #title>
            Events
        </template>
        <template #subtitle>
            See what events are being hosted by our various industry providers
        </template>
        <template #button>
            <GenericButton
                :callback="() => router.push('/browse/event')"
                :type="'blue'"
                :id="eventsBtn"
            >
                View all events
            </GenericButton>
        </template>
        <template #sectionAction />
        <template #content>

            <BaseLandingCardRow :resource-list="allEvents" v-if="windowStore.isMed">
                    <template #rowContent>
                    <EventCard
                        v-for="event in allEvents.filter((event,index) => index < 2)"
                        :key="event.guid"
                        :data="event"
                        :show-icon="true"
                    />
                </template>
            </BaseLandingCardRow>

            <BaseLandingCardRow :resource-list="allEvents" v-if="!windowStore.isMed">
                    <template #rowContent>
                    <EventCard
                        v-for="event in allEvents.filter((event,index) => index < 3)"
                        :key="event.guid"
                        :data="event"
                        :show-icon="true"
                    />
                </template>
            </BaseLandingCardRow>
        </template>
    </BaseLandingSection>

    <BaseLandingSection background-color="white">
        <template #title>
            Industry providers
        </template>
        <template #subtitle>
            Explore the profiles of our educational industry providers
        </template>
        <template #button>
            <GenericButton
                :callback="() => router.push('/browse/partner')"
                :type="'blue'"
                :id="partnersBtn"
            >
                View all partners
            </GenericButton>
        </template>
        <template #sectionAction />
        <template #content>
            <BaseLandingCardRow :resource-list="allPartners" v-if="windowStore.isMed">
                <template #rowContent>
                    <PartnerCard
                        v-for="(partner,index) in allPartners.filter((event,index) => index < 2)"
                        :key="index"
                        :data="partner"
                    />
                </template>
            </BaseLandingCardRow>
            <BaseLandingCardRow :resource-list="allPartners" v-if="!windowStore.isMed">
                <template #rowContent>
                    <PartnerCard
                        v-for="(partner,index) in allPartners.filter((event,index) => index < 3)"
                        :key="index"
                        :data="partner"
                    />
                </template>
            </BaseLandingCardRow>
        </template>
    </BaseLandingSection>

    <BaseLandingSection background-color="blue">
        <template #title>
            Event calendar
        </template>
        <template #subtitle>
            Browse through the events our providers and partners have on offer
        </template>
        <template #content>
            <div class="eventCalendarContainer flex flex-col h-full">
                <div
                    v-if="allEvents && allEvents.length > 0"
                    class="flex lg:flex-row flex-col flex-wrap"
                >
                    <div class="pl-4 pr-4 lg:pr-16 lg:pl-8 sm:pr-8 sm:pl-8  w-full lg:!w-2/3 max-h-[850px]">
                        <EventsCalendar
                            :events="allEvents"
                        />
                        <div class="calendarColorLegend flex shrink flex-col gap-2 pt-5 mb-6">
                            <div class="colorLegendTitle font-semibold">
                                Calendar legend
                            </div>
                            <div class="flex items-center flex-row virtualLegend">
                                <div class="bg-event-virtual colorDot h-4 mx-4 rounded-full w-4" />
                                <p>Virtual </p>
                            </div>
                            <div class="flex items-center flex-row hybridLegend">
                                <div class="bg-event-hybrid colorDot h-4 mx-4 rounded-full w-4" />

                                <p>Hybrid</p>
                            </div>
                            <div class="flex items-center flex-row inPersonLegend">
                                <div class="bg-event-inPerson colorDot h-4 mx-4 rounded-full w-4" />
                                <p>In Person</p>
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:!w-1/3 md:max-h-[500px] lg:max-h-[850px] overflow-y-auto" tabindex="0">
                        <EventsView
                            :events="allEvents"
                            tabindex="0"
                        />
                    </div>
                </div>
                <div
                    v-else
                    class="flex justify-center py-10"
                >
                    <div class="font-thin text-xl">
                        <Loader
                            :loader-color="'#0072DA'"
                            :loader-message="'Calendar loading'"
                        />
                    </div>
                </div>
            </div>
        </template>
    </BaseLandingSection>
</template>
