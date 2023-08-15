<script setup>

import { ref } from 'vue';

import EventsCard from '../events/EventsCard.vue';
import CardLoading from '../card/CardLoading.vue';

const colCount = ref(3);
const rowCount = ref(1);

const props = defineProps({
    events: {
        type: Array,
        required: true
    },
    eventsLoading: {
        type: Boolean,
        required: true
    }
});

</script>

<template>
    <div class="px-huge py-8">
        <div
            v-if="!eventsLoading"
            class="flex justify-between flex-1 flex-row flex-wrap"
        >
            <EventsCard
                v-for="(event, index) in events.slice(0,colCount)"
                :key="index"
                :event-data="event"
                :number-per-row="3"
            />
        </div>
    </div>
</template>
