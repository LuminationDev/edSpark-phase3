<script setup lang="ts">

import {computed} from "vue";
import {useRoute, useRouter} from "vue-router";

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import BaseLandingSection from "@/js/components/bases/BaseLandingSection.vue";
import InspirationAndGuidesRobot from "@/js/components/inspirationandguides/InspirationAndGuidesRobot.vue";
import CreateSelector from "@/js/components/selector/CreateSelector.vue";
import ChevronLeftNavIcon from "@/js/components/svg/ChevronLeftNavIcon.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";

const router = useRouter()
const route = useRoute()


const handleClickBackToPosts = (): void => {
    router.push('/create')
}

const showBackToPosts = computed(() => {
    return route.name !== 'userPosts'

})
</script>

<template>
    <BaseLandingHero
        :title="LandingHeroText['creator']['title']"
        :title-paragraph="LandingHeroText['creator']['subtitle']"
    >
        <template #robotIllustration>
            <InspirationAndGuidesRobot class="absolute top-10 left-36" />
        </template>
    </BaseLandingHero>

    <BaseLandingSection>
        <template #title>
            <div class="flex justify-between items-start flex-col w-full">
                <div
                    v-if="showBackToPosts"
                    class="backToPosts flex items-center flex-row font-base text-sm hover:cursor-pointer hover:underline"
                    @click="handleClickBackToPosts"
                >
                    <ChevronLeftNavIcon class="fill-black h-5 p-1 stroke-black w-5" />Back to your posts
                </div>
                <div class="font-semibold text-4xl">
                    What would you like to create? <br>
                </div>
            </div>
        </template>
        <template #content>
            <CreateSelector />
        </template>
    </BaseLandingSection>
    <router-view />
</template>
