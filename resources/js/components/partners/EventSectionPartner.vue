<script setup lang="ts">
import useSWRV from "swrv";

import EventsCalendar from "@/js/components/events/EventsCalendar.vue";
import EventsView from "@/js/components/events/EventsView.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {axiosFetcher} from "@/js/helpers/fetcher";
import {EventType} from "@/js/types/EventTypes";


const {
    data: allEvents,
    error: eventError
} = useSWRV<EventType[]>(API_ENDPOINTS.EVENT.FETCH_EVENT_POSTS, axiosFetcher, swrvOptions)

</script>

<template>
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
                        <div class="bg-secondary-cherry colorDot h-4 mx-4 rounded-full w-4" />
                        <p>Virtual </p>
                    </div>
                    <div class="flex items-center flex-row hybridLegend">
                        <div class="bg-secondary-grape colorDot h-4 mx-4 rounded-full w-4" />

                        <p>Hybrid</p>
                    </div>
                    <div class="flex items-center flex-row inPersonLegend">
                        <div class="bg-secondary-blueberry colorDot h-4 mx-4 rounded-full w-4" />
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
                    :loader-message="'Calendar loading'"
                />
            </div>
        </div>a
    </div>
</template>
