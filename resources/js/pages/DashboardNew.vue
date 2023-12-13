<script setup>
import {storeToRefs} from "pinia";
import useSWRV from "swrv";
import {computed} from 'vue';
import {useRouter} from "vue-router";

import CardLoading from '@/js/components/card/CardLoading.vue';
import CarouselGenerator from "@/js/components/card/CarouselGenerator.vue";
import DAGInfoSection from "@/js/components/dashboard/DAGInfoSection.vue";
import DashboardHero from '@/js/components/dashboard/DashboardHero.vue';
import SoftwareIllustration from "@/js/components/dashboard/SoftwareIllustration.vue";
import SectionHeader from '@/js/components/global/SectionHeader.vue';
import SchoolSectionDashboard from "@/js/components/schools/SchoolSectionDashboard.vue";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import SoftwareRobot from '@/js/components/svg/SoftwareRobot.vue';
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {axiosFetcher} from "@/js/helpers/fetcher";
import {useUserStore} from '@/js/stores/useUserStore';
import {useWindowStore} from "@/js/stores/useWindowStore";

const router = useRouter()
const userStore = useUserStore();
const {currentUser} = storeToRefs(userStore)
const windowStore = useWindowStore()
const {isMobile} = storeToRefs(windowStore)


const shouldStartSwrv = computed(() => {
    return Boolean(currentUser.value.id)
})

const {
    data: eventsData,
} = useSWRV(() => shouldStartSwrv.value ? API_ENDPOINTS.EVENT.FETCH_EVENT_POSTS : null, axiosFetcher, swrvOptions)
const {
    data: softwaresData,
} = useSWRV(() => shouldStartSwrv.value ? API_ENDPOINTS.SOFTWARE.FETCH_SOFTWARE_POSTS : null, axiosFetcher, swrvOptions)
const {
    data: advicesData,
} = useSWRV(() => shouldStartSwrv.value ? API_ENDPOINTS.ADVICE.FETCH_ADVICE_POSTS : null, axiosFetcher, swrvOptions)


const softwareResponsiveData = computed(() => {
    if (!softwaresData.value.length) {
        return []
    } else if (isMobile.value) {
        return softwaresData.value.slice(0, 2)
    } else {
        return softwaresData.value.slice(0, 4)
    }
})

</script>

<template>
    <div>
        <DashboardHero class="-mt-extraHuge" />

        <SectionHeader
            :classes="'bg-main-darkTeal'"
            :section="'advice'"
            :title="'Guides'"
            :button-text="'View all guides'"
            :button-callback="() => router.push('/browse/guide')"
        />

        <div class="flex flex-col w-full lg:!flex-row lg:px-16">
            <div class="DAGInfoSection px-8 w-full lg:!pl-8 lg:!w-2/5 xl:!w-1/4">
                <DAGInfoSection />
            </div>
            <div class="DAGAdviceCarousel w-full lg:!w-3/5 xl:!w-3/4">
                <CarouselGenerator
                    data-type="advice"
                    :data-array="advicesData ? advicesData : []"
                    special-attribute="twoThirdWide"
                />
            </div>
        </div>

        <SectionHeader
            :classes="'bg-secondary-darkBlue'"
            :section="'software'"
            :title="'Latest Software'"
            :button-text="'View all software'"
            :button-callback="() => router.push('/browse/software')"
        />

        <!-- Software Section Here -->
        <div class="flex flex-col gap-6 group/bg h-full relative lg:!flex-row lg:!px-huge xl:py-8">
            <div
                class="
                    -translate-y-1/2
                    md:!block
                    -z-10
                    absolute
                    top-1/2
                    left-1/4
                    duration-700
                    group-hover/bg:left-[15%]
                    group-hover/bg:scale-125
                    hidden
                    opacity-10
                    softwareRobot
                    transition-all
                    "
            >
                <SoftwareRobot />
            </div>
            <div class="flex flex-col max-h-[1000px] pl-8 place-items-center w-full lg:w-[40%]">
                <SoftwareIllustration />
            </div>
            <template v-if="softwaresData">
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
                        v-for="software in softwareResponsiveData"
                        :key="software.guid"
                        :data="software"
                    />
                </div>
            </template>
            <template v-else>
                <div class="px-8 w-full lg:!px-20">
                    <CardLoading
                        :number-of-rows="2"
                        :number-per-row="windowStore.getNumberOfCardLoading"
                    />
                </div>
            </template>
        </div>

        <SectionHeader
            :classes="'bg-main-teal'"
            :section="'events'"
            :title="'New Events'"
            :button-text="'View all events'"
            :button-callback="() => router.push('/browse/event')"
        />
        <CarouselGenerator
            data-type="events"
            :data-array="eventsData? eventsData : []"
        />


        <SectionHeader
            :classes="'bg-main-navy'"
            :section="'schools'"
            :title="'Latest School Profiles'"
            :button-text="'View all schools'"
            :button-callback="() => router.push('/browse/school')"
        />
        <SchoolSectionDashboard v-if="shouldStartSwrv" />
    </div>
</template>


<style>
.InfoSection {
    width: auto !important;
    margin: auto !important;
    padding-right: 2rem !important;
}

.CardSection {
    max-width: fit-content !important;
    margin: auto !important;
}
</style>