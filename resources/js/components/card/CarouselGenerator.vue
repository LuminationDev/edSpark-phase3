<script setup>
import 'vue3-carousel/dist/carousel.css';

import {computed} from 'vue'
import {Carousel, Navigation, Pagination, Slide} from 'vue3-carousel';

import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import CardLoading from "@/js/components/card/CardLoading.vue";
import EventsCard from "@/js/components/events/EventsCard.vue";
import HardwareCard from "@/js/components/hardware/HardwareCard.vue";
import PartnerCard from "@/js/components/partners/PartnerCard.vue";
import SchoolCard from "@/js/components/schools/SchoolCard.vue";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import {
    generalCarouselBreakpoints,
    schoolCarouselBreakpoints,
    twoThirdCarouselBreakpoints
} from "@/js/constants/carouselBreakpoints";
import {useWindowStore} from "@/js/stores/useWindowStore";

const windowStore = useWindowStore()

const props = defineProps({
    dataArray: {
        type: Array,
        required: true
    },
    dataType: {
        type: String,
        required: true
    },
    specialAttribute: {
        type: String,
        required: false,
        default: ''
    }
})

const breakpointChoser = () => {
    if (props.specialAttribute === 'twoThirdWide') {
        return twoThirdCarouselBreakpoints
    } else if (props.dataType === 'school') {
        return schoolCarouselBreakpoints
    } else {
        return generalCarouselBreakpoints
    }
}

const numberOfLoadingPlaceholder = computed(() => {
    if (props.specialAttribute === 'twoThirdWide') {
        return windowStore.getNumberOfCardLoading - 1
    } else {
        return windowStore.getNumberOfCardLoading
    }
})
// TODO :: Process what card to return from the props.dataArray
// Can be latest or random in certain scenario
const controlledDataArray = computed(() => {
    if (props.dataArray && props.dataArray.length > 5) {
        if (props.specialAttribute === 'twoThirdWide') {
            return props.dataArray.slice(0, 4)
        } else {
            return props.dataArray.slice(0, 5)
        }
    } else {
        return props.dataArray
    }
})


</script>


<template>
    <div
        class="flex flex-col px-4 py-2 lg:!flex-row lg:!py-8"
        :class="{'lg:!px-huge': props.specialAttribute !== 'twoThirdWide'}"
    >
        <div
            v-if="controlledDataArray && controlledDataArray.length > 0"
            class="carousel__wrapper"
        >
            <Carousel
                :snap-align="'start'"
                :wrap-around="false"
                :breakpoints="breakpointChoser()"
            >
                <Slide
                    v-for="cardData in controlledDataArray"
                    :key="cardData.guid"
                    class="overflow-visible"
                >
                    <template v-if="props.dataType === 'school'">
                        <SchoolCard
                            :key="cardData.guid"
                            :data="cardData"
                        />
                    </template>

                    <template v-else-if="props.dataType === 'advice'">
                        <AdviceCard
                            :key="cardData.guid"
                            :data="cardData"
                            :show-icon="true"
                        />
                    </template>

                    <template v-else-if="props.dataType === 'software'">
                        <SoftwareCard
                            :key="cardData.guid"
                            :data="cardData"
                            :show-icon="false"
                        />
                    </template>

                    <template v-else-if="props.dataType === 'hardware'">
                        <HardwareCard
                            :key="cardData.guid"
                            :data="cardData"
                        />
                    </template>

                    <template v-else-if="props.dataType === 'partners'">
                        <PartnerCard
                            :key="cardData.guid"
                            :data="cardData"
                        />
                    </template>

                    <template v-else-if="props.dataType === 'events'">
                        <EventsCard
                            :key="cardData.guid"
                            :data="cardData"
                        />
                    </template>
                </Slide>
                <template
                    v-if="props.dataArray.length > 3"
                    #addons
                >
                    <navigation />
                    <pagination />
                </template>
            </Carousel>
        </div>
        <div
            v-else
            class="flex justify-center items-center w-full"
        >
            <CardLoading
                :number-per-row="numberOfLoadingPlaceholder"
                :number-of-rows="1"
            />
        </div>
    </div>
</template>
<style scoped lang="scss">
.iconListContainer::-webkit-scrollbar {
    display: none;
}

.iconListContainer {
    -ms-overflow-style: none;
    scrollbar-width: none;
}


.carousel__pagination-button::after,
:deep(.carousel__pagination-button::after) {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

@media screen and (max-width: 400px) {
    .carousel__wrapper {
        max-width: 100%;
        min-width: auto !important;
    }

}

@media screen and (max-width: 480px) {

    :deep(.carousel__next) {
        right: -20px !important;
        background-color: white;
    }

    :deep(.carousel__prev) {
        left: -20px !important;
        background-color: white;
    }
}

.carousel__wrapper {
    width: 100%;
    min-width: 360px;

    :deep(.carousel__viewport) {
        padding-bottom: 36px;
        overflow-x: hidden;
        overflow-y: visible;
    }

    :deep(.carousel__pagination-button::after) {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    :deep(.carousel__pagination) {
        margin-top: 20px
    }

    :deep(.carousel__next) {
        border: 1px solid gray;
        border-radius: 100%;
        background-color: white;
        opacity: 1;
    }

    :deep(.carousel__prev) {
        border: 1px solid gray;
        border-radius: 100%;
        background-color: white;
        opacity: 1;
    }

    @media (min-width: 1024px) {
        :deep(.carousel__next) {
            right: -25px;
        }

        :deep(.carousel__prev) {
            left: -25px;
        }
    }


    :deep(.carousel__next--disabled) {
        border: 1px solid lightgray;
    }

    :deep(.carousel__prev--disabled) {
        border: 1px solid lightgray;
    }

    :deep(.carousel__next--disabled > svg) {
        fill: lightgray !important;
    }

    :deep(.carousel__prev--disabled > svg) {
        fill: lightgray !important;
    }

}
</style>