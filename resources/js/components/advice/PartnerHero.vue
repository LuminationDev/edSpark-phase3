<script setup>
import AdviceRobot from '../svg/adviceRobot/AdviceRobot.vue';
import GenericButton from "@/js/components/button/GenericButton.vue";
import {useRouter} from "vue-router";

const router = useRouter()
</script>

<template>
    <div class="PartnerHeroContainer">
        <div class="bg-gradient-to-r from-[#0C7B83] via-[#4AB1B3] to-[#26CBC3] mt-20 py-[36px] px-[48px] grid grid-cols-8 h-72 mx-huge">
            <div class="col-span-5">
                <h1 class="text-white text-[36px] font-semibold pb-8 uppercase">
                    Partner Published Resources
                </h1>

                <p class="text-white text-[18px] font-normal pb-4">
                    See the latest resources from our trusted partners
                </p>
            </div>
            <div class="col-span-3 relative flex justify-end items-end">
                <AdviceRobot class="absolute right-12 -bottom-[20%] h-[250px]" />
                <GenericButton
                    :callback="() => router.push('browse/advices')"
                    class="bg-white text-lg font-semibold !text-secondary-darkBlue !rounded-none border-2 border-secondary-darkBlue z-30 px-12 hover:bg-slate-50"
                >
                    View all advice
                </GenericButton>
            </div>
        </div>
    </div>
</template>
