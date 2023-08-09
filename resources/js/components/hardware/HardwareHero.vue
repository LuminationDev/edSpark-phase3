<script setup>
import BaseHero from "@/js/components/bases/BaseHero.vue";
import HardwareHeroRobot from "@/js/components/svg/hardware/HardwareHeroRobot.vue";
import BasePageHero from "@/js/components/bases/BasePageHero.vue";
</script>
<template>
    <BasePageHero
        class="!from-[#002858] !to-[#A5D1BD] !via-[#0A7982] bg-gradient-to-r"
        title="Hardware"
        subtitle="Explore what options are available for schools with locked prices on hardware selected to be purpose fit for school use"
    >
        <template #subtitle2>
            Use this page to explore software which has the Department for Education tick of approval.
            <br>
            Find out more information,
            see use cases, steps to purchase and possible costs.
        </template>
        <template #pageIconSVG>
            <hardware-hero-robot class="absolute -top-16 right-12" />
        </template>
    </BasePageHero>
</template>