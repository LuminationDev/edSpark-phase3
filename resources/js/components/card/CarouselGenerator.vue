<script setup>
import {ref, computed} from 'vue'
import 'vue3-carousel/dist/carousel.css';
import {Carousel, Slide, Pagination, Navigation} from 'vue3-carousel';
import {
    generalCarouselBreakpoints,
    schoolCarouselBreakpoints,
    twoThirdCarouselBreakpoints
} from "@/js/constants/carouselBreakpoints";
import {cardDataHelper, cardDataWithGuid} from "@/js/helpers/cardDataHelper";
import SchoolCard from "@/js/components/schools/SchoolCard.vue";
import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import HardwareCard from "@/js/components/hardware/HardwareCard.vue";
import PartnerCard from "@/js/components/partners/PartnerCard.vue";
import EventsCard from "@/js/components/events/EventsCard.vue";
import CardLoading from "@/js/components/card/CardLoading.vue";
import {useWindowStore} from "@/js/stores/useWindowStore";


const props = defineProps({
    dataArray: {
        type: Array,
        required: true
    },
    dataType: {
        type: String,
        required: true
    },
    showCount:{
        type: Number,
        required: true
    },
    specialAttribute:{
        type: String,
        required: false,
        default: ''
    }

})
const windowStore = useWindowStore()

const breakpointChoser = () =>{
    if(props.specialAttribute === 'twoThirdWide'){
        return twoThirdCarouselBreakpoints
    } else if(props.dataType === 'school'){
        return schoolCarouselBreakpoints
    }
    else{
        return generalCarouselBreakpoints
    }
}
// currently not uniform yet. still depending on each card (each card is adapted to responses from backend)
// TODO: Configure backend responses and Card data at the same time to prevent breaking changes
const formattedData = computed(() => {
    return cardDataWithGuid(props.dataArray)
})

const numberOfLoadingPlaceholder = computed(() =>{
    if(props.specialAttribute === 'twoThirdWide'){
        return windowStore.getNumberOfCardLoading - 1
    } else{
        return windowStore.getNumberOfCardLoading
    }
})
</script>

<template>
    <div
        class="flex flex-col px-8 py-2 lg:!flex-row lg:!py-8"
        :class="{'lg:!px-huge': props.specialAttribute !== 'twoThirdWide'}"
    >
        <div
            v-if="props.dataArray && props.dataArray.length > 0"
            class="carousel__wrapper"
        >
            <Carousel
                :items-to-show="props.showCount"
                :snap-align="'start'"
                :wrap-around="false"
                :breakpoints="breakpointChoser()"
            >
                <Slide
                    v-for="cardData in formattedData"
                    :key="cardData.guid"
                    class="overflow-visible"
                >
                    <template v-if="props.dataType === 'school'">
                        <SchoolCard :school-data="cardData" />
                    </template>

                    <template v-else-if="props.dataType === 'advice'">
                        <AdviceCard
                            :advice-content="cardData"
                            :show-icon="true"
                        />
                    </template>

                    <template v-else-if="props.dataType === 'sofware'">
                        <SoftwareCard :software="cardData" />
                    </template>

                    <template v-else-if="props.dataType === 'hardware'">
                        <HardwareCard :hardware-content="cardData" />
                    </template>

                    <template v-else-if="props.dataType === 'partners'">
                        <PartnerCard :partner-content="cardData" />
                    </template>

                    <template v-else-if="props.dataType === 'events'">
                        <EventsCard :event-content="cardData" />
                    </template>
                </Slide>
                <template #addons>
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

.carousel__wrapper {
    width: 100%;

    .carousel__viewport {
        padding-bottom: 36px;
    }

    .carousel__next {
        right: -20px !important;
    }

    .carousel__prev {
        left: -20px !important;
    }

    :deep(.carousel__pagination-button::after) {
        height: 12px;
        border-radius: 50%;
    }
}
</style>