<script setup>

import {ref} from "vue";

import {imageURL} from "@/js/constants/serverUrl";

const props = defineProps({
    imageArray :{
        required: true,
        type : Array
    }
})
const mainImageUrl = ref(props.imageArray[0])

const handleClickThumbnail = (imageUrl) =>{
    mainImageUrl.value = imageUrl
}

const appendServerImageUrlIfMissing = (url) =>{
    if(!url.includes(imageURL)){
        return `${imageURL}/${url}`
    }else{
        return url
    }
}
</script>

<template>
    <div class="flex items-center flex-col imageDisplayLarge w-full">
        <img
            :src="appendServerImageUrlIfMissing(mainImageUrl)"
            alt="how to use image sample picture"
            class="h-96 object-contain w-96"
        >
        <div class="flex justify-center items-center flex-row gap-2 thumbnail-row">
            <div
                v-for="(image,index) in imageArray"
                :key="index"
                class="cursor-pointer flex justify-center items-center h-20 mt-6 thumbnailSmallImage w-20"
                @click="() =>handleClickThumbnail(image)"
            >
                <img
                    :src="appendServerImageUrlIfMissing(image)"
                    alt="how to use image sample picture"
                    class="h-full object-contain"
                >
            </div>
        </div>
    </div>
</template>
