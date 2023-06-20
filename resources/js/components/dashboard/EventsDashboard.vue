<script setup>

import EventsCard from '../events/EventsCard.vue';
// import CardLoading from '../card/CardLoading.vue';

const props = defineProps({
    events: {
        type: Array,
        required: true
    },
});

</script>

<template>
    <div class="py-8 px-huge">
        <div
            class="flex flex-row flex-1 flex-wrap justify-between"
        >
            <EventsCard
                v-for="(event, index) in events.slice(0,3)"
                :key="index"
                :event-content="event"
                :number-per-row="3"
            />
        </div>
    </div>
</template>
