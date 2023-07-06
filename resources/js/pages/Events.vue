<script setup>
import EventsHero from '../components/events/EventsHero.vue';
import EventsCalendar from '../components/events/EventsCalendar.vue';
import EventsView from '../components/events/EventsView.vue';
import SectionHeader from '../components/global/SectionHeader.vue';
import { onBeforeMount , ref, computed } from "vue";
import useSWRV from "swrv";

import Spinner from "../components/spinner/Spinner.vue";
import EventCard from "../components/events/EventsCard.vue";
import {serverURL} from "../constants/serverUrl";
import {axiosFetcher} from "../helpers/fetcher";
import {useRouter} from "vue-router";
import Loader from "@/js/components/spinner/Loader.vue";
import {guid} from "@/js/helpers/guidGenerator";

const router = useRouter()
const { data: allEvents, error: eventError } = useSWRV(`${serverURL}/fetchEventPosts`, axiosFetcher)

const allEventsWithKeys = computed(() =>{
    if(!allEvents.value) return []
    return allEvents.value.map(event => {
        event['key'] = guid()
        return event
    })
})
</script>

<template>
    <EventsHero />
    <SectionHeader
        :classes="'bg-[#C73858]'"
        :section="'events'"
        :title="'Upcoming Events'"
        :button-text="'View all events'"
        :button-callback="() => router.push('/browse/events')"
    />
    <div class="EventContentContainer flex flex-col h-full px-20">
        <div class="EventCardListContainer heading text-xl pt-10 flex flex-row flex-1 justify-between flex-wrap  gap-6">
            <EventCard
                v-for="event in allEventsWithKeys.filter((event,index) => index < 3)"
                :key="event['key']"
                :event-content="event"
                :show-icon="true"
            />
        </div>
    </div>

    <SectionHeader
        :classes="'bg-[#C73858]'"
        :section="'events'"
        :title="'Calendar'"
        :button-text="'View all events'"
        :button-callback="() => router.push('/browse/events')"
    />

    <div class="eventCalendarContainer flex flex-col h-full px-20 mt-20">
        <div
            v-if="allEvents && allEvents.length > 0"
            class="flex flex-row flex-wrap"
        >
            <div class="w-2/3 pl-8">
                <EventsCalendar
                    :events="allEvents"
                />
            </div>
            <div class="w-1/3">
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
