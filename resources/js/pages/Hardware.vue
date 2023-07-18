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
import {useRouter} from "vue-router";
import {guid} from "@/js/helpers/guidGenerator";
import CardLoading from "@/js/components/card/CardLoading.vue";


const router = useRouter()
const {data: hardware, error} = useSWRV(`${serverURL}/fetchAllProducts`, axiosFetcher)


const laptops = computed(() => {
    if (hardware.value) {
        return hardware.value.filter(item => item.category['categoryName'] === 'Laptop');
    } else {
        return []
    }
})
const audioVisual = computed(() => {
    if (hardware.value) {
        return hardware.value.filter(item => item.category['categoryName'] === 'Audio Visual');
    } else {
        return []
    }
})
const emergingTech = computed(() => {
    if (hardware.value) {
        return hardware.value.filter(item => item.category['categoryName'] === 'Emerging Technology');
    } else {
        return []
    }
})


</script>

<template>
    <div>
        <HardwareHero />

        <HardwareInformation />

        <!-- LAPTOPS -->
        <SectionHeader
            :classes="`bg-[#048246]`"
            :section="'hardware-laptops'"
            :title="'Laptops'"
            :button-text="'View all laptops'"
            :button-callback="() => router.push('/browse/hardwares')"
        />
        <div class="px-huge flex flex-row flex-1 gap-6 flex-wrap h-full justify-between">
            <template v-if="laptops && laptops.length > 0">
                <HardwareCard
                    v-for="(laptop, index) in laptops.slice(0,3)"
                    :key="index"
                    :hardware-content="laptop"
                    :number-per-row="4"
                />
            </template>
            <template v-else>
                <CardLoading
                    :number-of-rows="1"
                    :number-per-row="3"
                />
            </template>
        </div>


        <!-- AUDIO AND VISUAL -->
        <SectionHeader
            :classes="`bg-[#1C5CA9]`"
            :section="'hardware-av'"
            :title="'Audio and Visual'"
            :button-text="'View all devices'"
        />
        <div class="px-huge grid grid-cols-12">
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
            <div class="col-span-9 flex flex-row gap-6 justify-between">
                <template v-if="audioVisual && audioVisual.length > 0">
                    <HardwareCard
                        v-for="(item, index) in audioVisual.slice(0,4)"
                        :key="index"
                        :hardware-content="item"
                        :number-per-row="3"
                    />
                </template>
                <template v-else>
                    <CardLoading
                        :number-of-rows="1"
                        :number-per-row="2"
                    />
                </template>
            </div>
        </div>


        <!-- EMERGING TECHNOLOGY -->
        <SectionHeader
            :classes="`bg-[#002858]`"
            :section="'hardware-emerging'"
            :title="'Emerging Technology'"
            :button-text="'View all devices'"
        />
        <div class="px-huge grid grid-cols-12">
            <div class="col-span-5 flex flex-col gap-6 overflow-clip">
                <h5 class="text-[18px] font-bold">
                    Emerging Technology Buzzwords
                </h5>
                <p>
                    Nam sit amet fermentum mauris. Nullam ultrices augue a turpis aliquam dapibus.
                    Vestibulum laoreet at nisl in ultrices. Quisque tempus turpis id tempus
                    finibus. Integer lacus felis, ulztrices sit amet libero nec, pulvinar dictum metus.
                    Curabitur sapien odio, scelerisque sit amet tincidunt ac, ullamcorper in quam.
                    Proin euismod mi ac ligula volutpat, sed facilisis leo laoreet.
                </p>
            </div>
            <div class="col-span-7 ml-2 flex flex-row gap-4 justify-between">
                <template v-if="emergingTech && emergingTech.length > 0">
                    <HardwareCard
                        v-for="(item, index) in emergingTech.slice(0,4)"
                        :key="index + guid()"
                        :hardware-content="item"
                        :number-per-row="2"
                    />
                </template>
                <template v-else>
                    <CardLoading
                        :number-of-rows="1"
                        :number-per-row="2"
                    />
                </template>
            </div>
        </div>
    </div>
</template>
