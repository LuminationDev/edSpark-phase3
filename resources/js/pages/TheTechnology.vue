<script setup>


import {storeToRefs} from "pinia";
import {onMounted} from "vue";
import {useRouter} from "vue-router";

import BaseLandingCardRow from "@/js/components/bases/BaseLandingCardRow.vue";
import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import BaseLandingSection from "@/js/components/bases/BaseLandingSection.vue";
import PopularResourceShortcuts from "@/js/components/bases/PopularResourceShortcuts.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import CardLoading from "@/js/components/card/CardLoading.vue";
import SoftwareIllustration from "@/js/components/dashboard/SoftwareIllustration.vue";
import HardwareCard from "@/js/components/hardware/HardwareCard.vue";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import HardwareRobot from "@/js/components/svg/hardwareRobot/HardwareRobot.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {cardDataWithGuid, getNRandomElementsFromArray} from "@/js/helpers/cardDataHelper";
import {lowerSlugify} from "@/js/helpers/stringHelpers";
import {adviceService} from "@/js/service/adviceService";
import {softwareService} from "@/js/service/softwareService";
import {useAdviceStore} from "@/js/stores/useAdviceStore";
import {useHardwareStore} from "@/js/stores/useHardwareStore";
import {useSoftwareStore} from "@/js/stores/useSoftwareStore";

const router = useRouter()
const {allSoftware} = storeToRefs(useSoftwareStore())
const {allHardware} = storeToRefs(useHardwareStore())
const {allAdvice} = storeToRefs(useAdviceStore())

onMounted(() => {
    softwareService.fetchAllSoftware().then(res => {
        allSoftware.value = res
    })
    // TODO: Replace with catalogue Items instead of this
    axios.get(API_ENDPOINTS.HARDWARE.FETCH_HARDWARE_POSTS).then(res => {
        allHardware.value = cardDataWithGuid(res.data)
    })
    adviceService.fetchAllAdvice().then(res =>
        allAdvice.value = res
    )
})


const handleClickPopularTech = (techId, title) => {
    return router.push({
        name: "software-single",
        params: {id: techId, slug: lowerSlugify(title)},
    })
}
</script>

<template>
    <BaseLandingHero
        :title="LandingHeroText['technology']['title']"
        :title-paragraph="LandingHeroText['technology']['subtitle']"
        background-color="technologyPurple"
        swoosh-color="technologyPurple"
    >
        <template #robotIllustration>
            <HardwareRobot 
                class="absolute top-16 left-32 scale-125 py-4" />
        </template>
    </BaseLandingHero>
    <BaseLandingSection background-color="white">
        <template #title>
            Popular Technology
        </template>
        <template #content>
            <PopularResourceShortcuts
                v-if="allSoftware && allSoftware.length"
                :resource-list="allSoftware"
                border-color="technologyPurple"
                :resource-click-callback="handleClickPopularTech"
            />
            <Loader
                v-else
                loader-message="Loading popular resource"
                loader-type="small"
            />
        </template>
    </BaseLandingSection>
    <BaseLandingSection
        background-color="technologyPurple"
    >
        <template #title>
            Latest technology
        </template>
        <template #button>
            <GenericButton
                :callback="() => router.push('browse/software')"
                :type="'purple'"
            >
                View all apps and programs
            </GenericButton>
        </template>
        <template #content>
            <div class="flex flex-col gap-6 group/bg h-full relative lg:!flex-row">
                <div class="flex flex-col max-h-[1000px] pl-8 place-items-center w-full lg:w-[40%]">
                    <SoftwareIllustration />
                </div>
                <template v-if="!allSoftware || !allSoftware.length">
                    <div class="">
                        <CardLoading
                            :number-of-rows="1"
                            :number-per-row="2"
                        />
                    </div>
                </template>
                <template v-else>
                    <div
                        class="
                            grid
                            sm:grid-cols-1
                            md:grid-cols-2
                            lg:grid-cols-1
                            xl:grid-cols-2
                            grid-cols-1
                            gap-10
                            place-items-center
                            h-fit
                            max-h-[1000px]
                            overflow-hidden
                            px-8
                            xl:px-2
                            "
                    >
                        <SoftwareCard
                            v-for="software in getNRandomElementsFromArray(allSoftware,2)"
                            :key="software.guid"
                            :data="software"
                        />
                    </div>
                </template>
            </div>
        </template>
    </BaseLandingSection>
    <BaseLandingSection
        background-color="white"
    >
        <template #title>
            Latest equipment and devices
        </template>
        <template #button>
            <GenericButton
                :callback="() => router.push('/browse/hardware')"
                :type="'purple'"
            >
                View all equipment and devices
            </GenericButton>
        </template>
        <template #content>
            <BaseLandingCardRow :resource-list="allHardware">
                <template #rowContent>
                    <HardwareCard
                        v-for="(hardware,index) in getNRandomElementsFromArray(allHardware,3)"
                        :key="index"
                        :data="hardware"
                    />
                </template>
            </BaseLandingCardRow>
        </template>
    </BaseLandingSection>

    <BaseLandingSection background-color="technologyPurple">
        <template #title>
            Technology guides and resources
        </template>
        <template #button>
            <GenericButton
                :callback="() => router.push('/browse/guide/dag')"
                :type="'purple'"
            >
                View all guides
            </GenericButton>
        </template>
        <template #content>
            <BaseLandingCardRow :resource-list="allAdvice">
                <template #rowContent>
                    <HardwareCard
                        v-for="(advice,index) in getNRandomElementsFromArray(allAdvice,3)"
                        :key="index"
                        :data="advice"
                    />
                </template>
            </BaseLandingCardRow>
        </template>
    </BaseLandingSection>
</template>