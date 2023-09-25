<script setup lang="ts">
import {computed, ref} from "vue";

import Uploader from "@/js/components/uploader/Uploader.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {imageURL} from "@/js/constants/serverUrl";

export type MediaType = {
    url: string,
    remoteUrl: string,
    name: string,
    size: number,
    type: string
};


const props = defineProps({
    itemType: {
        type: String, required: true
    },
    max: {
        type: Number, required: false, default: 1
    },
    currentMedia: {
        type: String, required: false, default: ''
    }
})

const emits = defineEmits<{
    (e: 'emitUploadedMedia', mediaData: MediaType[])
}>()


const media = ref<MediaType[]>([])


const handleChangeMedia = (allMedia): void => {
    media.value = allMedia
    if (props.max === 1) {
        emits('emitUploadedMedia', [media.value[0]])
    } else {
        emits('emitUploadedMedia', media.value)

    }
}

const formatStringMediaToMediaType = computed((): MediaType[] => {
    if(props.currentMedia){
        const fullUrl = imageURL + "/" + props.currentMedia
        return [{
            url: fullUrl,
            remoteUrl: fullUrl,
            name: '',
            size: 0,
            type: ''
        }]
    } else{
        return []
    }
})
</script>

<template>
    <Uploader
        :server="API_ENDPOINTS.IMAGE.UPLOAD_IMAGE + '/' + props.itemType"
        :max="props.max"
        :media="formatStringMediaToMediaType"
        @change="handleChangeMedia"
    />
</template>
<style scoped>
:deep(.mu-container) {
    border: 1px solid #D4D4D8 !important;
}

:deep(.mu-elements-wraper) {
    justify-content: center;
    gap: 4rem;
}
</style>