<script setup>
import { useRouter } from "vue-router";

import GenericButton from "@/js/components/button/GenericButton.vue";
import AdviceEducatorsIcon from "@/js/components/svg/adviceIcon/AdviceEducatorsIcon.vue";

const router = useRouter()
</script>

<template>
    <div class="EducatorHeroContainer">
        <div
            class="
                bg-gradient-to-r
                from-[#002858]
                via-[#0B7342]
                to-[#81BFA2]
                grid
                grid-cols-8
                mt-16
                pb-8
                pt-8
                px-10
                lg:!mt-24
                lg:!mx-huge
                lg:!pt-12
                "
        >
            <div class="col-span-5">
                <h1 class="first-letter:uppercase font-semibold lowercase pb-8 text-2xl text-white md:!text-3xl lg:!text-4xl">
                    Advice for Educators
                </h1>

                <p class="font-normal hidden pb-4 text-white text-[16px] md:block">
                    Find advice from a range of trusted sources to help you as an educator to integrate technology in your
                    classroom, your work and your own learning
                </p>
            </div>
            <div class="col-span-3 flex justify-start items-start relative md:!items-end md:!justify-end">
                <AdviceEducatorsIcon
                    class="
                        absolute
                        -right-28
                        -bottom-6
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
                    :callback="() => router.push('browse/advice/educators')"
                    button-id="EDUadvice"
                    class="
                        !rounded-none
                        !text-main-navy
                        bg-white
                        hover:bg-slate-50
                        border-2
                        border-secondary-darkBlue
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
            <div class="block col-span-8 md:hidden">
                <p class="font-normal pb-4 text-white text-xl">
                    Find advice from a range of trusted sources to help you as an educator to integrate technology in your
                    classroom, your work and your own learning
                </p>
            </div>
            <div class="block col-span-8 md:hidden">
                <GenericButton
                    :callback="() => router.push('browse/advice/educators')"
                    class="
                        !rounded-none
                        !text-main-navy
                        bg-white
                        hover:bg-slate-50
                        border-2
                        border-secondary-darkBlue
                        font-semibold
                        px-12
                        text-lg
                        z-30
                        "
                >
                    View all advice
                </GenericButton>
            </div>
        </div>
    </div>
</template>
