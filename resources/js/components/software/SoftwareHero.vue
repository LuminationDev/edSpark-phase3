<script setup>

import BaseHero from "@/js/components/bases/BaseHero.vue";
import SoftwareRobot from "@/js/components/svg/software/SoftwareRobot.vue";
</script>
<template>
    <div
        class="
            -mt-[90px]
            lg:!mt-24
            lg:!mx-10
            lg:!pt-12
            xl:!mx-huge
            bg-gradient-to-l
            from-[#C7B2EA]
            via-[#4F2D8D]
            to-[#002858]
            grid
            grid-cols-8
            pb-8
            pt-[160px]
            px-10
            mb-4 
            "
    >



        <div class="col-span-8 md:!col-span-5 lg:!col-span-6 pr-8">
            <h1 class="font-semibold pb-8 text-[36px] text-white lowercase first-letter:uppercase">
                Software
            </h1>
            <p class="font-normal pb-2 text-[16px] text-white">
                Find out what software is available to you to use as an employee and how to access it.
            </p>
            <p class="font-normal text-[16px] text-white">
                Use this page to explore software which has the Department for Education tick of approval.
                
                Find out more information,
                see use cases, steps to purchase and possible costs.
            </p>
        </div>
        <div class="col-span-3 hidden relative md:!block lg:!col-span-2">
            <SoftwareRobot class="absolute  md:!scale-75 
                                            lg:!scale-100 
                                            xl:!right-12" />

        </div>
    </div>
</template>
