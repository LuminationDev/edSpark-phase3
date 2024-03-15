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


        <div
            class="UserProfileSelectionMenuContainer
                flex
                flex-col
                min-h-[70vh]
                mt-5
                pb-5
                profileBodyContainer
                px-4
                w-full
                
                
                lg:!mt-10
                lg:!px-10"
        >
            <UserProfileSelectionMenu />
        </div>
    </div>
</template>

<style scoped>

</style>
