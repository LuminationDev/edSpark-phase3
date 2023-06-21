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
        class="longCardContainer bg-white rounded-lg flex flex-row w-full h-32 cursor-pointer "
        :style="`background-color : ${backgroundColor} !important`"
        @click="props.clickCallback"
    >
        <div class="flex basis-1/4 justify-center">
            <div class="longCardImagePreview flex justify-center items-center">
                <img
                    :src="`${imageURL}/${props.imagePreview}`"
                    :alt="props.imageAlt"
                    class="h-full"
                >
            </div>
        </div>
        <div class="flex flex-col basis-3/4">
            <div class="longCardTitle h-1/3 font-semibold text-lg overflow-hidden">
                {{ props.title }}
            </div>
            <div class="longCardContent h-1/4">
                {{ props.displayContent }}
            </div>
        </div>
    </div>
</template>
