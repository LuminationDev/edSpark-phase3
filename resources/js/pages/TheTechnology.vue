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
import SoftwareRobot from "@/js/components/svg/software/SoftwareRobot.vue";
import {LandingHeroText} from "@/js/constants/LandingPageTitle";
import {getNRandomElementsFromArray} from "@/js/helpers/cardDataHelper";
import {softwareService} from "@/js/service/softwareService";
import {useHardwareStore} from "@/js/stores/useHardwareStore";
import {useSoftwareStore} from "@/js/stores/useSoftwareStore";

const router = useRouter()
const {allSoftware} = storeToRefs(useSoftwareStore())
const {allHardware} = storeToRefs(useHardwareStore())
const handleClickPopularTech = () => {
    console.log('jhee popular')
}

onMounted(() => {
    softwareService.fetchAllSoftware().then(res => {
        allSoftware.value = res
    })
})
</script>

<template>
    <BaseLandingHero
        :title="LandingHeroText['technology']['title']"
        :title-paragraph="LandingHeroText['technology']['subtitle']"
        background-color="purple"
    >
        <template #robotIllustration>
            <SoftwareRobot class="absolute top-10 left-36" />
        </template>
    </BaseLandingHero>
    <BaseLandingSection background-color="white">
        <template #title>
            Popular guides
        </template>
        <template #button>
            <GenericButton
                :callback="handleClickPopularTech"
                :type="'teal'"
            >
                View all guides
            </GenericButton>
        </template>
        <template #content>
            <PopularResourceShortcuts
                v-if="allSoftware && allSoftware.length"
                :resource-list="allSoftware"
            />
            <Loader
                v-else
                loader-message="loading popular resource"
                loader-type="small"
            />
        </template>
    </BaseLandingSection>
    <BaseLandingSection
        background-color="purple"
    >
        <template #title>
            Latest technology
        </template>
        <template #button>
            <GenericButton
                :callback="() => router.push('browse/guide')"
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
            Guides and resources
        </template>
        <template #button>
            <GenericButton
                :callback="() => router.push('browse/guide')"
                :type="'purple'"
            >
                View all equipment and devices
            </GenericButton>
        </template>
        <template #content>
            <BaseLandingCardRow :resource-list="guideList">
                <template #rowContent>
                    <HardwareCard
                        v-for="(guide,index) in getNRandomElementsFromArray(guideList,3)"
                        :key="index"
                        :data="guide"
                    />
                </template>
            </BaseLandingCardRow>
        </template>
    </BaseLandingSection>
</template>