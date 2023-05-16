<script setup>
import GenericCard from "@/js/components/card/GenericCard.vue";
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)
const props = defineProps({
    hardware:{
        type: Object,
        required: true
    },
    numberPerRow:{
        type: Number,
        required: false,
        default: 3
    }
})

const handleClickHardwareCard = () => {
    console.log('clicked hardware card for now')
}

const likeBookmarkData = {
    post_id: props.hardware.id,
    user_id: currentUser.value.id, // to be replaced with userId from userStore
    post_type: 'hardware'
}

</script>
<template>
    <GenericCard
        :title="hardware['product_name']"
        :display-content="hardware['product_excerpt']"
        :display-author="hardware['brand']['brandName']"
        :cover-image="hardware['cover_image']"
        :number-per-row="props.numberPerRow"
        :click-callback="handleClickHardwareCard"
        :like-bookmark-data="likeBookmarkData"
    />
</template>