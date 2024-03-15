<script setup>
import {storeToRefs} from "pinia";
import {onMounted, ref} from 'vue'
import {useRouter} from "vue-router";

import Loader from "@/js/components/spinner/Loader.vue";
import Trash from "@/js/components/svg/Trash.vue";
import ListingDesignItemSmall
    from "@/js/components/userprofile/userprofileupdate/usernotification/ListingDesignItemSmall.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useUserStore} from '@/js/stores/useUserStore';

const isLoading = ref(true);
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
        // return;
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
    if (targetUrl !== '') {
        router.push(targetUrl + postId);
    }
}



const fetchBookmarksWithTitle = () => {
    const userId = currentUser.value.id;
    axios.post(API_ENDPOINTS.BOOKMARK.FETCH_ALL_BOOKMARKS_WITH_TITLE, { user_id: userId })
        .then(response => {
            console.log(currentUser.value.id)
            console.log(userBookmarks)
            userBookmarks.value = response.data.data;
            count.value = response.data.count;
            isLoading.value = false
        })
        .catch(error => {
            console.error('Error fetching bookmarks:', error);
        });
}

const deleteBookmark = (index) => {
    const bookmarkToDelete = userBookmarks.value[index]
    axios.post(API_ENDPOINTS.BOOKMARK.BOOKMARK, { post_id: bookmarkToDelete.post_id, post_type: bookmarkToDelete.post_type, user_id: currentUser.value.id })
        .then(() => {
            userBookmarks.value.splice(index, 1); // Remove the deleted bookmark from the list
            count.value--; // Decrease the count
        })
        .catch(error => {
            console.error('Error deleting bookmark:', error);
        });
}

onMounted(() => {
    fetchBookmarksWithTitle();
});

</script>

<template>
    <div>
        <div
            v-if="isLoading"
            class="loader"
        >
            <Loader
                loader-type="small"
                loader-message="Fetching your bookmarks"
            />
        </div>

        <div
            v-for="(singleBookmark, index) in userBookmarks"
            :key="index"
            class="flex flex-row mr-4"
        >
            <ListingDesignItemSmall
                :time-date="singleBookmark.post_id"
                :display-heading="singleBookmark.post_title"
                :category-text="singleBookmark.post_type"
                :click-callback="() => handleClickBookmark(singleBookmark.post_type, singleBookmark.post_id,singleBookmark.post_title)"
            />

            <Trash

                class="cursor-pointer m-auto hover:text-red-700"
                @click="deleteBookmark(index)"
            />
        </div>
    </div>
</template>
