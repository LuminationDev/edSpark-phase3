<script setup>
import {computed} from "vue";
import GenericCard from "@/js/components/card/GenericCard.vue";
import AdviceTypeTag from "@/js/components/advice/AdviceTypeTag.vue";
import AdviceCardIcon from "@/js/components/advice/AdviceCardIcon.vue";
import {useRouter} from "vue-router";

const props = defineProps({
    adviceContent: {
        type: Object, required: true
    },
    showIcon: {
        type: Boolean, required: false
    },
    numberPerRow: {
        type: Number, required: false, default: 3
    }
});

const {post_id, post_title, cover_image, advice_type, created_at, post_excerpt, author} = props.adviceContent
const router = useRouter()


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
        params: {id: props.adviceContent.post_id},
        state: {content: JSON.stringify(props.adviceContent)}
    })

}

const likeBookmarkData = {
    post_id: props.adviceContent.post_id,
    user_id: 2,
    post_type: 'advice'
}

</script>

<template>
    <GenericCard
        :key="post_id"
        :title="post_title"
        :display-content="post_excerpt"
        :display-author="author"
        :display-date="created_at"
        :number-per-row="numberPerRow"
        :cover-image="cover_image"
        :click-callback="handleClickAdviceCard"
        :like-bookmark-data="likeBookmarkData"
    >
        <template
            v-if="advice_type.length > 0"
            #typeTag
        >
            <AdviceTypeTag :type-tag="advice_type" />
        </template>
        <template
            v-if="showIcon"
            #icon
        >
            <AdviceCardIcon
                class="icon absolute right-2 bottom-2 group-hover:-bottom-28"
                :advice-icon-name="randomIconName"
            />
        </template>
    </GenericCard>
</template>
