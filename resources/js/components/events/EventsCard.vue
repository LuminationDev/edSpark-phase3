<script setup>
import lowerSlugify from "@/js/helpers/slugifyHelper";
import GenericCard from '../card/GenericCard.vue';
import InPerson from '../svg/InPerson.vue';
import Virtual from '../svg/Virtual.vue';
import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";
import {computed} from "vue";
import {useRouter} from "vue-router";
import Hybrid from "@/js/components/svg/event/Hybrid.vue";

const props = defineProps({
    eventData: {
        type: Object, required: true
    },
    showIcon: {
        type: Boolean, required: false
    },

});

// eslint-disable-next-line vue/no-setup-props-destructure
// const {
//     event_id,
//     author,
//     cover_image,
//     created_at,
//     end_date,
//     event_excerpt,
//     event_status,
//     event_title,
//     event_type,
//     start_date,
//     updated_at,
//     isLikedByUser,
//     isBookmarkedByUser,
//     guid
// } = props.eventData;

const router = useRouter();

const handleClickEventCard = () => {
    /**
     * id inside param must be the same as the route path specified for event-single
     * which is /event/resources/:id
     */
    router.push({
        name: "event-single",
        params: {id: props.eventData.event_id, slug: lowerSlugify(props.eventData.event_title)},
        state: {content: JSON.stringify(props.eventData)},
    })
}

const {currentUser} = storeToRefs(useUserStore())


</script>

<template>
    <GenericCard
        :id="eventData.event_id"
        :key="eventData.guid"
        :title="eventData.event_title"
        :display-content="eventData.event_excerpt"
        :display-author="eventData.author"
        :display-date="eventData.start_date"
        :end-date="eventData.end_date"
        :cover-image="eventData.cover_image"
        :click-callback="handleClickEventCard"
        :is-liked-by-user="eventData.isLikedByUser"
        :is-bookmarked-by-user="eventData.isBookmarkedByUser"
        section-type="event"
        :guid="eventData.guid"
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
                    v-if="eventData.event_type === 'In Person'"
                >
                    <InPerson />
                </template>
                <template
                    v-else-if="eventData.event_type === 'Virtual'"
                >
                    <Virtual />
                </template>
                <template
                    v-else-if="eventData.event_type === 'Hybrid'"
                >
                    <Hybrid />
                </template>
                {{ eventData.event_type }}
            </div>
        </template>
    </GenericCard>
</template>