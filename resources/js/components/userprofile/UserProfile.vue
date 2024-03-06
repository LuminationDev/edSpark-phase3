<script setup>

import {storeToRefs} from "pinia";
import {onMounted} from "vue";

import UserProfileSelectionMenu from "@/js/components/userprofile/userprofileupdate/UserProfileSelectionMenu.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useUserStore} from "@/js/stores/useUserStore";

const {currentUser} = storeToRefs(useUserStore())

onMounted(() =>{
    axios.get(API_ENDPOINTS.NOTIFICATION.GET_NOTIFICATIONS + currentUser.value.id ).then(res =>{
        console.log(res.data)
    } )
    // axios.get(API_ENDPOINTS.NOTIFICATION.READ_NOTIFICATION + '2cde425c-1239-42b9-84f6-b1ed1f842562' ).then(res =>{
    //     console.log(res.data)
    // } )
})
</script>
<template>
    <div class="UserProfilePage h-full w-full">
        <div
            class="bg-bottom bg-cover border-b-[1px] h-[12vh] profileCoverImage relative top-0 left-0 w-full"
        />
        <div class="flex flex-col profileBodyContainer w-full">
            <div class="bg-slate-50 flex flex-col min-h-[70vh] pb-10 px-4 md:!px-8 lg:!px-24">
                <div class="UserProfileSelectionMenuContainer flex flex-col mt-1">
                    <UserProfileSelectionMenu />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
