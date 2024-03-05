<script setup>

import UserProfileSelectionMenu from "@/js/components/userprofile/userprofileupdate/UserProfileSelectionMenu.vue";

</script>
<template>
    <div class="UserProfilePage h-full w-full">
        <div
            class="bg-bottom bg-cover h-[19vh] profileCoverImage relative top-0 left-0 w-full"
        >
            <img
                src="@/assets/images/profile_background.jpeg"
                class="absolute -top-10 left-0 h-full object-cover scale-y-125 w-full"
                alt="static art consist of lines"
            >
        </div>
        <div class="flex flex-col profileBodyContainer w-full">
            <div class="bg-slate-50 flex flex-col min-h-[70vh] pb-10 px-4 md:!px-8 lg:!px-24">
                <div class="UserProfileSelectionMenuContainer flex flex-col">
                    <UserProfileSelectionMenu />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
