<script setup>
import GenericCard from "@/js/components/card/GenericCard.vue";
import SoftwareCardIcon from "@/js/components/software/SoftwareCardIcon.vue";
import {useRouter} from "vue-router";
import {likeURL, bookmarkURL} from "@/js/constants/serverUrl";
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";

const props = defineProps({
    software: {
        type: Object,
        required: true
    },

})

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
        params: {id: props.software.post_id, content: JSON.stringify(props.software)}
    })
}
</script>
<template>
    <GenericCard
        :id="software['id']"
        :title="software['post_title']"
        :display-content="software['post_content']"
        :display-author="software['author']"
        :display-date="software['post_modified']"
        :cover-image="software['cover_image']"
        :click-callback="handleClickCard"
        :section-type="'software'"
        :is-liked-by-user="software['isLikedByUser']"
        :is-bookmarked-by-user="software['isBookmarkedByUser']"
        class="mt-8"
    >
        <template #icon>
            <SoftwareCardIcon
                class="absolute -top-6 -right-6 icon"
                :software-icon-name="software['software_type'][0]"
            />
        </template>
    </GenericCard>
</template>