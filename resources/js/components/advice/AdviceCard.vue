<script setup lang="ts">
import {computed} from "vue";
import {useRouter} from "vue-router";

import AdviceCardIcon from "@/js/components/advice/AdviceCardIcon.vue";
import AdviceTypeTag from "@/js/components/advice/AdviceTypeTag.vue";
import GenericCard from "@/js/components/card/GenericCard.vue";
import {lowerSlugify} from "@/js/helpers/stringHelpers";
import {BasePostType} from "@/js/types/PostTypes";

const props = defineProps({
    data: {
        type: Object as () => BasePostType,
        required: true
    },
    showIcon: {
        type: Boolean, required: false
    },
});

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
        name: "guide-single",
        params: {id: props.data.id, slug: lowerSlugify(props.data.title)},
        state: {content: JSON.stringify(props.data)}
    })

}

</script>

<template>
    <GenericCard
        :id="data.id"
        :key="data.guid"
        :title="data.title"
        :display-content="data.excerpt"
        :display-author="data.author"
        :display-date="data.created_at"
        :cover-image="data.cover_image"
        :click-callback="handleClickAdviceCard"
        :section-type="'advice'"
        :is-liked-by-user="data.isLikedByUser"
        :is-bookmarked-by-user="data.isBookmarkedByUser"
        :guid="data.guid"
        :labels="data.labels"
    >
        <template
            v-if="data.type.length > 0"
            #typeTag
        >
            <AdviceTypeTag :type-tag="data.type" />
        </template>
        <template
            v-if="showIcon"
            #icon
        />
    </GenericCard>
</template>
