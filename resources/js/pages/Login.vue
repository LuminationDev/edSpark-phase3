<script setup>
import HardwareHero from '../components/hardware/HardwareHero.vue';
import HardwareInformation from '../components/hardware/HardwareInformation.vue';
import SectionHeader from '../components/global/SectionHeader.vue';
import HardwareCard from '../components/hardware/HardwareCard.vue';

import VideoConferencing from '../components/svg/VideoConferencing.vue';
import MonitorDisplay from '../components/svg/MonitorDisplay.vue';

import {computed} from 'vue';
import useSWRV from "swrv";
import {serverURL} from "@/js/constants/serverUrl";
import {axiosFetcher} from "@/js/helpers/fetcher";

const {data: hardware, error } = useSWRV(`${serverURL}/fetchAllProducts`, axiosFetcher)


const laptops = computed(()=>{
    if(hardware.value){
        return hardware.value.filter(item => item.category['categoryName'] === 'Laptop');
    } else{
        return []
    }
})
const audioVisual = computed(()=>{
    if(hardware.value){
        return hardware.value.filter(item => item.category['categoryName'] === 'Audio Visual');
    } else{
        return []
    }
})
const emergingTech = computed(()=>{
    if(hardware.value){
        return hardware.value.filter(item => item.category['categoryName'] === 'Emerging Technology');
    } else{
        return []
    }
})



</script>

<template>
<<<<<<<< HEAD:resources/js/pages/Login.vue
    <div class="mt-12">
        Hello
========
    <div>
        <HardwareHero />

        <HardwareInformation />

        <!-- LAPTOPS -->
        <SectionHeader
            :classes="`bg-[#048246]`"
            :section="'hardware-laptops'"
            :title="'Laptops'"
            :button-text="'View all laptops'"
        />
        <div class="mx-[81px] mt-[64px] flex flex-row flex-1 flex-wrap h-full justify-between">
            <HardwareCard
                v-for="(laptop, index) in laptops.slice(0,4)"
                :key="index"
                :hardware-content="laptop"
                :number-per-row="4"
            />
        </div>


        <!-- AUDIO AND VISUAL -->
        <SectionHeader
            :classes="`bg-[#1C5CA9]`"
            :section="'hardware-av'"
            :title="'Audio and Visual'"
            :button-text="'View all devices'"
        />
        <div class="mx-[81px] mt-[64px] grid grid-cols-12">
            <div class="col-span-3 flex flex-col gap-6">
                <h5 class="text-[18px] font-bold">
                    A Variety of AV equipment
                </h5>
                <p>
                    Obtain a quote for audio and visual devices purchased for department use.
                </p>

                <div class="flex flex-row gap-4 place-items-center mt-3">
                    <MonitorDisplay />
                    <h5 class="text-[18px] font-bold">
                        Interactive Displays
                    </h5>
                </div>
                <div class="flex flex-row gap-4 place-items-center mt-3">
                    <VideoConferencing />
                    <h5 class="text-[18px] font-bold">
                        Video Conferencing
                    </h5>
                </div>
            </div>
            <div class="col-span-9 flex flex-row justify-between">
                <HardwareCard
                    v-for="(item, index) in audioVisual.slice(0,4)"
                    :key="index"
                    :hardware-content="item"
                    :number-per-row="3"
                />
            </div>
        </div>


        <!-- EMERGING TECHNOLOGY -->
        <SectionHeader
            :classes="`bg-[#002858]`"
            :section="'hardware-emerging'"
            :title="'Emerging Technology'"
            :button-text="'View all devices'"
        />
        <div class="mx-[81px] mt-[64px] grid grid-cols-12">
            <div class="col-span-6 flex flex-col gap-6">
                <h5 class="text-[18px] font-bold">
                    Emerging Technology Buzzwords
                </h5>
                <p>
                    Nam sit amet fermentum mauris. Nullam ultrices augue a turpis aliquam dapibus.
                    Vestibulum laoreet at nisl in ultrices. Quisque tempus turpis id tempus
                    finibus. Integer lacus felis, ultrices sit amet libero nec, pulvinar dictum metus.
                    Curabitur sapien odio, scelerisque sit amet tincidunt ac, ullamcorper in quam.
                    Proin euismod mi ac ligula volutpat, sed facilisis leo laoreet.
                </p>
            </div>
            <div class="col-span-6 flex flex-row justify-between">
                <HardwareCard
                    v-for="(item, index) in emergingTech.slice(0,4)"
                    :key="index"
                    :hardware-content="item"
                    :number-per-row="2"
                />
            </div>
        </div>
>>>>>>>> 9c4af75b41fe553df20b043847d77a2fdf76e791:resources/js/pages/Hardware.vue
    </div>
</template>
