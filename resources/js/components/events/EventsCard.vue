<script setup>
import GenericCard from '../card/GenericCard.vue';
import InPerson from '../svg/InPerson.vue';
import Virtual from '../svg/Virtual.vue';
import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({
    event: {
        type: Object,
        required: true
    },
    numberPerRow: {
        type: Number,
        required: false,
        default: 3
    }
})

const {currentUser } = storeToRefs(useUserStore())

const likeBookmarkData = {
    post_id: props.event.event_id,
    user_id: currentUser.value.id, // to be replaced with userId from userStore
    post_type: 'event'
}

</script>

<template>
    <GenericCard
        :title="event['event_title']"
        :display-content="event['event_content']"
        :display-author="event['event_author']"
        :cover-image="event['cover_image']"
        :number-per-row="props.numberPerRow"
        :like-bookmark-data="likeBookmarkData"
    >
        <template #icon>
            <div
                class="absolute rounded bg-[#DE4668] min-w-[136px] h-[39px] text-white flex flex-row justify-around gap-3 place-items-center -right-3 top-3 px-4"
            >
                <InPerson v-if="event.event_type === 'In Person'" />
                <Virtual v-else-if="event.event_type === 'Virtual'" />
                {{ event.event_type }}
            </div>
        </template>

        <!-- <template #icon>
            <SoftwareCardIcon
                class="icon absolute -top-6 -right-6 "
                :software-icon-name="event['event_type'][0]"
            />
        </template> -->
    </GenericCard>
</template>
