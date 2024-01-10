<script setup>
import {storeToRefs} from "pinia";
import useSWRV from "swrv";
import {computed, onMounted, ref, watch} from 'vue';
import {useRouter} from "vue-router";

import CardLoading from "@/js/components/card/CardLoading.vue";
import HardwareSearch from "@/js/components/search/HardwareSearch.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {axiosFetcher} from "@/js/helpers/fetcher";
import {useUserStore} from "@/js/stores/useUserStore";
import {useWindowStore} from "@/js/stores/useWindowStore";

import SectionHeader from '../components/global/SectionHeader.vue';
import HardwareCard from '../components/hardware/HardwareCard.vue';
import HardwareHero from '../components/hardware/HardwareHero.vue';
import HardwareInformation from '../components/hardware/HardwareInformation.vue';
import MonitorDisplay from '../components/svg/MonitorDisplay.vue';
import VideoConferencing from '../components/svg/VideoConferencing.vue';

const userStore = useUserStore()
const router = useRouter()
const laptops = ref([])
const audioVisual = ref([])
const emergingTech = ref([])

const {
    data: hardware,
    error
} = useSWRV(API_ENDPOINTS.HARDWARE.FETCH_HARDWARE_POSTS, axiosFetcher, swrvOptions)

watch(hardware, () => {
    laptops.value = hardware.value.filter(item => item.category['categoryName'] === 'Laptop');
    audioVisual.value = hardware.value.filter(item => item.category['categoryName'] === 'Audio Visual');
    emergingTech.value = hardware.value.filter(item => item.category['categoryName'] === 'Emerging Technology')
}, {deep: true})

const windowStore = useWindowStore()
const {isMobile, isTablet, windowWidth} = storeToRefs(windowStore)


const getResponsiveDisplayData = (itemArray) => {
    if (itemArray.length === 0) return []
    if (isMobile.value || isTablet.value) return itemArray.slice(0, 2)

    else if(itemArray.length >= 3) return itemArray.slice(0,3)
    else return itemArray
}

onMounted(() =>{
    const payload = {
        field: 'types',
        value: 'Notebook'
    }
    axios.post(API_ENDPOINTS.CATALOGUE.FETCH_CATALOGUE_BY_FIELD, payload).then(res =>{
        console.log(res.data)
    })
})

</script>

