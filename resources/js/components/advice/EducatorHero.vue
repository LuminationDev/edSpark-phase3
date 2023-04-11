<script setup>
import AdviceRobot from '../svg/adviceRobot/AdviceRobot.vue';

</script>

<template>
    <div class="EducatorHeroContainer">
        <div class="bg-gradient-to-r from-[#002858] via-[#0B7342] to-[#81BFA2] mt-[95px] py-6 px-[48px] grid grid-cols-8">
            <div class="col-span-5">
                <h1 class="text-white text-[36px] font-semibold pb-8 uppercase">
                    Advice for Educators
                </h1>

                <p class="text-white text-[18px] font-normal pb-4">
                    Find advice from a range of trusted sources to help you as an educatr to integrate technology in your classroom, your work and your own learning
                </p>
            </div>
            <div class="col-span-3 relative">
                <AdviceRobot class="absolute right-12" />
            </div>
        </div>
    </div>
</template>
