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
    
        class="TypeTag 
            border border-solid flex gap-2 h-[28px] py-1 place-items-center 
            px-2 rounded-2xl text-sm w-fit mx-3 my-2
            text-main-teal border-main-teal bg-main-teal/20 
            [&>svg]:fill-main-teal [&>svg]:stroke-main-teal"
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
