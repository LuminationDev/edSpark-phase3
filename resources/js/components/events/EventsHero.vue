<script setup>
import BasePageHero from "@/js/components/bases/BasePageHero.vue";


</script>

<template>
    <BasePageHero
        class="bg-gradient-to-r from-[#002858] via-[#0158AD] to-[#DE4668]/50"
        title="Events"
        subtitle="The department have partnered with trusted companies to provide you with the best digital tool at your fingertips. Find out more about our partners and access a range of curated resources."
    >
        <template #pageIconSVG>
            <img
                class="mx-auto absolute mt-1 ml-20"
                src="../../../assets/images/eventsCalendar.png"
                alt="Industry Partners connecting pieces of the puzzle"
            >
        </template>
    </BasePageHero>
</template>
