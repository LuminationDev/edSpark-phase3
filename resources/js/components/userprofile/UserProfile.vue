<script setup>

import UserProfileSelectionMenu from "@/js/components/userprofile/userprofileupdate/UserProfileSelectionMenu.vue";

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
