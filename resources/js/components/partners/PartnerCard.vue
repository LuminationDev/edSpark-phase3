<script setup>
import {ref, computed} from 'vue'
import GenericCard from "@/js/components/card/GenericCard.vue";
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";
import {useRouter} from "vue-router";

const props = defineProps({
    partnerData: {
        type: Object,
        required: true
    },
})

// eslint-disable-next-line vue/no-setup-props-destructure
// const {id, name, user_id, introduction, cover_image, isLikedByUser, isBookmarkedByUser, guid} = props.partnerData


const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const router = useRouter()

const handleClickPartnerCard = () => {
    router.push({
        name: "partner-single",
        params: {id: props.partnerData.user_id},
        state: {content: JSON.stringify(props.partnerData)}
    })
}

</script>

<template>
    <GenericCard
        :id="partnerData.id"
        :key="partnerData.guid"
        :title="partnerData.name"
        :item="partnerData"
        :display-content="partnerData.introduction"
        :cover-image="partnerData.cover_image"
        :display-author="partnerData.name"
        :click-callback="handleClickPartnerCard"
        :is-liked-by-user="partnerData.isLikedByUser"
        :is-bookmarked-by-user="partnerData.isBookmarkedByUser"
        :guid="partnerData.guid"
        section-type="partner"
    />
</template>