<script setup>
    import HardwareRobot from '../svg/hardwareRobot/HardwareRobot.vue';

</script>

<template>
    <div class="bg-gradient-to-r from-[#002858] via-[#0A7982] to-[#A5D1BD]/50 mx-[81px] mt-[95px] py-[36px] px-[48px] grid grid-cols-8">
        <div class="col-span-5">
            <h1 class="text-white text-[36px] font-semibold pb-8">
                Hardware
            </h1>

            <p class="text-white text-[18px] font-normal pb-4">
                Explore what options are available for schools with locked in prices on hardware selected to be purpose fit for school use.
            </p>
        </div>
        <div class="col-span-3 relative">
            <HardwareRobot
                class="absolute left-1/2 top-1/2  -translate-y-1/2"
            />
        </div>
    </div>
</template>
