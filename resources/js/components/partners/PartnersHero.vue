<script setup>
import BasePageHero from "@/js/components/bases/BasePageHero.vue";
</script>

<template>
    <BasePageHero
        class="bg-gradient-to-r from-[#002858] via-[#0072DA] to-[#44B8F3]/50 mb-[50px]"
        title="Partners"
        subtitle="The department have partnered with trusted companies to provide you with the best digital tool at your fingertips. Find out more about our partners and access a range of curated resources."
    >
        <template #pageIconSVG>
            <img
                class="mx-auto absolute ml-20 md:!mt-auto md:ml-16 md:scale-75 xl:scale-100"
                src="../../../assets/images/PartnersPuzzle.png"
                alt="Industry Partners connecting pieces of the puzzle"
            >
        </template>
    </BasePageHero>
</template>



