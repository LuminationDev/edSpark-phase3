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
    <div class="mb-6 mt-5 px-1 lg:!mt-20 lg:!px-20">
        <div
            :class="[classes]"
            class="
                flex
                justify-start
                items-start
                flex-col
                group/headerbg
                h-auto
                h-full
                pt-3
                px-6
                py-4
                relative
                w-full
                md:!flex-row
                md:!h-20
                md:!items-center
                md:!justify-between
                md:!px-12
                "
        >
            <EventsCollection
                v-if="section === 'events'"
                class="-translate-y-1/2 absolute top-1/2 right-16 duration-500 group-hover/headerbg:scale-125 transition-all"
            />
            <SoftwareCollection
                v-if="section === 'software' || section === 'hardware-laptops'"
                class="-translate-y-1/2 absolute top-1/2 right-16 duration-500 group-hover/headerbg:scale-125 transition-all"
            />
            <AdviceCollection
                v-if="section === 'advice' || section === 'hardware-emerging'"
                class="-translate-y-1/2 absolute top-1/2 right-16 duration-500 group-hover/headerbg:scale-125 transition-all"
            />
            <SchoolsCollection
                v-if="section === 'schools'"
                class="-translate-y-1/2 absolute top-1/2 right-16 duration-500 group-hover/headerbg:scale-125 transition-all"
            />
            <LaptopCollection
                v-if="section === 'laptops'"
                class="-translate-y-1/2 absolute top-1/2 right-16 duration-500 group-hover/headerbg:scale-125 transition-all"
            />

            <h3
                class="font-semibold h-full py-2 section-header text-3xl text-white uppercase lg:!text-4xl"
            >
                {{ title }}
            </h3>
            <button
                class="
                    bg-white
                    hover:bg-slate-100
                    border-[1px]
                    border-main-navy
                    cursor-pointer
                    font-medium
                    h-fit
                    px-4
                    py-2
                    text-base
                    text-main-navy
                    z-20
                    "
                @click="props.buttonCallback"
            >
                {{ buttonText }}
            </button>
        </div>
    </div>
</template>
