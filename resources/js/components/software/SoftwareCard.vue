<script setup>
import {storeToRefs} from "pinia";
import {useRouter} from "vue-router";

import GenericCard from "@/js/components/card/GenericCard.vue";
import SoftwareCardIcon from "@/js/components/software/SoftwareCardIcon.vue";
import {bookmarkURL,likeURL} from "@/js/constants/serverUrl";
import {lowerSlugify} from "@/js/helpers/slugifyHelper";
import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    showIcon:{
        type: Boolean,
        required: false,
        default: true
    }

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
        params: {id: props.data.id, slug : lowerSlugify(props.data.title) },
        state:{
            content: JSON.stringify(props.data)
        }
    })
}
</script>
<template>
    <GenericCard
        :id="data.id"
        :key="data.guid"
        :title="data.title"
        :display-content="data.content"
        :display-author="data.author"
        :display-date="data.modified_at"
        :cover-image="data.cover_image"
        :click-callback="handleClickCard"
        :section-type="'software'"
        :is-liked-by-user="data.isLikedByUser"
        :is-bookmarked-by-user="data.isBookmarkedByUser"
        :guid="data.guid"
    >
        <template
            v-if="showIcon"
            #icon
        >
            <SoftwareCardIcon
                class="-top-6 -right-6 icon pr-4"
                :software-icon-name="data.type[0]"
            />
        </template>
    </GenericCard>
</template>