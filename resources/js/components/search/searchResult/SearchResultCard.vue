<script setup>

import {storeToRefs} from "pinia";
import {computed} from "vue";
import {useRouter} from "vue-router";

import ImageWithFallback from "@/js/components/global/ImageWithFallback.vue";
import {imageURL} from "@/js/constants/serverUrl";
import {lowerSlugify} from "@/js/helpers/stringHelpers";
import {useWindowStore} from "@/js/stores/useWindowStore";

const props = defineProps({
    data: {
        type: Object,
        required: true
    }
})
const router = useRouter()
const windowStore = useWindowStore()
const {showGlobalSearch} = storeToRefs(windowStore)


const colorTheme = computed(() => {
    switch (props.data.type) {
    case"school":
        return 'bg-main-lightTeal/40'
    case"guide":
        return 'bg-secondary-green/40'
    case"software":
        return 'bg-secondary-grape/40'
    case"hardware":
        return 'bg-secondary-green/40'
    case"event":
        return 'bg-secondary-cherry/40'
    default:
        return 'bg-main-lightTeal/40'
    }
})

const hoverClass = computed(() => {
    return `!hover:${colorTheme.value}`
})

const handleClickSearchResultCard = () => {
    let componentName = ''
    switch (props.data.type) {
    case"guide":
        componentName = 'guide-single'
        break;
    case"software":
        componentName = 'software-single'
        break;
    case"hardware":
        componentName = 'hardware-single'
        break;
    case"event":
        componentName = 'event-single'
        break;
    default:
        return ''
    }
    if (componentName) {
        showGlobalSearch.value = false
        const targetId = props.data.id
        const slug = lowerSlugify(props.data.title)

        router.push({name: componentName, params: {id: targetId, slug: slug}})
    }
}


</script>

<template>
    <div
        class="cursor-pointer flex flex-row gap-2 h-24 my-6 px-4 py-2 searchResultCardContainer hover:bg-slate-50"
        :class="hoverClass"
        @click="handleClickSearchResultCard"
    >
        <div class="flex justify-center items-center rounded-2xl searchCardResultThumbnail w-1/6">
            <div class="bg-slate-100 border border-[1px] border-gray-300 h-16 overflow-hidden rounded-2xl w-16">
                <ImageWithFallback
                    :image-url="`${imageURL}/${props.data.cover_image}`"
                    class="h-full object-center object-cover w-full"
                    :image-alt="props.data.title + ' thumbnail photo'"
                    image-type="default"
                />
            </div>
        </div>

        <div class="flex justify-center flex-col searchCardResultContent w-5/6">
            <div class="capitalize contentTitle text-lg">
                {{ props.data.title }}
            </div>
            <div class="contentContent line-clamp-1 mb-2 text-xs">
                <div v-html="props.data.excerpt " />
            </div>
            <div
                class="contentTags flex items-start flex-row gap-1 overflow-hidden"
            >
                <span
                    class="capitalize mr-2 px-2 py-1 py-1 rounded-xl text-xs"
                    :class="colorTheme"
                >
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
