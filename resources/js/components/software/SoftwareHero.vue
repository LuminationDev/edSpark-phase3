<script setup>

import BaseHero from "@/js/components/bases/BaseHero.vue";
import SoftwareRobot from "@/js/components/svg/software/SoftwareRobot.vue";
</script>
<template>
    <BaseHero
        color1="#002858"
        color2="#4F2D8D"
        color3="#C7B2EA"
        class="!h-fit mb-16"
    >
        <template #titleText>
            {{ `Software` }}
        </template>
        <template #subtitleText1>
            {{ `Find out what software is available to you as an employee and how to access them` }}
        </template>
        <template #icon>
            <SoftwareRobot />
        </template>
    </BaseHero>
</template>