<script setup>
import {ref, computed} from 'vue'
import GenericCard from "@/js/components/card/GenericCard.vue";
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";
import {useRouter} from "vue-router";
const props = defineProps({
    partnerContent:{
        type: Object,
        required: true
    },
    numberPerRow:{
        type: Number,
        required: false,
        default: 3
    }
})
const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const router = useRouter()

const handleClickPartnerCard = () => {
    router.push({
        name: "partner-single",
        params: {id: props.partnerContent.user_id},
        state: {content: JSON.stringify(props.partnerContent)}
    })
}

</script>

<template>
    <GenericCard
        :id="props.partnerContent.id"
        :title="props.partnerContent.name"
        :item="props.partnerContent"
        :display-content="props.partnerContent.introduction"
        :cover-image="props.partnerContent.cover_image"
        :display-author="props.partnerContent.name"
        :click-callback="handleClickPartnerCard"
        :is-liked-by-user="props.partnerContent.isLikedByUser"
        :is-bookmarked-by-user="props.partnerContent.isBookmarkedByUser"
        section-type="partner"
    />
</template>