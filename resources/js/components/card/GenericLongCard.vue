<script setup>
import {ref, computed} from 'vue'
import {imageURL, serverURL} from "@/js/constants/serverUrl";

const props = defineProps({
    backgroundColor:{
        type: String,
        required: false,
        default: "#339999"
    },
    title:{
        type: String,
        required: true
    },
    displayContent:{
        type: String,
        required: true
    },
    imagePreview:{
        type: String,
        required: false,
        default: ''
    },
    imageAlt :{
        type: String, required: false,
        default: 'Image Alternate tag'
    },
    clickCallback:{
        type: Function,
        required: false,
        default: () => {}
    }
})
/**
 * Card Data : {
 *     post_id: Number
 *     post_type: String
 *     post_title: String
 *     cover_image:  String (URL)
 * }
 *
 */
</script>

<template>
    <div
        class="bg-white cursor-pointer flex flex-row gap-4 md:gap-6 h-32 longCardContainer rounded-lg w-full"
        :style="`background-color : ${backgroundColor} !important`"
        @click="props.clickCallback"
    >
        <div class="basis-1/4 flex justify-center">
            <div class="flex justify-center items-center longCardImagePreview">
                <img
                    :src="`${imageURL}/${props.imagePreview}`"
                    :alt="props.imageAlt"
                    class="h-full object-cover w-full"
                >
            </div>
        </div>
        <div class="basis-3/4 flex flex-col">
            <div class="font-semibold h-1/3 longCardTitle overflow-hidden text-sm md:!text-lg">
                {{ props.title }}
            </div>
            <div class="capitalize h-1/4 longCardContent text-sm md:!text-base">
                {{ props.displayContent }}
            </div>
        </div>
    </div>
</template>
