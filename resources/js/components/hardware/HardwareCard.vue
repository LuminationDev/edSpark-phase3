<script setup>
import GenericCard from '../card/GenericCard.vue';
import {useRouter} from 'vue-router';
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const props = defineProps({
    hardwareData: {
        type: Object,
        required: true
    },

});

const router = useRouter();

// eslint-disable-next-line vue/no-setup-props-destructure
// const {
//     id,
//     product_name,
//     cover_image,
//     category,
//     created_at,
//     product_excerpt,
//     product_content,
//     author,
//     isLikedByUser,
//     isBookmarkedByUser,
//     guid
// } = props.hardwareData;

const handleClickHardwareCard = () => {
    router.push({
        name: 'hardware-single',
        params: {
            id: props.hardwareData.id,
        },
        state:{
            content: JSON.stringify(props.hardwareData)
        }
    })
}
</script>

<template>
    <GenericCard
        :id="hardwareData.id"
        :key="hardwareData.guid"
        :title="hardwareData.product_name"
        :display-content="hardwareData.product_excerpt"
        :display-author="hardwareData.author ? hardwareData.author['author_name'] : ''"
        :display-date="hardwareData.created_at"
        :cover-image="hardwareData.cover_image"
        :click-callback="handleClickHardwareCard"
        :is-liked-by-user="hardwareData.isLikedByUser"
        :is-bookmarked-by-user="hardwareData.isBookmarkedByUser"
        :guid="hardwareData.guid"
        section-type="hardware"
    />
</template>
