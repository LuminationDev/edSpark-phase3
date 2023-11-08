<script setup>
import useSWRV from "swrv";
import { computed } from "vue";
import {useRouter} from "vue-router";

import CardLoading from "@/js/components/card/CardLoading.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {guid} from "@/js/helpers/guidGenerator";
import {useUserStore} from "@/js/stores/useUserStore";

import EventsCalendar from '../components/events/EventsCalendar.vue';
import EventCard from "../components/events/EventsCard.vue";
import EventsHero from '../components/events/EventsHero.vue';
import EventsView from '../components/events/EventsView.vue';
import SectionHeader from '../components/global/SectionHeader.vue';
import {axiosFetcher} from "../helpers/fetcher";

const router = useRouter()
const { data: allEvents, error: eventError } = useSWRV(API_ENDPOINTS.EVENT.FETCH_EVENT_POSTS, axiosFetcher(useUserStore().getUserRequestParam), swrvOptions)

</script>

<template>
    <EventsHero />
    <SectionHeader
        :classes="'bg-event-virtual '"
        :section="'events'"
        :title="'Upcoming Events'"
        :button-text="'View all events'"
        :button-callback="() => router.push('/browse/event')"
    />
    <div class="EventContentContainer flex flex-col h-full px-5 lg:!px-20">
        <div class="EventCardListContainer grid grid-cols-1 gap-10 place-items-center heading text-xl  md:!grid-cols-2 xl:!grid-cols-3">
            <template v-if="allEvents && allEvents.length > 0">
                <EventCard
                    v-for="event in allEvents.filter((event,index) => index < 3)"
                    :key="event.guid"
                    :data="event"
                    :show-icon="true"
                />
            </template>
            <template v-else>
                <div
                    class="col-span-1 md:!col-span-2 lg:!col-span-3"
                >
                    <CardLoading
                        :number-of-rows="1"
                        :number-per-row="3"
                    />
                </div>
            </template>
        </div>
    </div>

    <SectionHeader
        :classes="'bg-event-virtual'"
        :section="'events'"
        :title="'Calendar'"
        :button-text="'View all events'"
        :button-callback="() => router.push('/browse/event')"
    />

    <div class="eventCalendarContainer flex flex-col h-full px-5 lg:!px-20">
        <div
            v-if="allEvents && allEvents.length > 0"
            class="flex lg:flex-row flex-col flex-wrap"
        >
            <div class="pl-8 w-full lg:!w-2/3">
                <EventsCalendar
                    :events="allEvents"
                />
                <div class="calendarColorLegend flex shrink flex-col gap-2 pt-5">
                    <div class="colorLegendTitle font-semibold">
                        Calendar Colors Legend
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
            <div class="w-full lg:!w-1/3">
                <EventsView
                    :events="allEvents"
                />
            </div>
        </div>
        <div
            v-else
            class="flex justify-center py-10"
        >
            <div class="font-semibold text-xl">
                <Loader
                    :loader-color="'#0072DA'"
                    :loader-message="'Calendar Loading'"
                />
            </div>
        </div>
    </div>
</template>
