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
const tempPartnerContent = 'This is some content for the partner card. we are using temporary content because the data is not available just yet'

const handleClickPartnerCard = () => {
    router.push({
        name: "partner-single",
        params: {id: props.partnerContent.id},
        state: {content: JSON.stringify(props.partnerContent)}
    })

}

const likeBookmarkData = {
    post_id: props.partnerContent.post_id,
    user_id: currentUser.value.id || 9999,
    post_type: 'partner'
}
</script>

<template>
    <GenericCard
        :key="props.partnerContent.id"
        :title="props.partnerContent.name"
        :item="props.partnerContent"
        :display-content="tempPartnerContent"
        :display-author="props.partnerContent.name"
        :number-per-row="4"
        :like-bookmark-data="likeBookmarkData"
        :click-callback="handleClickPartnerCard"
    />
</template>