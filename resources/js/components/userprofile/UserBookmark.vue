<script setup>
import {ref, computed} from 'vue'
import GenericLongCard from "@/js/components/card/GenericLongCard.vue";
import {useRouter} from "vue-router";

const props = defineProps({
    bookmarkData:{
        type: Object,
        required: true
    }
})
const router = useRouter()

const handleClickBookmark = (postType, postId, postTitle) => {
    let targetUrl = ''
    switch(postType){
    case "school":
        targetUrl = '/schools/' + postTitle
        router.push(targetUrl)
        return;
        break;
    case "advice":
        targetUrl = '/advice/resources/'
        break;
    case "software":
        targetUrl = '/software/resources/'
        break;
    case "hardware":
        targetUrl = '/hardware/resources/'
        break;
    default:
        console.log('unknown post type')
        break;
    }
    router.push(targetUrl + postId)
}

</script>

<template>
    <div class="userBookmarkOuterContainer flex flex-col  ">
        <div class="bookmarksectiontitle flex px-4 h-16 bg-main-darkTeal rounded-t-xl items-center text-white text-lg uppercase font-semibold">
            Your Bookmarks
        </div>
        <div class="userBookmarkContentSection h-[30vh] bg-main-teal/80 px-10 pt-6 text-white overflow-scroll rounded-b-lg">
            <template
                v-for="(singleBookmark, index) in bookmarkData"
                :key="index"
            >
                <GenericLongCard
                    background-color="#FFFFFF"
                    :title="singleBookmark.post_title"
                    :display-content="singleBookmark.post_type"
                    :image-preview="singleBookmark.cover_image"
                    class="py-4 px-8 my-4  text-primary-teal"
                    :click-callback="() => handleClickBookmark(singleBookmark.post_type, singleBookmark.post_id,singleBookmark.post_title)"
                />
            </template>
        </div>
    </div>
</template>
