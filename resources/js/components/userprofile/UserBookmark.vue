<script setup>
import {storeToRefs} from "pinia";
import {onMounted, ref} from 'vue'
import {useRouter} from "vue-router";

import GenericLongCard from "@/js/components/card/GenericLongCard.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useUserStore} from '@/js/stores/useUserStore';

const userBookmarks = ref([])
const count = ref(0)
const router = useRouter()
const userStore = useUserStore();
const {currentUser} = storeToRefs(userStore)

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

const fetchBookmarksWithTitle = () => {
    const userId = currentUser.value.id; // Hardcoded user ID
    axios.post(API_ENDPOINTS.BOOKMARK.FETCH_ALL_BOOKMARKS_WITH_TITLE, { user_id: userId })
        .then(response => {
            console.log(currentUser.value.id)
            console.log(userBookmarks)
            userBookmarks.value = response.data.data;
            count.value = response.data.count;
        })
        .catch(error => {
            console.error('Error fetching bookmarks:', error);
        });
}

onMounted(() => {
    fetchBookmarksWithTitle();
});

</script>

<template>
    <div class="flex flex-col userBookmarkOuterContainer">
        <div class="bg-main-darkTeal bookmarksectiontitle flex items-center font-semibold h-16 px-4 rounded-t-xl text-lg text-white">
            Your Bookmarks
        </div>
        <div class="bg-main-teal/80 h-72 overflow-scroll pt-4 px-4 rounded-b-lg text-white userBookmarkContentSection md:!pt-6 md:!px-10">
            <template
                v-for="(singleBookmark, index) in userBookmarks"
                :key="index"
            >
                <GenericLongCard
                    background-color="#FFFFFF"
                    :title="singleBookmark.post_title"
                    :display-content="singleBookmark.post_type"
                    :image-preview="singleBookmark.cover_image"
                    class="my-2 px-4 py-4 text-main-teal md:!my-4 md:!px-8"
                    :click-callback="() => handleClickBookmark(singleBookmark.post_type, singleBookmark.post_id,singleBookmark.post_title)"
                />
            </template>
        </div>
    </div>
</template>
