<script setup>
// import EventsCalendarPopup from './EventsCalendarPopup.vue';

import EventsCalendarPopup from "@/js/components/events/EventsCalendarPopup.vue";
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
const attributes = computed(() =>
    props.events.map(event => {
        // const backgroundColor = event.type === 'Virtual' ? '#BF123D' : event.type === 'Hybrid' ? '#A855F7' : '#3B82F6';
        const backgroundColor = event.type === 'Virtual' ? 'mbRose' : event.type === 'Hybrid' ? 'purple' : 'blue';
        return {
            dates: [[event.start_date, event.end_date]],
            key: event.id,
            popover: {
                label: event.title
            },
            dot: {
                style: {
                    backgroundColor: backgroundColor,
                }
            },
            highlight: {
                start: {
                    fillMode: 'outline',
                    color: backgroundColor
                },
                base: {
                    fillMode: 'solid',
                    color: backgroundColor
                },
                end: {
                    fillMode: 'outline',
                    color: backgroundColor
                },
            },
            customData: event
        };
    })
);

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

const initialCalendarPage = computed(() => {
    const currentDate = new Date();
    return {
        month: currentDate.getMonth() + 1,
        year: currentDate.getFullYear()
    }
})
</script>

<template>
    <div class="calendarWrapper mt-10">
        <Calendar
            ref="eventCalendar"
            borderless
            expanded
            :attributes="attributes"
            show-weeknumbers="left-outside"
            :initial-page="initialCalendarPage"
            @dayclick="handleDayClick"
        />
    </div>


    <EventsCalendarPopup
        v-if="showPopup"
        :day-events="dayEvents"
        :date="dateForPopup"
        @emit-close-popup="closePopup"
    />
    <div
        v-if="showPopup"
        class="absolute top-0 right-0 bottom-0 left-0 bg-black/30 z-40"
    />
</template>


<style scoped>

.calendarWrappe :deep(.vc-pane) {
    background-color: #f8f8f8;
}

.calendarWrapper :deep(.vc-header) {
    margin-bottom: 1.5rem !important;
}

.calendarWrapper :deep(.vc-weeknumber-content) {
    color: #6b7585;
}

.calendarWrapper :deep(.vc-day-box-center-center) {
    height: 68px;
}

:deep(.vc-dfeteal) {
    --vc-accent-50: #e7fcfd;
    --vc-accent-100: #b8f5fa;
    --vc-accent-200: #89eef6;
    --vc-accent-300: #59e7f2;
    --vc-accent-400: #2ae0ef;
    --vc-accent-500: #10c6d5;
    --vc-accent-600: #0d9aa6;
    --vc-accent-700: #0A7982;
    --vc-accent-800: #096e76;
    --vc-accent-900: #054247;
}

:deep(.vc-navy) {
    --vc-accent-50: #4D698A;
    --vc-accent-100: #405E82;
    --vc-accent-200: #335379;
    --vc-accent-300: #264871;
    --vc-accent-400: #193D69;
    --vc-accent-500: #0D3360;
    --vc-accent-600: #002654;
    --vc-accent-700: #00244F;
    --vc-accent-800: #00224B;
    --vc-accent-900: #002046;
}

:deep(.vc-customRed) {
    --vc-accent-50: #FAE3E8;
    --vc-accent-100: #F7D1D9;
    --vc-accent-200: #F3BECA;
    --vc-accent-300: #F0ACBB;
    --vc-accent-400: #ED99AC;
    --vc-accent-500: #EA879D;
    --vc-accent-600: #E6748E;
    --vc-accent-700: #E3627F;
    --vc-accent-800: #E04F70;
    --vc-accent-900: #C83F5E;
}

:deep(.vc-customGreen) {
    --vc-accent-50: #CDE6DA;
    --vc-accent-100: #B4DAC8;
    --vc-accent-200: #9BCDB5;
    --vc-accent-300: #82C1A3;
    --vc-accent-400: #68B490;
    --vc-accent-500: #4FA87E;
    --vc-accent-600: #369B6B;
    --vc-accent-700: #1D8F59;
    --vc-accent-800: #047C43;
    --vc-accent-900: #036F3C;
}

:deep(.vc-mbRose) {
    --vc-accent-50: #F5CDD4;
    --vc-accent-100: #EB9CA2;
    --vc-accent-200: #E07588;
    --vc-accent-300: #D54E6E;
    --vc-accent-400: #CA2854;
    --vc-accent-500: #BE123C;
    --vc-accent-600: #BE123C;
    --vc-accent-700: #8E0E28;
    --vc-accent-800: #760B1E;
    --vc-accent-900: #5E0914;
}
</style>
