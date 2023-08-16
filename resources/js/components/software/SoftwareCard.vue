<script setup>
import GenericCard from "@/js/components/card/GenericCard.vue";
import SoftwareCardIcon from "@/js/components/software/SoftwareCardIcon.vue";
import {useRouter} from "vue-router";
import {likeURL, bookmarkURL} from "@/js/constants/serverUrl";
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";

const props = defineProps({
    softwareData: {
        type: Object,
        required: true
    },

})

// eslint-disable-next-line vue/no-setup-props-destructure
// const {
//     post_id,
//     post_title,
//     post_content,
//     author,
//     post_modified,
//     cover_image,
//     isLikedByUser,
//     isBookmarkedByUser,
//     software_type,
//     guid
// } = props.softwareData

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)
const router = useRouter()

const handleClickCard = () => {
    /**
     * id inside param must be the same as the route path specified for advice-single
     * which is /software/resources/:id
     */
    router.push({
        name: "software-single",
        params: {id: props.softwareData.post_id, content: JSON.stringify(props.softwareData)}
    })
}
</script>
<template>
    <GenericCard
        :id="softwareData.post_id"
        :key="softwareData.guid"
        :title="softwareData.post_title"
        :display-content="softwareData.post_content"
        :display-author="softwareData.author"
        :display-date="softwareData.post_modified"
        :cover-image="softwareData.cover_image"
        :click-callback="handleClickCard"
        :section-type="'software'"
        :is-liked-by-user="softwareData.isLikedByUser"
        :is-bookmarked-by-user="softwareData.isBookmarkedByUser"
        class="mt-8"
        :guid="softwareData.guid"
    >
        <template #icon>
            <SoftwareCardIcon
                class="absolute -top-6 -right-6 icon"
                :software-icon-name="softwareData.software_type[0]"
            />
        </template>
    </GenericCard>
</template>