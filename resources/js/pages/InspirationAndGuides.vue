<script setup>
import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import InspirationAndGuidesRobot from "@/js/components/inspirationandguides/InspirationAndGuidesRobot.vue";

const heroTitle = "Inspiration and guides"
const heroParagraph = "Discover software with the Department for Education tick of approval, and find out what is available to you to use as an employee and how to access it. Explore technical specifications, use cases, steps to purchase, and possible costs."
</script>

<template>
    <BaseLandingHero
        :title="heroTitle"
        :title-paragraph="heroParagraph"
    >
        <template #robotIllustration>
            <InspirationAndGuidesRobot class="absolute top-10 left-36" />
        </template>
    </BaseLandingHero>
    <div>
        hello
    </div>
</template>
