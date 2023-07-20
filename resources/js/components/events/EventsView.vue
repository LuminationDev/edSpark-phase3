<script setup>
import { ref } from 'vue';
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

</script>

<template>
    <div class="mt-10 px-6 pb-6 w-full h-[700px] overflow-y-scroll scrollbar-thin scrollbar-thumb-custom scrollbar-thumb-custom-genericScrollbarDark scrollbar-thumb-rounded-full mr-2">
        <div
            v-for="(eventArr, date) in sortedEvents"
            :key="date"
            class="flex flex-col gap-4 mb-4"
        >
            <div class="bg-[#F8F8F8] px-10 py-3">
                <h2 class="text-[24px] font-medium">
                    {{ date }}
                </h2>
            </div>

            <div
                v-for="(event, index) in eventArr"
                :key="index"
                class="flex flex-row gap-4 p-4 h-[150px] cursor-pointer rounded hover:bg-slate-50"
                @click="handleClickSingleEvent(event.event_id)"
            >
                <div
                    :class="event.event_type === 'Virtual' ? 'bg-red-500' : 'bg-blue-500'"
                    class="min-w-[8px] min-h-full rounded-sm"
                />
                <div class="flex flex-col gap-4 overflow-hidden">
                    <h5 class="font-semibold text-[18px]">
                        {{ event.event_title }}
                    </h5>

                    <div
                        class="eventEvcerptTextInline"
                        v-html="event.event_excerpt"
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

    .eventEvcerptTextInline >>> p {
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
