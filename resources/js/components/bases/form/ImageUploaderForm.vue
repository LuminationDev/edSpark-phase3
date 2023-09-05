<script setup>
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {ref} from "vue";
import Uploader from 'vue-media-upload'

const media = ref([])


const handleChangeMedia = (media) =>{
    media.value = media
}
</script>

<template>
    <Uploader
        :server="API_ENDPOINTS.IMAGE.UPLOAD_IMAGE + '/advice' "
        max="1"
        @change="handleChangeMedia"
    />
</template>