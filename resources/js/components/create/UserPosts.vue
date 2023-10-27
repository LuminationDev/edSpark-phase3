<script setup>

import {onMounted, reactive, ref} from "vue";

import UserDraftList from "@/js/components/create/UserDraftList.vue";
import {autoSaveService} from "@/js/service/autoSaveService";
import {useUserStore} from "@/js/stores/useUserStore";

const userStore = useUserStore()
const draftsArray = ref([])

onMounted(() => {
    autoSaveService.getAutoSave(userStore.currentUser.id, 'all').then(res => {
        console.log(res.data.data)

        draftsArray.value = res.data.data.map(item => {
            return {
                ...item,
                content: JSON.parse(item.content)
            }
        })
    })
    autoSaveService.getAllUserDraftPost(userStore.currentUser.id).then(res => {
        console.log(res.data.data)
    })

})
</script>

<template>
    <div class="flex justify-center items-center flex-col mt-6 userPostsContainer">
        <div class="YourDraftsTitle font-semibold text-xl">
            Your drafts
        </div>
        <div class="YourDraftsTitle font-base mb-4 text-lg">
            Here you can find your incomplete posts
        </div>
        <UserDraftList :drafts-array="draftsArray" />
        <div class="YourDraftsTitle font-semibold text-xl">
            Your recent posts
        </div>
        <div class="YourDraftsTitle font-base mb-4 text-lg">
            Here you can find your recently published posts
        </div>
    </div>
</template>

