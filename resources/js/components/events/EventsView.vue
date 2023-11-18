<script setup lang="ts">
import {computed} from 'vue';
import {useRouter} from "vue-router";

import EventTypeTag from "@/js/components/events/EventTypeTag.vue";
import {imageURL} from "@/js/constants/serverUrl";
import {formatDateToTimeOnly} from "@/js/helpers/dateHelper";
import {EventType} from "@/js/types/EventTypes";

const props = defineProps({
    events: {
        type: Array as () => EventType[],
        required: true
    }
});

const router = useRouter();

// Function to sort events by start_date
const sortEventsByDate = (events: EventType[]): EventType[] => {
    return [...events].sort((a, b) => {
        return new Date(a.start_date).getTime() - new Date(b.start_date).getTime();
    });
};

// group sorted events by date
const groupEventsByDate = (sorted: EventType[]): Record<string, EventType[]> => {
    const sortedData: Record<string, EventType[]> = {};

    sorted.forEach(event => {
        const date = event.start_date.split(' ')[0];
        if (sortedData[date]) {
            sortedData[date].push(event);
        } else {
            sortedData[date] = [event];
        }
    });

    return sortedData;
};

const sortedEvents = computed(() => {
    return groupEventsByDate(sortEventsByDate(props.events));
});

const handleClickSingleEvent = (eventId: number): void => {
    router.push({
        name: "event-single",
        params: {id: eventId},
    });
};

const eventTypeColorClass = (eventType: string): string => {
    switch (eventType) {
    case 'Virtual':
        return "bg-[#C73858]";
    case "In Person":
        return "bg-blue-500";
    case "Hybrid":
        return "bg-purple-500";
    default:
        return "";
    }
};
</script>

<template>
    <div
        class="
            mr-2
            mt-10
            overflow-y-scroll
            pb-6
            px-6
            scrollbar-thin
            scrollbar-thumb-custom
            scrollbar-thumb-custom-genericScrollbarDark
            scrollbar-thumb-rounded-full
            w-full
            "
    >
        <div
            v-for="(eventArr, date) in sortedEvents"
            :key="date"
            class="eventCalenderSide flex flex-col gap-2"
        >
            <div class="bg-[#F8F8F8] px-5 py-2">
                <h2 class="font-medium text-[24px]">
                    {{ date }}
                </h2>
            </div>

            <div
                v-for="(event, index) in eventArr"
                :key="index"
                class="cursor-pointer flex flex-col gap-2 mb-4 overflow-hidden pb-4 px-4 relative rounded hover:bg-slate-50"
                @click="handleClickSingleEvent(event.id)"
            >
                <div class="flex previewImage">
                    <img
                        :src="imageURL+ '/' + event.cover_image"
                        :alt="event.title"
                        class="h-24 object-cover object-top w-full"
                    >
                </div>
                <div class="flex flex-row gap-4">
                    <div
                        :class="eventTypeColorClass(event.type)"
                        class="min-h-full min-w-[8px] rounded-sm"
                    />
                    <div class="flex flex-col gap-4 overflow-hidden">
                        <h5 class="font-semibold text-[18px]">
                            {{ event.title }}
                        </h5>
                        <div class="">
                            {{ `${formatDateToTimeOnly(event.start_date)}-${formatDateToTimeOnly(event.end_date)}` }}
                        </div>

                        <div
                            class="eventEvcerptTextInline"
                            v-html="event.excerpt"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.eventEvcerptTextInline {
    max-width: 354px;
}

.eventEvcerptTextInline :deep(p) {
    max-width: 354px;
    width: 354px;
    overflow: hidden;
    text-overflow: ellipsis;
    height: 100px;
}
</style>

<style>
::-webkit-scrollbar-track {
    margin-top: 1rem !important;
    margin-bottom: 1rem !important;
    margin-right: 1rem !important;
}
</style>
