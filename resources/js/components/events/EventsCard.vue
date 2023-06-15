<script setup>
import { computed } from "vue";
import GenericCard from "@/js/components/card/GenericCard.vue";

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

const likeBookmarkData = {
    post_id: props.eventContent.event_id,
    user_id: 2, // to be replaced with userId from userStore
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
<!--<script setup>-->
<!--import GenericCard from '../card/GenericCard.vue';-->
<!--import InPerson from '../svg/InPerson.vue';-->
<!--import Virtual from '../svg/Virtual.vue';-->

<!--const props = defineProps({-->
<!--    event: {-->
<!--        type: Object,-->
<!--        required: true-->
<!--    },-->
<!--    numberPerRow: {-->
<!--        type: Number,-->
<!--        required: false,-->
<!--        default: 3-->
<!--    }-->
<!--})-->

<!--console.log("EVENTS", props);-->

<!--const likeBookmarkData = {-->
<!--    post_id: props.event.post_id,-->
<!--    user_id: 2, // to be replaced with userId from userStore-->
<!--    post_type: 'event'-->
<!--}-->

<!--</script>-->

<!--<template>-->
<!--    <GenericCard-->
<!--        :title="event['event_title']"-->
<!--        :display-content="event['event_content']"-->
<!--        :display-author="event['event_author']"-->
<!--        :cover-image="event['cover_image']"-->
<!--        :number-per-row="props.numberPerRow"-->
<!--        :like-bookmark-data="likeBookmarkData"-->
<!--    >-->
<!--        <template #icon>-->
<!--            <div-->
<!--                class="absolute rounded bg-[#DE4668] min-w-[136px] h-[39px] text-white flex flex-row justify-around gap-3 place-items-center -right-3 top-3 px-4"-->
<!--            >-->
<!--                <InPerson v-if="event.event_type === 'In Person'" />-->
<!--                <Virtual v-else-if="event.event_type === 'Virtual'" />-->
<!--                {{ event.event_type }}-->
<!--            </div>-->
<!--        </template>-->

<!--        &lt;!&ndash; <template #icon>-->
<!--            <SoftwareCardIcon-->
<!--                class="icon absolute -top-6 -right-6 "-->
<!--                :software-icon-name="event['event_type'][0]"-->
<!--            />-->
<!--        </template> &ndash;&gt;-->
<!--    </GenericCard>-->
<!--</template>-->
