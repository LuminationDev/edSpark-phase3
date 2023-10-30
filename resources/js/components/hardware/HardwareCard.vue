<script setup>
import {storeToRefs} from "pinia";
import {useRouter} from 'vue-router';

import {lowerSlugify} from "@/js/helpers/slugifyHelper";
import {useUserStore} from "@/js/stores/useUserStore";

import GenericCard from '../card/GenericCard.vue';

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const props = defineProps({
    data: {
        type: Object,
        required: true
    },

});

const router = useRouter();

const handleClickHardwareCard = () => {
    router.push({
        name: 'hardware-single',
        params: {
            id: props.data.id,
            slug: lowerSlugify(props.data.title)
        },
        state:{
            content: JSON.stringify(props.data)
        }
    })
}
</script>

<template>
    <GenericCard
        :id="data.id"
        :key="data.guid"
        :title="data.title"
        :display-content="data.excerpt"
        :display-author="data.author ? data.author['author_name'] : ''"
        :display-date="data.modified_at"
        :cover-image="data.cover_image"
        :click-callback="handleClickHardwareCard"
        :is-liked-by-user="data.isLikedByUser"
        :is-bookmarked-by-user="data.isBookmarkedByUser"
        :guid="data.guid"
        :section-type="'hardware'"
    />
</template>
