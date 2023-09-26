<script setup lang="ts">

import Hybrid from "@/js/components/svg/event/Hybrid.vue";
import InPerson from "@/js/components/svg/InPerson.vue";
import Virtual from "@/js/components/svg/Virtual.vue";

const props = defineProps({
    eventType: {
        type: String,
        required: true
    }
})

</script>

<template>
    <div
        class="TypeTag absolute top-4 right-0 bg-secondary-red flex gap-4 h-[39px] p-1 place-items-center px-6 rounded text-base text-white"
    >
        <template
            v-if="props.eventType === 'In Person'"
        >
            <InPerson />
        </template>
        <template
            v-else-if="props.eventType === 'Virtual'"
        >
            <Virtual />
        </template>
        <template
            v-else-if="props.eventType === 'Hybrid'"
        >
            <Hybrid />
        </template>
        {{ props.eventType }}
    </div>
</template>
