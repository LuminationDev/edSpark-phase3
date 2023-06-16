<script setup>
import GenericCard from '../card/GenericCard.vue';
import InPerson from '../svg/InPerson.vue';
import Virtual from '../svg/Virtual.vue';
import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";
import { computed } from "vue";
import { useRouter } from "vue-router";

const props = defineProps({
    eventContent: {
        type: Object, required: true
    },
    showIcon: {
        type: Boolean, required: false
    },
    numberPerRow: {
        type: Number, required: false, default: 3
    }
});

const {
    event_id,
    author,
    cover_image,
    created_at,
    end_date,
    event_excerpt,
    event_status,
    event_title,
    event_type,
    start_date,
    updated_at } = props.eventContent;

const router = useRouter();

const handleClickEventCard = () => {
    /**
     * id inside param must be the same as the route path specified for event-single
     * which is /event/resources/:id
     */
    router.push({
        name:"event-single",
        params: { id: props.eventContent.event_id},
        state: {content: JSON.stringify(props.eventContent)},
    })
}

const {currentUser } = storeToRefs(useUserStore())

const likeBookmarkData = {
    post_id: props.event.event_id,
    user_id: currentUser.value.id, // to be replaced with userId from userStore
    post_type: 'event'
}

</script>

<template>
    <GenericCard
        :key="event_id"
        :title="event_title"
        :display-content="event_excerpt"
        :display-author="author"
        :display-date="created_at"
        :number-per-row="numberPerRow"
        :cover-image="cover_image"
        :like-bookmark-data="likeBookmarkData"
        :click-callback="handleClickEventCard"
    />
</template>