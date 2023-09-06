<script setup>
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {ref} from "vue";
import Uploader from 'vue-media-upload'
const props = defineProps({
    itemType: {
        type: String, required: true
    }
})

const media = ref([])


const handleChangeMedia = (media) =>{
    media.value = media
}
</script>

<template>
    <Uploader
        :server="API_ENDPOINTS.IMAGE.UPLOAD_IMAGE + '/' + props.itemType"
        max="1"
        @change="handleChangeMedia"
    />
</template>
<style scoped>
:deep(.mu-container){
    border: 1px solid #D4D4D8 !important;
}
:deep(.mu-elements-wraper){
    justify-content: center;
    gap: 4rem;
}
</style>