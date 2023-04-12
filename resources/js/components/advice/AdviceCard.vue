<script setup>
import {computed} from "vue";
import GenericCard from "@/js/components/card/GenericCard.vue";
import AdviceTypeTag from "@/js/components/advice/AdviceTypeTag.vue";
import AdviceCardIcon from "@/js/components/advice/AdviceCardIcon.vue";
import {useRouter} from "vue-router";

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

const { post_id, post_title, cover_image, advice_type, created_at, post_excerpt, author } = props.adviceContent
const router = useRouter()


const randomIconName = computed(() => {
    const source = ['Book Light', 'Book Stars', 'Book Search']
    return source[Math.floor(Math.random() * source.length)]
})

const handleClickAdviceCard = () => {
    console.log('handleClickAdviceCardCalled')
    //id inside param must be the same as the route path specified for advice-single
    // which is /advice/resources/:id
    router.push({ name:"advice-single", params: {id: props.adviceContent.post_id, adviceContent: JSON.stringify(props.adviceContent) }})

}
</script>

<template>
    <GenericCard
        :title="post_title"
        :display-content="post_excerpt"
        :display-author="author"
        :display-date="created_at"
        :number-per-row="numberPerRow"
        :click-callback="handleClickAdviceCard"
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