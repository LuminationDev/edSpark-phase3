<script setup>
import {computed} from "vue";
import GenericCard from "@/js/components/card/GenericCard.vue";
import AdviceTypeTag from "@/js/components/advice/AdviceTypeTag.vue";
import AdviceCardIcon from "@/js/components/advice/AdviceCardIcon.vue";

const props = defineProps({
    adviceContent:{
        type: Object, required: true
    },
    showIcon:{
        type: Boolean, required: false
    },
    numberPerRow:{
        type: Number, required: false, default: 3
    }
})

const { post_title, cover_image, advice_type, created_at, post_excerpt, author } = props.adviceContent

const randomIconName = computed(() => {
    const source = ['Book Light', 'Book Stars', 'Book Search']
    return source[Math.floor(Math.random() * source.length)]
})
</script>

<template>
    <GenericCard
        :title="post_title"
        :display-content="post_excerpt"
        :display-author="author"
        :display-date="created_at"
        :number-per-row="numberPerRow"
    >
        <template
            v-if="advice_type"
            #typeTag
        >
            <AdviceTypeTag :type-tag="advice_type" />
        </template>
        <template
            v-if="showIcon"
            #icon
        >
            <AdviceCardIcon :advice-icon-name="randomIconName" />
        </template>
    </GenericCard>
</template>