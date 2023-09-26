<script setup>
import {storeToRefs} from "pinia";
import {useRouter} from "vue-router";

import EventTypeTag from "@/js/components/events/EventTypeTag.vue";
import Hybrid from "@/js/components/svg/event/Hybrid.vue";
import lowerSlugify from "@/js/helpers/slugifyHelper";
import {useUserStore} from "@/js/stores/useUserStore";

import GenericCard from '../card/GenericCard.vue';
import InPerson from '../svg/InPerson.vue';
import Virtual from '../svg/Virtual.vue';

const props = defineProps({
    data: {
        type: Object, required: true
    },
    showIcon: {
        type: Boolean, required: false
    },

});


const router = useRouter();

const handleClickEventCard = () => {
    /**
     * id inside param must be the same as the route path specified for event-single
     * which is /event/resources/:id
     */
    router.push({
        name: "event-single",
        params: {id: props.data.id, slug: lowerSlugify(props.data.title)},
        state: {content: JSON.stringify(props.data)},
    })
}

const {currentUser} = storeToRefs(useUserStore())


</script>

<template>
    <GenericCard
        :id="data.id"
        :key="data.guid"
        :title="data.title"
        :display-content="data.excerpt"
        :display-author="data.author"
        :display-date="data.start_date"
        :end-date="data.end_date"
        :cover-image="data.cover_image"
        :click-callback="handleClickEventCard"
        :is-liked-by-user="data.isLikedByUser"
        :is-bookmarked-by-user="data.isBookmarkedByUser"
        section-type="event"
        :guid="data.guid"
    >
        <template #typeTag>
            <EventTypeTag
                class="sm:!-right-4 md:!-right-6"
                :event-type="data.type"
            />
        </template>
    </GenericCard>
</template>