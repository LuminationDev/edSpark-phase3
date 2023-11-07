<script setup lang="ts">

import {onMounted, ref} from "vue";

import UserDraftList from "@/js/components/create/UserDraftList.vue";
import {autoSaveService} from "@/js/service/autoSaveService";
import {useUserStore} from "@/js/stores/useUserStore";
import {BasePostType} from "@/js/types/PostTypes";

const userStore = useUserStore()
const draftArray = ref([])
const autoSaveLoading = ref(true)
const postLoading = ref(true)





onMounted(() => {
    autoSaveService.getAutoSave(userStore.currentUser.id, 'all').then(res => {
        draftArray.value = [...draftArray.value, ...formatDataFromAutoSaveAndPostToDraft(res.data.data)]
    }).finally(() => {
        autoSaveLoading.value = false
    })
    autoSaveService.getAllUserDraftPost(userStore.currentUser.id).then(res => {
        const result = res.data.data
        const flattenResult = Object.values(result).flat(1)
        draftArray.value = [...draftArray.value, ...formatDataFromAutoSaveAndPostToDraft(flattenResult)]
    }).finally(() => {
        postLoading.value = false
    })
})

const formatDataFromAutoSaveAndPostToDraft = (dataArray) : BasePostType[]  => {
    return dataArray.map(item => {
        if (item["post_status"] === 'Draft' || item["status"] === 'Draft') {
            return ({
                content: item.content,
                title: item.title,
                cover_image: item.cover_image,
                excerpt: item.excerpt,
                extra_content: item.extra_content,
                id: item.id,
                type: item.type,
                post_type: item.post_type,
                tags:item.tags,
                created_at: item.created_at,
                updated_at: item.modified_at,
                start_date: item.start_date,
                end_date: item.end_date,
                location: item.location,
            })
        } else if (item['status'] === 'auto-saved') {
            const content = JSON.parse(item.content)
            return ({
                content: content.content,
                title: content.title,
                cover_image: content.cover_image,
                excerpt: content.excerpt,
                extra_content: content.extra_content,
                id: item.post_id,
                type: content.type,
                post_type: item.post_type,
                tags: content.tags,
                created_at: item.created_at,
                updated_at: item.updated_at,
                start_date: content.start_date,
                end_date: content.end_date,
                location: content.location,
            })
        }
    })
}



</script>

<template>
    <div class="flex justify-center items-center flex-col mt-6 userPostsContainer">
        <div class="YourDraftsTitle font-semibold text-xl">
            Your drafts
        </div>
        <div class="YourDraftsTitle font-base mb-4 text-lg">
            Here you can find your incomplete posts
        </div>
        <UserDraftList
            :draft-array="draftArray"
            :draft-loading="autoSaveLoading || postLoading"
        />
        <div class="YourDraftsTitle font-semibold text-xl">
            Your recent posts
        </div>
        <div class="YourDraftsTitle font-base mb-4 text-lg">
            Here you can find your recently published posts
        </div>
    </div>
</template>

