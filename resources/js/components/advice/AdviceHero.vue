<script setup>
import {useRouter} from "vue-router";

import GenericButton from "@/js/components/button/GenericButton.vue";

import AdviceRobot from '../svg/adviceRobot/AdviceRobot.vue';

const router = useRouter()
</script>

<template>
    <div class="AdviceHeroContainer">
        <div
            class="
                -mt-[90px]
                lg:!mt-24
                lg:!mx-10
                lg:!pt-12
                xl:!mx-huge
                bg-gradient-to-r
                from-[#002858]
                via-[#0A7982]
                to-[#FCEFCC]
                grid
                grid-cols-8
                mb-4
                pb-8
                pt-[160px]
                px-10
                "
        >
            <div class="col-span-8 md:!col-span-5">
                <h1 class="capitalize font-semibold pb-8 text-[36px] text-white">
                    Digital Adoption Group 
                </h1>

                <p class="font-normal pb-2 text-[16px] text-white">
                    The Digital Adoption Group (DAG) offers comprehensive guidance on digital technologies, providing practical, system-wide 
                    advice for purchasing and adopting high-impact technologies that enhance teaching and learning.
                </p>
            </div>
            <div class="col-span-8 flex justify-start items-start relative z-30 md:!col-span-3 md:!items-end md:!justify-end">
                <AdviceRobot
                    class="absolute
                        lg:right-6
                        h-[250px]
                        md:h-[200px]
                        hidden
                        
                        
                        md:!-bottom-[1rem]
                        md:!block
                        xl:!-bottom-[3rem]
                        xl:!right-12" />
                <GenericButton
                    :callback="() => router.push('browse/guide/dag')"
                    button-id="DAGadvice"
                    class="
                        !rounded-none
                        !text-main-navy
                        bg-white
                        hover:bg-slate-50
                        border-2
                        bg-secondary-blueberry
                        font-medium
                        my-2
                        px-12
                        text-lg
                        z-30
                        "
                >
                    View all guides
                </GenericButton>
            </div>
        </div>
    </div>
</template>
