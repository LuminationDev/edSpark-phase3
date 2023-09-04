<script setup>
import {computed, ref} from 'vue';
import {useRouter} from "vue-router";

const props = defineProps({
    events: {
        type: Array,
        required: true
    }
});

const router = useRouter()
const sortedEvents = ref({});

const arraySorter = () => {
    let sorted = props.events.sort((a, b) => {
        return new Date(a.start_date) - new Date(b.start_date);
    });

    let sortedData = {};

    sorted.forEach(obj => {
        const date = obj.start_date.split(' ')[0];

        if (sortedData.hasOwnProperty(date)) {
            sortedData[date].push(obj);
        } else {
            sortedData[date] = [obj];
        }
    });

    sortedEvents.value = sortedData
};

arraySorter();

const handleClickSingleEvent = (eventId) => {
    console.log('Clicked the event with id: ', eventId);
    router.push({
        name:"event-single",
        params: { id: eventId},
    })
}
const eventTypeColorClass = (eventType) => {
    switch (eventType){
    case 'Virtual':
        return "bg-[#C73858]"
        break;
    case "In Person":
        return "bg-blue-500"
        break;
    case "Hybrid":
        return "bg-purple-500"
    }
}
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
            class="flex flex-col gap-4 mb-4"
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