<template>
    <!--        <HardwareHero />-->

    <!--        <HardwareInformation />-->

    <!--        &lt;!&ndash; LAPTOPS &ndash;&gt;-->
    <!--        <SectionHeader-->
    <!--            :classes="`bg-[#048246]`"-->
    <!--            :section="'hardware-laptops'"-->
    <!--            :title="'Laptops'"-->
    <!--            :button-text="'View all hardware'"-->
    <!--            :button-callback="() => router.push('/browse/hardware')"-->
    <!--        />-->
    <!--        <div-->
    <!--            class="grid grid-cols-1 gap-4 place-items-center h-full px-5 md:!grid-cols-2 lg:!px-10 xl:!grid-cols-3 xl:!px-huge"-->
    <!--        >-->
    <!--            <template v-if="laptops && laptops.length > 0">-->
    <!--                <HardwareCard-->
    <!--                    v-for="(laptop, index) in getResponsiveDisplayData(laptops)"-->
    <!--                    :key="index"-->
    <!--                    :data="laptop"-->
    <!--                />-->
    <!--            </template>-->
    <!--            <template v-else>-->
    <!--                <div class="col-span-1 grid w-full md:!col-span-2 xl:!col-span-3">-->
    <!--                    <CardLoading-->
    <!--                        :number-of-rows="1"-->
    <!--                        :number-per-row="windowStore.getNumberOfCardLoading"-->
    <!--                    />-->
    <!--                </div>-->
    <!--            </template>-->
    <!--        </div>-->


    <!--        &lt;!&ndash; AUDIO AND VISUAL &ndash;&gt;-->
    <!--        <SectionHeader-->
    <!--            :classes="`bg-[#1C5CA9]`"-->
    <!--            :section="'hardware-av'"-->
    <!--            :title="'Audio and Visual'"-->
    <!--            :button-text="'View all hardware'"-->
    <!--            :button-callback="() => router.push('/browse/hardware')"-->
    <!--        />-->
    <!--        <div class="grid grid-cols-12 px-5 lg:!px-20 xl:!px-huge">-->
    <!--            <div class="col-span-12 flex flex-row gap-6 mb-6 xl:!col-span-4 xl:!flex-col">-->
    <!--                <div class="flex items-start flex-col pr-8">-->
    <!--                    <h5 class="font-bold text-[18px] pr-8">-->
    <!--                        A variety of AV equipment-->
    <!--                    </h5>-->
    <!--                    <p>-->
    <!--                        Obtain a quote for audio and visual devices purchased for department use.-->
    <!--                    </p>-->
    <!--                </div>-->
    <!--                <div class="flex flex-col gap-4">-->
    <!--                    <div class="flex flex-row gap-4 mt-3 place-items-center">-->
    <!--                        <MonitorDisplay />-->
    <!--                        <h5 class="font-bold text-[18px]">-->
    <!--                            Interactive displays-->
    <!--                        </h5>-->
    <!--                    </div>-->
    <!--                    <div class="flex flex-row gap-4 mt-3 place-items-center">-->
    <!--                        <VideoConferencing />-->
    <!--                        <h5 class="font-bold text-[18px]">-->
    <!--                            Video conferencing-->
    <!--                        </h5>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="col-span-12 grid grid-cols-1 gap-6 md:!grid-cols-2 xl:!col-span-8 xl:!grid-cols-3">-->
    <!--                <template v-if="audioVisual && audioVisual.length > 0">-->
    <!--                    <HardwareCard-->
    <!--                        v-for="(item, index) in audioVisual.slice(0,4)"-->
    <!--                        :key="index"-->
    <!--                        :data="item"-->
    <!--                        :number-per-row="3"-->
    <!--                    />-->
    <!--                </template>-->
    <!--                <template v-else>-->
    <!--                    <div class="col-span-1 md:!col-span-2">-->
    <!--                        <CardLoading-->
    <!--                            :number-of-rows="1"-->
    <!--                            :number-per-row="2"-->
    <!--                        />-->
    <!--                    </div>-->
    <!--                </template>-->
    <!--            </div>-->
    <!--        </div>-->


    <!--        &lt;!&ndash; EMERGING TECHNOLOGY &ndash;&gt;-->
    <!--        <SectionHeader-->
    <!--            :classes="`bg-[#002858]`"-->
    <!--            :section="'hardware-emerging'"-->
    <!--            :title="'Emerging Technology'"-->
    <!--            :button-text="'View all hardware'"-->
    <!--            :button-callback="() => router.push('/browse/hardware')"-->
    <!--        />-->
    <!--        <div class="grid grid-cols-12 px-5 lg:!px-20 xl:!px-huge">-->
    <!--            <div class="col-span-12 xl:col-span-5 flex flex-col gap-6 mb-6 overflow-clip xl:!py-2">-->
    <!--                <h5 class="font-bold text-[18px]">-->
    <!--                    Emerging technology buzzwords-->
    <!--                </h5>-->
    <!--                <p>-->
    <!--                    Ready to turbocharge your classroom? Dive into the digital age with Augmented Reality (AR), Virtual-->
    <!--                    Reality (VR), drones, and 3D printing. These aren’t just cool gadgets—they’re transforming-->
    <!--                    education. From VR trips inside a cell to drones mapping the schoolyard or 3D printing historical-->
    <!--                    artifacts, the possibilities are boundless. The future of teaching is here, and it's thrilling! Grab-->
    <!--                    these tools and watch your lessons come alive.-->
    <!--                </p>-->
    <!--            </div>-->
    <!--            <div class="col-span-12 grid grid-cols-1 gap-4 ml-2 md:!grid-cols-2 xl:!col-span-7">-->
    <!--                <template v-if="emergingTech && emergingTech.length > 0">-->
    <!--                    <HardwareCard-->
    <!--                        v-for="(item, index) in emergingTech.slice(0,2)"-->
    <!--                        :key="item.guid"-->
    <!--                        :data="item"-->
    <!--                        :number-per-row="2"-->
    <!--                    />-->
    <!--                </template>-->
    <!--                <template v-else>-->
    <!--                    <div class="col-span-1 md:!col-span-2">-->
    <!--                        <CardLoading-->
    <!--                            :number-of-rows="1"-->
    <!--                            :number-per-row="2"-->
    <!--                        />-->
    <!--                    </div>-->
    <!--                </template>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <HardwareSearch />
</template>
