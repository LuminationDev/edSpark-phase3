<script setup>
import BasePageHero from "@/js/components/bases/BasePageHero.vue";
import SchoolsRobot from '@/js/components/svg/schoolsRobot/schoolsRobot.vue';
</script>

<template>
    <BasePageHero
        class="bg-gradient-to-r from-[#002858] via-[#0072DA] to-[#44B8F3]/50"
        title="Schools"
        subtitle="Get inspiration and find out how other schools are enhancing their teaching and learning through digital Technology. Make connections with schools on the same pathway, share learnings and expand your PLC."
    >
        <template #pageIconSVG>
            <SchoolsRobot
                class="absolute mr-0 sm:!-mr-[50px] -top-20 md:!-top-[3.5rem] lg:!-top-[5.5rem] hidden md:!right-8  md:!block md:scale-75 lg:!right-12 lg:!scale-90"
            />
        </template>
    </BasePageHero>
</template>
