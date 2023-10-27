<script setup>
import {storeToRefs} from "pinia";
import {computed} from "vue";
import {useRouter} from "vue-router";

import AdviceCardIcon from "@/js/components/advice/AdviceCardIcon.vue";
import AdviceTypeTag from "@/js/components/advice/AdviceTypeTag.vue";
import GenericCard from "@/js/components/card/GenericCard.vue";
import {lowerSlugify} from "@/js/helpers/slugifyHelper";
import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({
    data: {
        type: Object, required: true
    },
    showIcon: {
        type: Boolean, required: false
    },
});

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
        >
            <AdviceCardIcon
                class="absolute right-4 bottom-2 group-hover:-bottom-32 icon transition-all"
                :advice-icon-name="randomIconName"
            />
        </template>
    </GenericCard>
</template>
