<script setup>
import AdviceEducatorsIcon from "@/js/components/svg/adviceIcon/AdviceEducatorsIcon.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import {useRouter} from "vue-router";

const router = useRouter()
</script>

<template>
    <div class="EducatorHeroContainer">
        <div class="bg-gradient-to-r from-[#002858] via-[#0B7342] to-[#81BFA2] mt-20 py-6 px-[48px] grid grid-cols-8 h-80 mx-huge">
            <div class="col-span-5">
                <h1 class="text-white text-[36px] font-semibold pb-8 uppercase">
                    Advice for Educators
                </h1>

                <p class="text-white text-[18px] font-normal pb-4">
                    Find advice from a range of trusted sources to help you as an educatr to integrate technology in your classroom, your work and your own learning
                </p>
            </div>
            <div class="col-span-3 relative flex justify-end items-end">
                <AdviceEducatorsIcon class="absolute right-12" />
                <GenericButton
                    :callback="() => router.push('browse/advices')"
                    class="bg-white text-lg font-semibold !text-adviceGreen !rounded-none border-2 border-adviceGreen z-30 px-12 hover:bg-slate-50"
                >
                    View all advice
                </GenericButton>
            </div>
        </div>
    </div>
</template>
