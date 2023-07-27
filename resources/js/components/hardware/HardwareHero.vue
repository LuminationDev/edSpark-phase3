<script setup>
import BaseHero from "@/js/components/bases/BaseHero.vue";
import HardwareHeroRobot from "@/js/components/svg/hardware/HardwareHeroRobot.vue";
</script>
<template>
    <div class="bg-gradient-to-r from-[#002858] via-[#0A7982] !to-[#A5D1BD] mt-24 py-[36px] px-[48px] grid grid-cols-8 mx-20">
        <div class="col-span-5">
            <h1 class="text-white text-[36px] font-semibold pb-8 uppercase">
                Hardware
            </h1>
            <p class="text-white text-[18px] font-normal pb-4">
                Explore what options are available for schools with locked prices on hardware selected to be purpose fit for school use
            </p>
        </div>
        <div class="col-span-3 relative">
            <hardware-hero-robot class="absolute right-12 -top-16" />
        </div>
    </div>
</template>