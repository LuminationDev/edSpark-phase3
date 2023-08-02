<script setup>
import BasePageHero from "@/js/components/bases/BasePageHero.vue";
</script>

<template>
    <!--    <div>-->
    <!--        <div class="bg-gradient-to-r from-[#002858] via-[#0072DA] to-[#44B8F3]/50 grid grid-cols-8 mt-24 mx-[81px] px-[48px] py-[36px]">-->
    <!--            <div class="col-span-5">-->
    <!--                <h1 class="font-semibold pb-8 text-[36px] text-white uppercase">-->
    <!--                    Partners-->
    <!--                </h1>-->

    <!--                <p class="font-normal pb-4 text-[18px] text-white">-->
    <!--                    The department have partnered with trusted companies to provide you with the best digital tool at your fingertips. Find out more about our partners and access a range of curated resources.-->
    <!--                </p>-->
    <!--            </div>-->
    <!--            <div class="col-span-3">-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <BasePageHero
        class="bg-gradient-to-r from-[#002858] via-[#0072DA] to-[#44B8F3]/50"
        title="Partners"
        subtitle="The department have partnered with trusted companies to provide you with the best digital tool at your fingertips. Find out more about our partners and access a range of curated resources."
    >
        <template #pageIconSVG>
            <img
                class="mx-auto"
                src="../../../assets/images/PartnersPuzzle.png"
                alt="Industry Partners connecting pieces of the puzzle"
            >
        </template>
    </BasePageHero>
</template>