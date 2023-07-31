<script>
import FooterSwoosh from '../svg/FooterSwoosh.vue';

export default {

    components: {
        FooterSwoosh
    },


}
</script>
<template>
    <div class="flex flex-col w-full">
        <!-- <FooterSwoosh class="w-full pointer-events-none" /> -->

        <div class="hidden relative w-full xl:!h-[330px] xl:block">
            <div class="absolute">
                <img
                    class=""
                    src="../../../assets/images/FooterSwoosh.png"
                    alt="Footer Swoosh"
                >
            </div>
            <div class="flex flex-row gap-8">
                <!-- Gov icons here pls -->
            </div>
        </div>

        <div class="bg-white flex py-4 w-full">
            <ul class="flex flex-col font-medium mx-auto place-items-center text-[#0E4376] text-[20px] lg:!flex-row">
                <li class="cursor-pointer px-4 hover:text-[#4a87c0]">
                    Accessibility
                </li>
                <li class="px-2">
                    <div class="bg-[#0E4376] h-[20px] w-[1px]" />
                </li>
                <li class="cursor-pointer px-4 hover:text-[#4a87c0]">
                    Acknowledgement of Country
                </li>
                <li class="px-2">
                    <div class="bg-[#0E4376] h-[20px] w-[1px]" />
                </li>
                <li class="cursor-pointer px-4 hover:text-[#4a87c0]">
                    Copyright
                </li>
                <li class="px-2">
                    <div class="bg-[#0E4376] h-[20px] w-[1px]" />
                </li>
                <li class="cursor-pointer px-4 hover:text-[#4a87c0]">
                    Disclaimer
                </li>
                <li class="px-2">
                    <div class="bg-[#0E4376] h-[20px] w-[1px]" />
                </li>
                <li class="cursor-pointer px-4 hover:text-[#4a87c0]">
                    Privacy
                </li>
            </ul>
        </div>
    </div>
</template>

<style>
    .footer-background {
        clip-path: inset(0 0 round 0 0 75%);
    }
</style>
