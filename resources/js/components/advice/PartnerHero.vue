<script setup>
import {useRouter} from "vue-router";

import GenericButton from "@/js/components/button/GenericButton.vue";
import AdvicePartnerLogos from "@/js/components/svg/adviceRobot/AdvicePartnerLogos.vue";

import AdviceRobot from '../svg/adviceRobot/AdviceRobot.vue';

const router = useRouter()
</script>

<template>
    <div class="PartnerHeroContainer">
        <div
            class="bg-gradient-to-r
                from-[#0C7B83]
                via-[#4AB1B3]
                to-[#26CBC3]
                grid
                grid-cols-8
                pb-8
                pt-8
                px-10
                
                
                lg:!mt-24
                lg:!mx-huge
                lg:!pt-12">
            <div class="col-span-5">
                <h1 class="first-letter:uppercase font-semibold lowercase pb-8 text-[36px] text-white">
                    Partner Published Resources
                </h1>

                <p class="font-normal hidden pb-4 text-[16px] text-white md:block">
                    Find a curated collection of valuable resources published by our trusted partners to provide you with the latest insights, tips and strategies to help your classroom.
                </p>
            </div>
            <div class="col-span-3 flex justify-start items-start relative md:!col-span-3 md:!items-end md:!justify-end">
                <AdvicePartnerLogos
                    class="
                        absolute
                        -right-28
                        bottom-10
                        h-[250px]
                        scale-50
                        md:!block
                        md:!bottom-4
                        md:!right-0
                        md:!scale-100
                        lg:!right-0
                        xl:!-bottom-4
                        xl:!right-12
                        "
                />
                <GenericButton
                    :callback="() => router.push('browse/guide/partner')"
                    button-id="PTRadvice"
                    class="
                        !rounded-none
                        !text-main-navy
                        bg-white
                        hover:bg-slate-50
                        border-2
                        bg-secondary-blueberry
                        font-medium
                        hidden
                        px-12
                        text-lg
                        z-30
                        md:!block
                        "
                >
                    View all guides
                </GenericButton>
            </div>
            <div class="block col-span-8 md:hidden">
                <p class="font-normal pb-4 text-white text-xl">
                    Find a curated collection of valuable resources published by our trusted partners to provide you with the latest insights, tips and strategies to help your classroom.
                </p>
            </div>
            <div class="block col-span-8 md:hidden">
                <GenericButton
                    :callback="() => router.push('browse/guide/partner')"
                    class="
                        !rounded-none
                        !text-main-navy
                        bg-white
                        hover:bg-slate-50
                        border-2
                        bg-secondary-blueberry
                        font-medium
                        hidden
                        px-12
                        text-lg
                        z-30
                        md:!block
                        "
                >
                    View all advice
                </GenericButton>
            </div>
        </div>
    </div>
</template>
