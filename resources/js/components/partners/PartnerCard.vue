<script setup>
import {storeToRefs} from "pinia";
import {computed,ref} from 'vue'
import {useRouter} from "vue-router";

import GenericCard from "@/js/components/card/GenericCard.vue";
import {lowerSlugify} from "@/js/helpers/slugifyHelper";
import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
})

// eslint-disable-next-line vue/no-setup-props-destructure
// const {id, name, user_id, introduction, cover_image, isLikedByUser, isBookmarkedByUser, guid} = props.data


const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const router = useRouter()

const handleClickPartnerCard = () => {
    router.push({
        name: "partner-single",
        params: {id: props.data.user_id, slug: lowerSlugify(props.data.name)},
        state: {content: JSON.stringify(props.data)}
    })
}

</script>

<template>
    <GenericCard
        :id="data.id"
        :key="data.guid"
        :title="data.name"
        :item="data"
        :display-content="data.introduction"
        :cover-image="data.cover_image"
        :display-author="data.name"
        :click-callback="handleClickPartnerCard"
        :is-liked-by-user="data.isLikedByUser"
        :is-bookmarked-by-user="data.isBookmarkedByUser"
        :guid="data.guid"
        section-type="partner"
    />
</template>