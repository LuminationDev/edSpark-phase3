<script setup lang="ts">
import 'v-calendar/style.css';

import {Calendar} from 'v-calendar';
import {computed, onMounted, ref} from 'vue';

import EventsCalendarPopup from "@/js/components/events/EventsCalendarPopup.vue";
import {EventType} from "@/js/types/EventTypes";

const eventCalendarRef = ref(null);
const showPopup = ref(false);
const dayEvents = ref([]);
const dateForPopup = ref('');

const props = defineProps({
    events: {
        type: Array as () => EventType[],
        required: true
    }
});

onMounted(() => {
    document.getElementsByClassName('vc-next')[0].setAttribute("aria-label", "Next");
    document.getElementsByClassName('vc-prev')[0].setAttribute("aria-label", "Previous");
})

const attributes = computed(() =>
    props.events.map(event => {
        const backgroundColor = event.type === 'Virtual' ? 'teal' : event.type === 'Hybrid' ? 'navy' : 'blueberry';
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
                    fillMode: 'light',
                    color: backgroundColor
                },
                base: {
                    fillMode: 'light',
                    color: backgroundColor
                },
                end: {
                    fillMode: 'light',
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

const handleClickTodayButton = () => {
    eventCalendarRef.value.focusDate(new Date())
}
// onMounted(() => {
//     eventCalendarRef.value.focusDate(new Date())
//
// })
</script>

<template>
    <div class="calendarWrapper mt-10">
        <div
            class="flex justify-end p-2 rounded-full"
        >
            <button
                class="bg-secondary-blueberry
                    hover:bg-secondary-blueberry/80
                    border-[1px]
                    px-4
                    py-2
                    rounded-full
                    text-white
                    
                    
                    hover:cursor-pointer"
                @click="handleClickTodayButton"
            >
                Today
            </button>
        </div>

        <Calendar
            ref="eventCalendarRef"
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
        class="absolute top-0 right-0 bottom-0 left-0 bg-black/30 z-40 w-screen min-w-[270px]"
    />
</template>


<style scoped>


/*
 vc-highlight-base-middle vc-highlight-bg-solid
*/

/* @media screen and (max-width: 620px) { */
    /* .vc-dots {
        display:none;
    } */
/* } */

.vc-highlight.vc-highlight-base-middle.vc-highlight-bg-solid {
    border-radius: 10px 0 0 10px !important;
}

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

.calendarWrapper :deep(.vc-focus:focus-within) {
    background-color: #0072DA;
    color: white;
    padding: 8px;
    box-shadow: 0 0 10px 10px white;
}

.calendarWrapper :deep(.vc-bars){
    height: 4px
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

:deep(.vc-accessiblePurple) {
    --vc-accent-50: #B7A5C5;
    --vc-accent-100: #A490BE;
    --vc-accent-200: #957BB7;
    --vc-accent-300: #8766B0;
    --vc-accent-400: #7851A9;
    --vc-accent-500: #6D469C;
    --vc-accent-600: #603D8C;
    --vc-accent-700: #54347D;
    --vc-accent-800: #472B6E;
    --vc-accent-900: #3B226F;
}

:deep(.vc-cherry) {
    --vc-accent-50: #F7D8E1;
    --vc-accent-100: #F0B6C7;
    --vc-accent-200: #E994AE;
    --vc-accent-300: #E27394;
    --vc-accent-400: #DB517B;
    --vc-accent-500: #DE4668; /* Original Cherry Color */
    --vc-accent-600: #C33E5E;
    --vc-accent-700: #A83554;
    --vc-accent-800: #8F2C4A;
    --vc-accent-900: #762442;
}


:deep(.vc-grape) {
    --vc-accent-50: #C6BAE8;
    --vc-accent-100: #B1A3E0;
    --vc-accent-200: #9C8BD9;
    --vc-accent-300: #5D569F;
    --vc-accent-400: #725EB3;
    --vc-accent-500: #8766C5;/* Original Grape Color */
    --vc-accent-600: #524D90;
    --vc-accent-700: #484481;
    --vc-accent-800: #3D3B72;
    --vc-accent-900: #333062;
}
:deep(.vc-blueberry) {
    --vc-accent-50: #9AC7F5;
    --vc-accent-100: #76B0F1;
    --vc-accent-200: #5298EC;
    --vc-accent-300: #2E81E8;
    --vc-accent-400: #1A6BE4;
    --vc-accent-500: #0072DA; /* Original Bluebaya Color */
    --vc-accent-600: #0066C1;
    --vc-accent-700: #005BAA;
    --vc-accent-800: #005093;
    --vc-accent-900: #00457C;
}
</style>
