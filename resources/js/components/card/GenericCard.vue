<script setup>
import {computed, ref} from "vue";
import {bookmarkURL, imageURL, likeURL} from "@/js/constants/serverUrl";
import Tooltip from "@/js/components/global/Tooltip/Tooltip.vue";

import Like from "@/js/components/svg/Like.vue";
import LikeFull from "@/js/components/svg/LikeFull.vue";
import BookMark from "@/js/components/svg/BookMark.vue";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";
import BookmarkFull from "@/js/components/svg/BookmarkFull.vue";
const {userLikeList, userBookmarkList} = storeToRefs(useUserStore())

const props = defineProps({
    title:{
        type: String,
        required: true
    },
    numberPerRow: {
        type: Number,
        required: false,
        default: 3
    },
    displayAuthor:{
        type: String, required: true
    },
    displayDate:{
        type: String, required: false
    },
    displayContent:{
        type: String, required: true
    },
    coverImage:{
        type: String, required: false
    },
    clickCallback:{
        type: Function,
        required: false,
        default: () => {}
    },
    likeBookmarkData:{
        type: Object,
        required: false
    }
})

const currentUserLiked = computed(  () => {
    if(userLikeList.value[props.likeBookmarkData.post_type] &&
        userLikeList.value[props.likeBookmarkData.post_type].length > 0 &&
        userLikeList.value[props.likeBookmarkData.post_type].filter(eachItem => {
            if( eachItem == props.likeBookmarkData.post_id) return true
        }).length > 0)
    {
        return true
    } else {
        return false
    }
})

const currentUserBookmarked = computed(() => {
    if(userBookmarkList.value[props.likeBookmarkData.post_type] &&
        userBookmarkList.value[props.likeBookmarkData.post_type].length > 0 &&
        userBookmarkList.value[props.likeBookmarkData.post_type].filter(eachItem => {
            if( eachItem == props.likeBookmarkData.post_id) return true
        }).length > 0)
    {
        return true
    } else {
        return false
    }
})

const formattedDate = computed(() =>{
    const date = new Date(Date.parse(props.displayDate))
    return date.toDateString()
})

const handleDefaultLike = async (data) => {
    const {post_type} = props.likeBookmarkData
    await axios.post(likeURL, data )
        .then(res => {
            if(res.data.isLiked){
                userLikeList.value[post_type].push(data.post_id)
            } else{
                const indexRemoval = userLikeList.value[post_type].indexOf(data.post_id)
                if(indexRemoval !== -1){
                    userLikeList.value[post_type].splice(indexRemoval, 1)
                } else{
                    console.log('tried to unlike something that doesnt exist in the database')
                }
            }
        })
        .catch(err => {
            console.log(err);
            console.error(err.code)
        })
}

const handleDefaultBookmark = async (data) => {
    const {post_type} = props.likeBookmarkData
    await axios.post(bookmarkURL, data )
        .then(res => {
            if(res.data.isBookmarked){
                userBookmarkList.value[post_type].push(data.post_id)
            } else{
                const indexRemoval = userBookmarkList.value[post_type].indexOf(data.post_id)
                if(indexRemoval !== -1){
                    userBookmarkList.value[post_type].splice(indexRemoval, 1)
                } else{
                    console.log('tried to unbookmark something that doesnt exist in the database')
                }
            }
        })
        .catch(err => {
            console.log(err)
        })
}


const cardHoverToggle = ref(false)

</script>

<template>
    <div
        :class="{'!w-[30%]': numberPerRow === 3,
                 '!w-[22%]': numberPerRow === 4,
                 '!w-[45%]': numberPerRow === 2,
                 '!w-[95%]' : numberPerRow === 1
        }"
        class="GenericCardContainer w-full border-[0.5px] border-black hover:shadow-2xl mx-2 mb-4 flex flex-col min-h-[480px] max-w-[400px] max-h-[480px] group transition-all card_parent cursor-pointer"
        @mouseenter="cardHoverToggle = true"
    >
        <div
            class="cardTopCoverImage relative min-h-[35%] bg-cover bg-center group-hover:min-h-[0%] group-hover:h-0 transition-all"
            :class="`bg-[url('${imageURL}/${coverImage}')]`"
            @click="clickCallback"
        >
            <template
                v-if="$slots.typeTag"
            >
                <slot
                    name="typeTag"
                />
            </template>

            <div
                v-if="$slots.icon"
            >
                <slot name="icon" />
            </div>
        </div>
        <div
            class="cardContent h-full flex flex-col p-4 overflow-hidden transition-all"
            @click="clickCallback"
        >
            <div
                v-if="props.title"
                class="cardTitle text-xl font-bold uppercase transition-all mb-4 group-hover:w-3/4"
            >
                {{ props.title }}
            </div>
            <div class="flex flex-col card-content_body h-full">
                <div
                    v-if="props.displayAuthor"
                    class="cardAuthor text-base font-semibold mt-2 transition-all"
                >
                    {{ props.displayAuthor }}
                </div>
                <div
                    v-if="props.displayDate"
                    class="cardDate text-base  mb-2 transition-all"
                >
                    {{ formattedDate }}
                </div>
                <div
                    v-if="props.displayContent"
                    class="cardDisplayPreview pt-2 h-full font-light text-lg overflow-hidden mt-auto pb-6 transition-all"
                    v-html="props.displayContent"
                />
            </div>
        </div>
        <div class="flex flex-row h-18 pl-4 gap-4 mt-auto">
            <div class="p-2">
                <span class="has-tooltip">
                    <LikeFull
                        v-if="currentUserLiked"
                        @click="() => handleDefaultLike(props.likeBookmarkData)"
                    />
                    <Like
                        v-else
                        @click="() => handleDefaultLike(props.likeBookmarkData)"
                    />
                    <Tooltip
                        tip="like this post"
                        :tool-tip-margin="{'!-ml-28' : numberPerRow > 3, '-ml-36' : true}"
                    />
                </span>
            </div>
            <div class="p-2">
                <span class="has-tooltip">
                    <BookmarkFull
                        v-if="currentUserBookmarked"
                        @click="() => handleDefaultBookmark(props.likeBookmarkData)"
                    />
                    <BookMark
                        v-else
                        @click="() => handleDefaultBookmark(props.likeBookmarkData)"
                    />
                    <Tooltip
                        tip="bookmark this post"
                        tool-tip-margin="ml-8"
                    />
                </span>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>
