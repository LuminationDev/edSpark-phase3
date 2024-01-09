<script setup>


import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import SchoolSearch from "@/js/components/search/SchoolSearch.vue";
import SchoolsRobot from "@/js/components/svg/schoolsRobot/schoolsRobot.vue";
import {LandingHeroText} from "@/js/constants/LandingPageTitle";


</script>
<template>
    <BaseLandingHero
        :title="LandingHeroText['school']['title']"
        :title-paragraph="LandingHeroText['school']['subtitle']"
        background-color="teal"
    >
        <template #robotIllustration>
            <SchoolsRobot
                class="absolute top-10 left-36"
            />
        </template>
    </BaseLandingHero>
    <SchoolSearch />
</template>
