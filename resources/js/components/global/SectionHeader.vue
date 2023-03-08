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
    <div :class="[this.classes]" class="flex flex-row justify-between h-[72px] w-full px-12 relative">
        <EventsCollection v-if="this.section === 'events'" class="absolute right-16"/>
        <SoftwareCollection v-if="this.section === 'software'" class="absolute right-16"/>
        <AdviceCollection v-if="this.section === 'advice'" class="absolute right-16"/>
        <SchoolsCollection v-if="this.section === 'schools'" class="absolute right-16"/>

        <slot name="header"></slot>

        <div class="self-center z-10">
            <slot name="cta"></slot>
        </div>
    </div>
    </div>
</template>
