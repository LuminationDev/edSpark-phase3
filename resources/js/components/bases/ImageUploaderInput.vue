<script setup lang="ts">
import {computed, ref} from "vue";

import ErrorMessages from "@/js/components/bases/ErrorMessages.vue";
import Uploader from "@/js/components/uploader/Uploader.vue";
import {IMAGE_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {imageURL} from "@/js/constants/serverUrl";
import {guid} from "@/js/helpers/guidGenerator";

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
        type: [String,Array],
        required:
            false,
        default: ''
    },
    v$: {
        type: Object,
        required: false,
        default: () => {
        }
    },
    index:{
        type: Number,
        required: false,
        default: 0
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

const appendServerImageUrlIfMissing = (url) =>{
    if(!url.includes(imageURL)){
        return `${imageURL}/${url}`
    }else{
        return url
    }
}

const formatStringMediaToMediaType = computed((): MediaType[] => {
    if (props.currentMedia && props.currentMedia.length == 1) {
        const fullUrl = appendServerImageUrlIfMissing(props.currentMedia)
        return [{
            url: fullUrl,
            remoteUrl: fullUrl,
            name: guid(),
            size: 0,
            type: ''
        }]
    } else if(Array.isArray(props.currentMedia) && props.currentMedia.length > 1){
        return props.currentMedia.map(image =>{
            const fullUrl = appendServerImageUrlIfMissing(image)
            return {
                url: fullUrl,
                remoteUrl: fullUrl,
                name: guid(),
                size: 0,
                type: ''
            }
        })
    }

    else {
        return []
    }
})
</script>

<template>
    <Uploader
        :server="IMAGE_ENDPOINTS.IMAGE.UPLOAD_IMAGE + '/' + props.itemType"
        :max="props.max"
        :media="formatStringMediaToMediaType"
        :index="index"
        @change="handleChangeMedia"
    />
    <ErrorMessages
        v-if="props.v$"
        :v$="props.v$"
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