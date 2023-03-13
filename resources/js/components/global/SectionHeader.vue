<script>
    import EventsCollection from '../svg/EventsCollection.vue';
    import SoftwareCollection from '../svg/SoftwareCollection.vue';
    import AdviceCollection from '../svg/AdviceCollection.vue';
    import SchoolsCollection from '../svg/SchoolsCollection.vue';

    export default {

        components: {
            EventsCollection,
            SoftwareCollection,
            AdviceCollection,
            SchoolsCollection
        },

        props: {
            classes: String,
            section: String
        },

        mounted() {
            console.log(this.section)
        },
    }
</script>

<template>
    <div class="px-[81px] mt-20">
    <div :class="[this.classes]" class="flex flex-row justify-between h-[72px] w-full px-12 relative group/headerbg">
        <EventsCollection v-if="this.section === 'events'" class="absolute top-1/2 -translate-y-1/2 right-16 group-hover/headerbg:scale-125 transition-all duration-500"/>
        <SoftwareCollection v-if="this.section === 'software'" class="absolute top-1/2 -translate-y-1/2 right-16 group-hover/headerbg:scale-125 transition-all duration-500"/>
        <AdviceCollection v-if="this.section === 'advice'" class="absolute top-1/2 -translate-y-1/2 right-16 group-hover/headerbg:scale-125 transition-all duration-500"/>
        <SchoolsCollection v-if="this.section === 'schools'" class="absolute top-1/2 -translate-y-1/2 right-16 group-hover/headerbg:scale-125 transition-all duration-500"/>

        <slot name="header"></slot>

        <div class="self-center z-10">
            <slot name="cta"></slot>
        </div>
    </div>
    </div>
</template>
