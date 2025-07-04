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
                class="absolute top-16 left-32 py-4 scale-120"
            />
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
                id="eventsBtn"
                :callback="() => router.push('/browse/event')"
                :type="'blue'"
            >
                View all events
            </GenericButton>
        </template>
        <template #sectionAction />
        <template #content>
            <BaseLandingCardRow
                v-if="windowStore.isMed"
                :resource-list="allEvents"
            >
                <template #rowContent>
                    <EventCard
                        v-for="event in allEvents.filter((event,index) => index < 2)"
                        :key="event.guid"
                        :data="event"
                        :show-icon="true"
                    />
                </template>
            </BaseLandingCardRow>

            <BaseLandingCardRow
                v-if="!windowStore.isMed"
                :resource-list="allEvents"
            >
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
            Providers
        </template>
        <template #subtitle>
            Explore the profiles of our educational providers
        </template>
        <template #button>
            <GenericButton
                id="partnersBtn"
                :callback="() => router.push('/browse/provider')"
                :type="'blue'"
            >
                View all providers
            </GenericButton>
        </template>
        <template #sectionAction />
        <template #content>
            <BaseLandingCardRow
                v-if="windowStore.isMed"
                :resource-list="allPartners"
            >
                <template #rowContent>
                    <PartnerCard
                        v-for="(partner,index) in allPartners.filter((event,index) => index < 2)"
                        :key="index"
                        :data="partner"
                    />
                </template>
            </BaseLandingCardRow>
            <BaseLandingCardRow
                v-if="!windowStore.isMed"
                :resource-list="allPartners"
            >
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
                    <div class="max-h-[850px] pl-4 sm:pl-8 lg:pl-8 pr-4 sm:pr-8 lg:pr-16 w-full  lg:!w-2/3">
                        <EventsCalendar
                            :events="allEvents"
                        />
                        <div class="calendarColorLegend flex shrink flex-col gap-2 mb-6 pt-5">
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
                    <div
                        class="overflow-y-auto w-full md:max-h-[500px] lg:!w-1/3 lg:max-h-[850px]"
                        tabindex="0"
                    >
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
