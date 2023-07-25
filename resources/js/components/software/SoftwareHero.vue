<script setup>

import BaseHero from "@/js/components/bases/BaseHero.vue";
import SoftwareRobot from "@/js/components/svg/software/SoftwareRobot.vue";
</script>
<template>
    <!--    <BaseHero-->
    <!--        :color1="'#002858'"-->
    <!--        :color2="'#4F2D8D'"-->
    <!--        :color3="'#C7B2EA'"-->
    <!--        class="!h-[500px] mb-16"-->
    <!--    >-->
    <!--        <template #titleText>-->
    <!--            {{ `Software` }}-->
    <!--        </template>-->
    <!--        <template #subtitleText1>-->
    <!--            {{ `Find out what software is available to you as an employee and how to access them` }}-->
    <!--        </template>-->
    <!--        <template #icon>-->
    <!--            <SoftwareRobot />-->
    <!--        </template>-->
    <!--    </BaseHero>-->
    <div class="bg-gradient-to-l from-[#C7B2EA] via-[#4F2D8D] to-[#002858] mt-24 py-[36px] px-[48px] grid grid-cols-8 mx-20">
        <div class="col-span-6">
            <h1 class="text-white text-[36px] font-semibold pb-8 uppercase">
                Software
            </h1>
            <p class="text-white text-[16px] font-normal pb-2">
                Find out what software is available to you to use as an employee and how to access it.
            </p>
            <p class="text-white text-[16px] font-normal">
                Use this page to explore software which has the Department for Education tick of approval.
                <br>
                Find out more information,
                see use cases, steps to purchase and possible costs.
            </p>
        </div>
        <div class="col-span-2 relative">
            <SoftwareRobot class="absolute right-12 -top-16" />
        </div>
    </div>
</template>
