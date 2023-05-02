<script setup>
    import GenericCard from '../card/GenericCard.vue';
    import InPerson from '../svg/InPerson.vue';
    import Virtual from '../svg/Virtual.vue';

    const props = defineProps({
        event: {
            type: Object,
            required: true
        },
        numberPerRow:{
            type: Number,
            required: false,
            default: 3
        }
    })

    const likeBookmarkData = {
        post_id: props.event.post_id,
        user_id: 2, // to be replaced with userId from userStore
        post_type: 'event'
    }

    console.log(props.event);

</script>

<template>
    <GenericCard
        :title="event['event_title']"
        :display-content="event['event_content']"
        :display-author="event['event_author']"
        :cover-image="event['cover_image']"
        :number-per-row="props.numberPerRow"
        :click-callback="handleClickCard"
        :like-bookmark-data="likeBookmarkData"
    >
        <template #icon>
            <div class="absolute rounded bg-[#DE4668] min-w-[136px] h-[39px] text-white flex flex-row justify-around gap-3 place-items-center -right-3 top-3 px-4">
                <InPerson v-if="event.event_type === 'In Person'" />
                <Virtual v-if="event.event_type === 'Virtual'" />
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
