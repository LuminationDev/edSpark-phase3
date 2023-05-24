<script setup>
import AdviceRobot from '../svg/adviceRobot/AdviceRobot.vue';

</script>

<template>
    <div class="AdviceHeroContainer">
        <div class="bg-gradient-to-r from-[#002858] via-[#0A7982] to-[#FCEFCC] py-[36px] px-[48px] grid grid-cols-8 mx-huge mt-20">
            <div class="col-span-5">
                <h1 class="text-white text-[36px] font-semibold pb-8">
                    Digital Adoption Group
                </h1>

                <p class="text-white text-[18px] font-normal pb-4">
                    The Digital Adoption Group (DAG) is a cross-divisional group that provides holistic and focused advice on digital technologies.
                </p>

                <p class="text-white text-[18px] font-normal">
                    The objective of the DAG is to support leaders and educators to integrate high-impact technologies by providing system-wide
                    and practical advice on what technology to purchase for teaching and learning and for what purpose.
                </p>
            </div>
            <div class="col-span-3 relative">
                <AdviceRobot class="absolute right-12 -bottom-[20%] h-[250px]" />
            </div>
        </div>
    </div>
</template>
