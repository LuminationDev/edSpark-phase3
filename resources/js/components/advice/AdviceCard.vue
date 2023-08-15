<script setup>
import {computed} from "vue";
import GenericCard from "@/js/components/card/GenericCard.vue";
import AdviceTypeTag from "@/js/components/advice/AdviceTypeTag.vue";
import AdviceCardIcon from "@/js/components/advice/AdviceCardIcon.vue";
import {useRouter} from "vue-router";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({
    adviceData: {
        type: Object, required: true
    },
    showIcon: {
        type: Boolean, required: false
    },
    numberPerRow: {
        type: Number, required: false, default: 3
    }
});

// eslint-disable-next-line vue/no-setup-props-destructure
// const {
//     post_id,
//     post_title,
//     cover_image,
//     advice_type,
//     created_at,
//     post_excerpt,
//     author,
//     isLikedByUser,
//     isBookmarkedByUser,
//     guid
// } = props.adviceData

const router = useRouter()
const {currentUser} = storeToRefs(useUserStore())

const randomIconName = computed(() => {
    const source = ['iconBookLight', 'iconBookStars', 'iconBookSearch']
    return source[Math.floor(Math.random() * source.length)]
})

const handleClickAdviceCard = () => {
    /**
     * id inside param must be the same as the route path specified for advice-single
     * which is /advice/resources/:id
     */
    router.push({
        name: "advice-single",
        params: {id: props.adviceData.post_id},
        state: {content: JSON.stringify(props.adviceData)}
    })

}

</script>

<template>
    <GenericCard
        :id="adviceData.post_id"
        :key="adviceData.guid"
        :title="adviceData.post_title"
        :display-content="adviceData.post_excerpt"
        :display-author="adviceData.author"
        :display-date="adviceData.created_at"
        :number-per-row="numberPerRow"
        :cover-image="adviceData.cover_image"
        :click-callback="handleClickAdviceCard"
        :section-type="'advice'"
        :is-liked-by-user="adviceData.isLikedByUser"
        :is-bookmarked-by-user="adviceData.isBookmarkedByUser"
        :guid="adviceData.guid"
    >
        <template
            v-if="adviceData.advice_type.length > 0"
            #typeTag
        >
            <AdviceTypeTag :type-tag="adviceData.advice_type" />
        </template>
        <template
            v-if="showIcon"
            #icon
        >
            <AdviceCardIcon
                class="absolute right-4 bottom-2 group-hover:-bottom-32 icon transition-all"
                :advice-icon-name="randomIconName"
            />
        </template>
    </GenericCard>
</template>
