<script setup>
import {computed} from 'vue';
import GenericCard from '../card/GenericCard.vue';
import {useRouter} from 'vue-router';
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const props = defineProps({
    hardwareContent: {
        type: Object,
        required: true
    },
    numberPerRow: {
        type: Number,
        required: false,
        default: 3
    }
});

const router = useRouter();

const {
    id,
    product_name,
    cover_image,
    category,
    created_at,
    product_excerpt,
    product_content,
    author
} = props.hardwareContent;

const likeBookmarkData = {
    post_id: props.hardwareContent.id,
    user_id: currentUser.value.id,
    post_type: 'hardware'
};

const handleClickHardwareCard = () => {
    router.push({
        name: 'hardware-single',
        params: {
            id: props.hardwareContent.id,
        },
        state:{
            content: JSON.stringify(props.hardwareContent)
        }
    })
}
</script>

<template>
    <GenericCard
        :title="product_name"
        :display-content="product_excerpt"
        :display-author="author['author_name']"
        :display-date="created_at"
        :number-per-row="numberPerRow"
        :cover-image="cover_image"
        :click-callback="handleClickHardwareCard"
        :like-bookmark-data="likeBookmarkData"
    />
</template>
