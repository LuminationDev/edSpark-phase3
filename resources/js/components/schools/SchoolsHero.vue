<script setup>
import SchoolsRobot from '@/js/components/svg/schoolsRobot/schoolsRobot.vue';
</script>

<template>
    <div>
        <div
            class="
                bg-gradient-to-r
                from-[#002858]
                via-[#0072DA]
                to-[#44B8F3]/50
                grid
                grid-cols-8
                mt-24
                mx-5
                px-6
                py-[36px]
                lg:!mx-20
                xl:!px-12
                ">
            <div class="col-span-8 md:!col-span-5">
                <h1 class="font-semibold pb-8 text-[36px] text-white uppercase">
                    Schools
                </h1>
                <p class="font-normal pb-4 text-[18px] text-white">
                    Get inspiration and find out how other schools are enhancing their teaching and learning through digital Technology. Make connections with schools on the same pathway, share learnings and expand your PLC.
                </p>
            </div>
            <div class="col-span-3 relative">
                <SchoolsRobot class="absolute -top-16 hidden md:!block md:scale-75 lg:!right-12 lg:!scale-100" />
            </div>
        </div>
    </div>
</template>
