<script setup>
import SchoolsRobot from '@/js/components/svg/schoolsRobot/SchoolsRobot.vue';
</script>

<template>
    <div>
        <div class="bg-gradient-to-r from-[#002858] via-[#0072DA] to-[#44B8F3]/50 mt-[95px] py-[36px] px-[48px] grid grid-cols-8">
            <div class="col-span-5">
                <h1 class="text-white text-[36px] font-semibold pb-8">
                    Schools
                </h1> 
                <p class="text-white text-[18px] font-normal pb-4">
                    Get inspiration and find out how other schools are enhancing their teaching and learning through digital Technology. Make connections with schools on the same pathway, share learnings and expand your PLC.
                </p>
            </div>
            <div class="col-span-3 relative">
                <SchoolsRobot class="absolute right-12 -top-16" />
            </div>
        </div>
    </div>
</template>
