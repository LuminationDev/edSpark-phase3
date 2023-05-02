<script setup>

    import EventsCard from '../events/EventsCard.vue';

    const props = defineProps({
        events: {
            type: Array,
            required: true
        }
    });
</script>

<template>
    <div class="py-20 px-20">
        <div class="flex flex-row flex-1 flex-wrap justify-between">
            <EventsCard
                v-for="(event, index) in events.slice(0,3)"
                :key="index"
                :event="event"
                :numberPerRow="3"
            />
        </div>
    </div>
</template>
