<script setup>
import GenericCard from '../card/GenericCard.vue';
import {useRouter} from 'vue-router';
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const props = defineProps({
    hardwareContent: {
        type: Object,
        required: true
    },

});

const router = useRouter();

const {
    id,
    product_name,
    cover_image,
    category,
    created_at,
    product_excerpt,
    product_content,
    author,
    isLikedByUser,
    isBookmarkedByUser
} = props.hardwareContent;

const handleClickHardwareCard = () => {
    router.push({
        name: 'hardware-single',
        params: {
            id: props.hardwareContent.id,
        },
        state:{
            content: JSON.stringify(props.hardwareContent)
        }
    })
}
</script>

<template>
    <GenericCard
        :id="id"
        :title="product_name"
        :display-content="product_excerpt"
        :display-author="author ? author['author_name'] : ''"
        :display-date="created_at"
        :cover-image="cover_image"
        :click-callback="handleClickHardwareCard"
        :is-liked-by-user="isLikedByUser"
        :is-bookmarked-by-user="isBookmarkedByUser"
        section-type="hardware"
    />
</template>
