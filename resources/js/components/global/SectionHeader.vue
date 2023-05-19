<script setup>
import EventsCollection from '../svg/EventsCollection.vue';
import SoftwareCollection from '../svg/SoftwareCollection.vue';
import AdviceCollection from '../svg/AdviceCollection.vue';
import SchoolsCollection from '../svg/SchoolsCollection.vue';
import LaptopCollection from "@/js/components/svg/hardware/LaptopCollection.vue";

const props = defineProps({
    classes: {
        type: String,
        required: false
    },
    section: {
        type: String,
        required: false
    },
    title: {
        type: String,
        required: true
    },
    buttonText: {
        type: String,
        required: true
    },
    buttonCallback:{
        type: Function,
        required: false,
        default: () => {}
    }
});

</script>

<template>
    <div class="mt-20 px-20">
        <div
            :class="[classes]"
            class="flex flex-row justify-between h-[72px] w-full px-12 relative group/headerbg place-items-center"
        >
            <EventsCollection
                v-if="section === 'events'"
                class="absolute top-1/2 -translate-y-1/2 right-16 group-hover/headerbg:scale-125 transition-all duration-500"
            />
            <SoftwareCollection
                v-if="section === 'software'"
                class="absolute top-1/2 -translate-y-1/2 right-16 group-hover/headerbg:scale-125 transition-all duration-500"
            />
            <AdviceCollection
                v-if="section === 'advice'"
                class="absolute top-1/2 -translate-y-1/2 right-16 group-hover/headerbg:scale-125 transition-all duration-500"
            />
            <SchoolsCollection
                v-if="section === 'schools'"
                class="absolute top-1/2 -translate-y-1/2 right-16 group-hover/headerbg:scale-125 transition-all duration-500"
            />
            <LaptopCollection
                v-if="section === 'laptops'"
                class="absolute top-1/2 -translate-y-1/2 right-16 group-hover/headerbg:scale-125 transition-all duration-500"
            />

            <h3 class="text-white text-4xl font-semibold self-center section-header uppercase">
                {{ title }}
            </h3>
            <button
                class="bg-white px-4 h-fit py-2 rounded-sm border-2 z-20 border-[#339999] text-[#339999] text-base font-medium cursor-pointer hover:text-[#307474] hover:border-2 hover:border-[#307474]"
                @click="props.buttonCallback"
            >
                {{ buttonText }}
            </button>
        </div>
    </div>
</template>
