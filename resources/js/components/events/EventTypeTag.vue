<script setup lang="ts">
import {computed} from 'vue'

import Hybrid from "@/js/components/svg/event/Hybrid.vue";
import InPerson from "@/js/components/svg/InPerson.vue";
import Virtual from "@/js/components/svg/Virtual.vue";

const props = defineProps({
    eventType: {
        type: String,
        required: true
    }
})

const typeTagColor = computed(() => {
    if (props.eventType === 'Virtual') {
        return 'text-main-teal border-main-teal bg-main-teal/10 [&>svg]:fill-main-teal [&>svg]:stroke-main-teal';

    } else if (props.eventType === 'Hybrid') {
        return 'text-main-navy border-main-navy bg-main-navy/10 [&>svg]:fill-main-navy [&>svg]:stroke-main-navy';

    } else { //must be In Person
        return 'text-secondary-blueberry border-secondary-blueberry bg-secondary-blueberry/10 [&>svg]:fill-secondary-blueberry [&>svg]:stroke-secondary-blueberry';
    }
})
</script>



<!-- text-main-teal border-main-teal bg-main-teal/10 
[&>svg]:fill-main-teal [&>svg]:stroke-main-teal" -->

<template>
    <div v-if="props.eventType"

        class="TypeTag whitespace-nowrap
            border border-solid flex gap-2 h-[28px] py-1 place-items-center 
            px-2 rounded-2xl text-sm w-fit ml-3 mr-0 my-2"
        :class="typeTagColor"
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
