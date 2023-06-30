<script setup>
import EventsCalendarPopup from './EventsCalendarPopup.vue';

import {Calendar} from 'v-calendar';
import 'v-calendar/style.css';

import {ref, computed} from 'vue';

const eventCalendar = ref(null);
const showPopup = ref(false);
const dayEvents = ref([]);
const dateForPopup = ref('');

const props = defineProps({
    events: {
        type: Array,
        required: true
    }
});

/**
 * Attempt to build some data for the calendar
 */
const attributes = computed(() => {
    if(!props.events || props.events.length === 0) return []
    else{
        return props.events.map(event => ({
            dates: [[event.start_date, event.end_date]],
            key: event.event_id,
            popover: {
                label: event.event_title
            },
            dot: {
                style: {
                    backgroundColor: event.event_type === 'Virtual' ? 'red' : 'blue',
                }
            },
            highlight: event.event_type === 'Virtual' ? 'red' : 'blue',
            customData: event
        }))

    }
});

const handleDayClick = (dayData, event) => {
    if (dayData.attributes.length > 0) {
        showPopup.value = true;
        document.body.style.overflow = 'hidden';

        /**
         * Setup some flavours for the popup
         */
        dayEvents.value = dayData.attributes;
        dateForPopup.value = dayData.date;
    }
};

const closePopup = () => {
    showPopup.value = false;
    document.body.style.overflow = 'auto';
    dayEvents.value = [];
    dateForPopup.value = '';
}

</script>

<template>
    <div class="calendarWrapper">
        <Calendar
            ref="eventCalendar"
            borderless
            expanded
            :attributes="attributes"
            is-dark="system"
            show-weeknumbers="left-outside"
            @dayclick="handleDayClick"
        />
    </div>


    <EventsCalendarPopup
        v-if="showPopup"
        :day-events="dayEvents"
        :date="dateForPopup"
        @emitClosePopup="closePopup"
    />
    <div
        v-if="showPopup"
        class="absolute top-0 right-0 bottom-0 left-0 bg-black/30 z-40"
    />
</template>


<style>

.vc-header {
    margin-bottom: 1.5rem !important;
}

.vc-day-box-center-center {
    height: 68px;
}
</style>
