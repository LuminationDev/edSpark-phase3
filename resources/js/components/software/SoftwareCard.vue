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
    numberPerRow: {
        type: Number,
        required: false,
        default: 3
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

const likeBookmarkData = {
    post_id: props.software.post_id,
    user_id: currentUser.value.id,
    post_type: 'software'
}

</script>
<template>
    <GenericCard
        :title="software['post_title']"
        :display-content="software['post_content']"
        :display-author="software['post_author']"
        :cover-image="software['cover_image']"
        :number-per-row="props.numberPerRow"
        :click-callback="handleClickCard"
        :like-bookmark-data="likeBookmarkData"
        class="mt-8"
    >
        <template #icon>
            <SoftwareCardIcon
                class="icon absolute -top-6 -right-6 "
                :software-icon-name="software['software_type'][0]"
            />
        </template>
    </GenericCard>
</template>