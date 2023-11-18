<script setup>
import BaseHero from "@/js/components/bases/BaseHero.vue";
import HardwareHeroRobot from "@/js/components/svg/hardware/HardwareHeroRobot.vue";
import BasePageHero from "@/js/components/bases/BasePageHero.vue";
</script>
<template>
    <BasePageHero
        class="!from-[#002858] !to-[#A5D1BD] !via-[#0A7982] bg-gradient-to-r  mb-[50px]"
        title="Hardware"
        subtitle="Browse hardware that has the Department for Education tick of approval. 
             Discover what is available to purchase or borrow, and generate a quote for purchases which can then be processed
                by your school business manager.">

        <template #pageIconSVG>
            <hardware-hero-robot class="absolute -top-24 right-12" />
        </template>
    </BasePageHero>
</template>