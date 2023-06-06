<script setup>
import AdviceRobot from '../svg/adviceRobot/AdviceRobot.vue';

</script>

<template>
    <div class="PartnerHeroContainer">
        <div class="bg-gradient-to-r from-[#0C7B83] via-[#4AB1B3] to-[#26CBC3] mt-20 py-[36px] px-[48px] grid grid-cols-8 h-80 mx-huge">
            <div class="col-span-5">
                <h1 class="text-white text-[36px] font-semibold pb-8 uppercase">
                    Partner Published Resources
                </h1>

                <p class="text-white text-[18px] font-normal pb-4">
                    See the latest resources from our trusted partners
                </p>
            </div>
            <div class="col-span-3 relative">
                <AdviceRobot class="absolute right-12 -bottom-[20%] h-[250px]" />
            </div>
        </div>
    </div>
</template>
