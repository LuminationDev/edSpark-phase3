<script setup>


import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import InspirationAndGuidesRobot from "@/js/components/inspirationandguides/InspirationAndGuidesRobot.vue";
import UserProfileSelectionMenu from "@/js/components/userprofile/userprofileupdate/UserProfileSelectionMenu.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";


</script>
<template>
    <div class="UserProfilePage h-full w-full">
        <BaseLandingHero
            :title="LandingHeroText['profile']['title']"
            :title-paragraph="LandingHeroText['profile']['subtitle']"
            swoosh-color="teal"
        >
            <template #robotIllustration>
                <InspirationAndGuidesRobot class="absolute top-16 left-36" />
            </template>
        </BaseLandingHero>


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
