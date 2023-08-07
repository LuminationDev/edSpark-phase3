<script setup>
import GenericCard from '../card/GenericCard.vue';
import InPerson from '../svg/InPerson.vue';
import Virtual from '../svg/Virtual.vue';
import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";
import {computed} from "vue";
import {useRouter} from "vue-router";
import Hybrid from "@/js/components/svg/event/Hybrid.vue";

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
    updated_at
} = props.eventContent;

const router = useRouter();

const handleClickEventCard = () => {
    /**
     * id inside param must be the same as the route path specified for event-single
     * which is /event/resources/:id
     */
    router.push({
        name: "event-single",
        params: {id: props.eventContent.event_id},
        state: {content: JSON.stringify(props.eventContent)},
    })
}

const {currentUser} = storeToRefs(useUserStore())

const likeBookmarkData = {
    post_id: props.eventContent.event_id,
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
        :display-date="start_date"
        :end-date="end_date"
        :number-per-row="numberPerRow"
        :cover-image="cover_image"
        :like-bookmark-data="likeBookmarkData"
        :click-callback="handleClickEventCard"
    >
        <template #typeTag>
            <div
                class="
                    TypeTag
                    absolute
                    top-4
                    right-0
                    bg-secondary-red
                    flex
                    h-[39px]
                    p-1
                    place-items-center
                    px-6
                    rounded
                    text-base
                    text-white
                    gap-4
                    sm:!-right-4
                    md:!-right-6
                    "
            >
                <template
                    v-if="event_type === 'In Person'"
                >
                    <InPerson />
                </template>
                <template
                    v-else-if="event_type === 'Virtual'"
                >
                    <Virtual />
                </template>
                <template
                    v-else-if="event_type === 'Hybrid'"
                >
                    <Hybrid />
                </template>
                {{ event_type }}
            </div>
        </template>
    </GenericCard>
</template>