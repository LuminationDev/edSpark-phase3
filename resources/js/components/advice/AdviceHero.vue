<script setup>
import AdviceRobot from '../svg/adviceRobot/AdviceRobot.vue';

</script>

<template>
    <div class="AdviceHeroContainer">
        <div class="bg-gradient-to-r from-[#002858] via-[#0A7982] to-[#FCEFCC] py-[36px] px-[48px] grid grid-cols-8 mx-huge mt-20">
            <div class="col-span-5">
                <h1 class="text-white text-[36px] font-semibold pb-8 uppercase">
                    Digital Adoption Group
                </h1>

                <p class="text-white text-[18px] font-normal">
                    The Digital Adoption Group (DAG) offers comprehensive guidance on digital technologies, assisting leaders and educators with seamless integration. We provide practical, system-wide advice for purchasing and adopting high-impact technologies that enhance teaching and learning.
                </p>
            </div>
            <div class="col-span-3 relative">
                <AdviceRobot class="absolute right-12 -bottom-[20%] h-[250px]" />
            </div>
        </div>
    </div>
</template>
