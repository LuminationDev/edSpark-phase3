<script setup>
import GenericCard from "@/js/components/card/GenericCard.vue";
import SoftwareCardIcon from "@/js/components/software/SoftwareCardIcon.vue";
import {useRouter} from "vue-router";
const props = defineProps({
    software: {
        type: Object,
        required: true
    },
    numberPerRow:{
        type: Number,
        required: false,
        default: 3
    }
})
const router = useRouter()
const handleClickCard = () => {
    /**
     * id inside param must be the same as the route path specified for advice-single
     */
    // which is /advice/resources/:id
    router.push({ name:"software-single", params: {id: props.software.post_id, content: JSON.stringify(props.software) }})

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
    >
        <template #icon>
            <SoftwareCardIcon
                class="icon absolute -top-6 -right-6 "
                :software-icon-name="software['software_type'][0]"
            />
        </template>
    </GenericCard>
</template>