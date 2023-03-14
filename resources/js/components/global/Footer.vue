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

        <div class="w-full h-[330px] relative">
            <div class="absolute">
                <img class="" src="../../../assets/images/FooterSwoosh.png" alt="Footer Swoosh">
            </div>
            <div class="flex flex-row gap-8">
                <!-- Gov icons here pls -->
            </div>
        </div>

        <div class="h-[110px] bg-white w-full flex">
            <ul class="flex flex-row place-items-center mx-auto text-[#0E4376] text-[20px] font-medium">
                <li class="px-4 cursor-pointer hover:text-[#4a87c0]">
                    Accessibility
                </li>
                <li class="px-2">
                    <div class="h-[20px] w-[1px] bg-[#0E4376]"></div>
                </li>
                <li class="px-4 cursor-pointer hover:text-[#4a87c0]">
                    Acknowledgement of Country
                </li>
                <li class="px-2">
                    <div class="h-[20px] w-[1px] bg-[#0E4376]"></div>
                </li>
                <li class="px-4 cursor-pointer hover:text-[#4a87c0]">
                    Copyright
                </li>
                <li class="px-2">
                    <div class="h-[20px] w-[1px] bg-[#0E4376]"></div>
                </li>
                <li class="px-4 cursor-pointer hover:text-[#4a87c0]">
                    Disclaimer
                </li>
                <li class="px-2">
                    <div class="h-[20px] w-[1px] bg-[#0E4376]"></div>
                </li>
                <li class="px-4 cursor-pointer hover:text-[#4a87c0]">
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
