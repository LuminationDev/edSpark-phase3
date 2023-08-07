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
import {useWindowStore} from "@/js/stores/useWindowStore";
import {storeToRefs} from "pinia";


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

const windowStore = useWindowStore()
const {isMobile, isTablet, windowWidth} = storeToRefs(windowStore)

// const responsiveDisplayLaptop = computed(() => {
//     if(isMobile.value){
//         return laptops.value.slice(0,2) || []
//     }else if(isTablet.value){
//
//     }
//     else{
//         return laptops.value
//     }
// })

const getResponsiveDisplayData = (itemArray) => {
    if(itemArray.length === 0) return []
    if(isMobile.value) return itemArray.slice(0,2)
    if(isTablet.value) return itemArray.slice(0,4)
    else return itemArray
}


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
            :button-text="'View all hardware'"
            :button-callback="() => router.push('/browse/hardware')"
        />
        <div class="grid grid-cols-1 gap-4 place-items-center h-full px-5 md:!grid-cols-2 lg:!px-10 xl:!grid-cols-3 xl:!px-huge">
            <template v-if="laptops && laptops.length > 0">
                <HardwareCard
                    v-for="(laptop, index) in getResponsiveDisplayData(laptops)"
                    :key="index"
                    :hardware-content="laptop"
                    :number-per-row="4"
                />
            </template>
            <template v-else>
                <div class="col-span-1 grid md:!col-span-2 xl:!col-span-3">
                    <CardLoading
                        :number-of-rows="1"
                        :number-per-row="windowStore.getNumberOfCardLoading"
                    />
                </div>
            </template>
        </div>


        <!-- AUDIO AND VISUAL -->
        <SectionHeader
            :classes="`bg-[#1C5CA9]`"
            :section="'hardware-av'"
            :title="'Audio and Visual'"
            :button-text="'View all hardware'"
            :button-callback="() => router.push('/browse/hardware')"
        />
        <div class="grid grid-cols-12 px-5 lg:!px-20 xl:!px-huge">
            <div class="col-span-12 flex flex-row gap-6 mb-6 xl:!col-span-4 xl:!flex-col">
                <div class="flex items-start flex-col">
                    <h5 class="font-bold text-[18px]">
                        A Variety of AV equipment
                    </h5>
                    <p>
                        Obtain a quote for audio and visual devices purchased for department use.
                    </p>
                </div>
                <div class="flex xl:flex-row flex-col">
                    <div class="flex flex-row gap-4 mt-3 place-items-center">
                        <MonitorDisplay />
                        <h5 class="font-bold text-[18px]">
                            Interactive Displays
                        </h5>
                    </div>
                    <div class="flex flex-row gap-4 mt-3 place-items-center">
                        <VideoConferencing />
                        <h5 class="font-bold text-[18px]">
                            Video Conferencing
                        </h5>
                    </div>
                </div>
            </div>
            <div class="col-span-12 grid grid-cols-1 gap-6 md:!grid-cols-2 xl:!col-span-8 xl:!grid-cols-3">
                <template v-if="audioVisual && audioVisual.length > 0">
                    <HardwareCard
                        v-for="(item, index) in audioVisual.slice(0,4)"
                        :key="index"
                        :hardware-content="item"
                        :number-per-row="3"
                    />
                </template>
                <template v-else>
                    <div class="col-span-1 md:!col-span-2">
                        <CardLoading
                            :number-of-rows="1"
                            :number-per-row="2"
                        />
                    </div>
                </template>
            </div>
        </div>


        <!-- EMERGING TECHNOLOGY -->
        <SectionHeader
            :classes="`bg-[#002858]`"
            :section="'hardware-emerging'"
            :title="'Emerging Technology'"
            :button-text="'View all hardware'"
            :button-callback="() => router.push('/browse/hardware')"
        />
        <div class="grid grid-cols-12 px-5 lg:!px-20 xl:!px-huge">
            <div class="col-span-12 xl:col-span-5 flex flex-col gap-4 overflow-clip py-10 xl:!py-2">
                <h5 class="font-bold text-[18px]">
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
            <div class="col-span-12 grid grid-cols-1 gap-4 ml-2 md:!grid-cols-2 xl:!col-span-7">
                <template v-if="emergingTech && emergingTech.length > 0">
                    <HardwareCard
                        v-for="(item, index) in emergingTech.slice(0,4)"
                        :key="index + guid()"
                        :hardware-content="item"
                        :number-per-row="2"
                    />
                </template>
                <template v-else>
                    <div class="col-span-1 md:!col-span-2">
                        <CardLoading
                            :number-of-rows="1"
                            :number-per-row="2"
                        />
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
