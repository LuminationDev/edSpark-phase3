<script setup lang="ts">
import {computed} from 'vue';
import {useRouter} from "vue-router";

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
            h-[700px]
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
            class="eventCalenderSide flex flex-col gap-4 mb-4"
        >
            <div class="bg-[#F8F8F8] px-10 py-3">
                <h2 class="font-medium text-[24px]">
                    {{ date }}
                </h2>
            </div>

            <div
                v-for="(event, index) in eventArr"
                :key="index"
                class="cursor-pointer flex flex-row gap-4 h-[150px] p-4 rounded hover:bg-slate-50"
                @click="handleClickSingleEvent(event.id)"
            >
                <div
                    :class="eventTypeColorClass(event.type)"
                    class="min-h-full min-w-[8px] rounded-sm"
                />
                <div class="flex flex-col gap-4 overflow-hidden">
                    <h5 class="font-semibold text-[18px]">
                        {{ event.title }}
                    </h5>

                    <div
                        class="eventEvcerptTextInline"
                        v-html="event.excerpt"
                    />
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
