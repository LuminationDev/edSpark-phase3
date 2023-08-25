<script setup>

import {imageURL} from "@/js/constants/serverUrl";
import {computed} from "vue";

const props = defineProps({
    data:{
        type: Object,
        required: true
    }
})

const colorTheme = computed(() =>{
    switch(props.data.type){
    case"school":
        return 'bg-main-lightTeal/40'
    case"advice":
        return 'bg-adviceGreen/40'
    case"software":
        return 'bg-secondary-blue/40'
    case"hardware":
        return 'bg-secondary-green/40'
    case"event":
        return 'bg-secondary-mbRose/40'
    default:
        return 'bg-main-lightTeal/40'
    }
})

const hoverClass = computed(() =>{
    return `!hover:${colorTheme.value}`
})

const handleClickSearchResultCard = () => {

}
</script>

<template>
    <div
        class="cursor-pointer flex flex-row gap-2 h-24 my-6 px-4 py-2 searchResultCardContainer hover:bg-slate-50"
        :class="hoverClass"
    >
        <div class="flex justify-center items-center rounded-2xl searchCardResultThumbnail w-1/6">
            <div class="bg-slate-100 border border-[1px] border-gray-300 h-16 overflow-hidden rounded-2xl w-16">
                <img
                    :src="`${imageURL}/${props.data.cover_image}`"
                    class="h-full object-center object-cover w-full"
                    :alt="props.data.title + ' thumbnail photo'"
                >
            </div>
        </div>

        <div class="flex justify-center flex-col searchCardResultContent w-5/6">
            <div class="capitalize contentTitle text-base">
                {{ props.data.title }}
            </div>
            <div class="contentContent line-clamp-1 text-xs">
                <div v-html="props.data.excerpt " />
            </div>
            <div
                class="contentTags flex flex-row gap-1 overflow-hidden"
            >
                <span class="capitalize mr-2 py-1 rounded-xl text-xs">
                    {{ props.data.type }}
                                        
                </span>
                <span
                    v-for="(tag,index) in props.data.tags"
                    :key="index"
                    class="mr-2 px-2 py-1 rounded-xl text-xs"
                    :class="colorTheme"
                > {{ `#${tag}` }} </span>
            </div>
        </div>
    </div>
</template>
