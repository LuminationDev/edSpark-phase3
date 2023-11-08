<script setup>
import {useRouter} from "vue-router";

import EventTypeTag from "@/js/components/events/EventTypeTag.vue";
import {lowerSlugify} from "@/js/helpers/slugifyHelper";

import GenericCard from '../card/GenericCard.vue';

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