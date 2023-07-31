<script setup>
/**
 * Carousel stuff
 */
import 'vue3-carousel/dist/carousel.css';
import {Carousel, Slide, Pagination, Navigation} from 'vue3-carousel';
import {computed, ref} from 'vue';
import {storeToRefs} from 'pinia';
import {useUserStore} from '@/js/stores/useUserStore';
import {useRouter} from 'vue-router';
import {bookmarkURL, imageURL, likeURL} from "@/js/constants/serverUrl";

import GenericCard from './GenericCard.vue';
import CardLoading from '../card/CardLoading.vue';
import AdviceCardIcon from '../advice/AdviceCardIcon.vue';

import SchoolCardIconList from '../schools/SchoolCardIconList.vue';

import InPerson from '../svg/InPerson.vue';
import Virtual from '../svg/Virtual.vue';

import {cardDataHelper} from '../../helpers/cardDataHelper.js';
import Hybrid from "@/js/components/svg/event/Hybrid.vue";

const {currentUser} = storeToRefs(useUserStore());

const router = useRouter();

const props = defineProps({
    cardData: {
        type: Array,
        required: true
    },
    loading: {
        type: Boolean,
        required: false,
        default: false
    },
    loadingState: {
        type: String,
        required: false,
        default: 'SUCCESS'
    },
    rowCount: {
        type: Number,
        required: true
    },
    colCount: {
        type: Number,
        required: true
    },
    sectionType: {
        type: String,
        required: true
    },
    additionalClasses: {
        type: String,
        required: false
    },
    typeTagColor: {
        type: String,
        required: false
    },
    adviceType: {
        type: String,
        required: false
    },
    loadingClasses: {
        type: String,
        required: false
    }
});

const getLikeBookmarkData = (cardData) => {
    return {
        post_id: cardData.id,
        user_id: currentUser.value.id, // to be replaced with userId from userStore
        post_type: props.sectionType
    }
};

const checkingStuff = computed(() => {
    console.log(props.additionalClasses);
    return props.cardData;
});

const getLatestContent = (data) => {
    cardDataHelper(data).sort(function compare(a, b) {
        let dateA = new Date(a.post_date);
        let dateB = new Date(b.post_date);
        return dateA - dateB;
    })
};

const computedCardData = computed(() => {
    if (props.loading) {
        return []
    } else {
        if (props.sectionType === 'advice') {
            let mutatedData = [];

            switch (props.adviceType) {
            case 'DAG':
                mutatedData = props.cardData.filter(data => data.advice_type.includes('DAG advice'));
                break;

            case 'General':
                let classroom = props.cardData.filter(data => data.advice_type.includes('Your Classroom'));
                let work = props.cardData.filter(data => data.advice_type.includes('Your Work'));
                let learning = props.cardData.filter(data => data.advice_type.includes('Your Learning'));

                mutatedData = classroom.concat(work, learning);
                break;

            case 'Partner':
                mutatedData = props.cardData.filter(data => data.advice_type.includes('Partner'));
                break;

            case 'Dashboard':
                mutatedData = props.cardData;
                break;
            default:
                break;
            }

            return cardDataHelper(mutatedData, props.sectionType);

        } else if (props.adviceType === 'Dashboard') {
            return cardDataHelper(props.cardData, props.sectionType);
        } else {
            return cardDataHelper(props.cardData, props.sectionType);
        }
    }
});

const randomIconName = computed(() => {
    const source = ['iconBookLight', 'iconBookStars', 'iconBookSearch']
    return source[Math.floor(Math.random() * source.length)]
});

const showFirstTech = ref(false)
const firstTechId = ref('');
const handleMouseEnterCard = (id) => {
    console.log(id)
    firstTechId.value = id;
    showFirstTech.value = true
}

const handleMouseExitCard = () => {
    firstTechId.value = '';
    showFirstTech.value = false
}

const canShowFirstTech = (id) => {
    if (id === firstTechId.value) {
        return true;
    } else {
        return false
    }
}

const handleClickCard = (item) => {

    let sectionId = '';

    switch (props.sectionType) {
    case 'advice':
        sectionId = 'post_id'
        break;

    case 'software':
        sectionId = 'post_id'
        break;

    case 'schools':
        sectionId = 'id'
        break;
    case 'events':
        sectionId = 'event_id'
        break;
    case 'hardware':
        sectionId = 'id'
        break;

    default:
        break;
    }
    const content = props.cardData.filter(data => data[sectionId] === item.id);

    if (props.sectionType === 'events') {
        router.push({
            name: `event-single`,
            params: {id: item.id},
            state: {content: JSON.stringify(content[0])}
        })
    } else if (props.sectionType === 'schools') {
        router.push({
            name: `school-single`,
            params: {name: item.title},
            state: {content: JSON.stringify(content[0])}
        })
    } else {
        router.push({
            name: `${props.sectionType}-single`,
            params: {id: item.id},
            state: {content: JSON.stringify(content[0])}
        })
    }

}

const generalCarouselBreakpoints =  {
    350: {
        itemsToShow: 1,
        snapAlign: 'start',
    },
    768:{
        itemsToShow: 1.5,
        snapAlign: 'center',
    },
    1024: {
        itemsToShow: 2,
        snapAlign: 'start',
    },
    1400: {
        itemsToShow: 2,
        snapAlign: 'start',
    },
    1600: {
        itemsToShow: 3,
        snapAlign: 'start',
    }
}

const twoThirdCarouselBreakpoints =  {
    350: {
        itemsToShow: 1,
        snapAlign: 'start',
    },
    768:{
        itemsToShow: 1.5,
        snapAlign: 'center',
    },
    1024: {
        itemsToShow: 1,
        snapAlign: 'start',
    },
    1400: {
        itemsToShow: 1.5,
        snapAlign: 'center',
    },
    1600: {
        itemsToShow: 2,
        snapAlign: 'start',
    }
}

const breakpointChoser = () =>{
    if(props.adviceType === 'Dashboard' && props.sectionType === 'advice'){
        return twoThirdCarouselBreakpoints
    } else{
        return generalCarouselBreakpoints
    }
}

console.log(computedCardData.value);
</script>

<template>
    <div class="flex flex-col px-8 py-2 lg:!flex-row lg:!px-huge lg:!py-8">
        <slot name="cardInfoSection" />

        <div
            v-if="!loading && loadingState === 'SUCCESS' || !loading && loadingState === 'VALIDATING'"
            class="carousel__wrapper"
            :class="additionalClasses"
        >
            <Carousel
                :items-to-show="colCount"
                :snap-align="'start'"
                :wrap-around="true"
                :breakpoints="breakpointChoser()"
            >
                <Slide
                    v-for="(slide, index) in computedCardData"
                    :key="slide"
                >
                    <GenericCard
                        v-if="sectionType !== 'schools'"
                        :key="slide.id"
                        :title="slide.title"
                        :item="slide"
                        :display-content="slide.excerpt"
                        :display-author="slide.author"
                        :display-date="sectionType === 'events' ? slide.start_date : slide.created_at"
                        :cover-image="slide.cover_image"
                        :number-per-row="colCount"
                        :like-bookmark-data="getLikeBookmarkData(slide)"
                        :section-type="sectionType"
                        @emitCardClick="handleClickCard"
                    >
                        <!-- For event types -->
                        <template
                            v-if="sectionType === 'events'"
                            #typeTag
                        >
                            <div
                                v-if="slide.type === 'In Person'"
                                class="
                                    TypeTag
                                    absolute
                                    top-4
                                    -right-6
                                    bg-secondary-red
                                    flex
                                    h-[39px]
                                    p-1
                                    place-items-center
                                    px-6
                                    rounded
                                    text-white
                                    gap-4
                                    "
                                :class="typeTagColor"
                            >
                                <InPerson />
                                {{ slide.type }}
                            </div>

                            <div
                                v-else-if="slide.type === 'Virtual'"
                                class="
                                    TypeTag
                                    absolute
                                    top-4
                                    -right-6
                                    bg-secondary-red
                                    flex
                                    h-[39px]
                                    p-1
                                    place-items-center
                                    px-6
                                    rounded
                                    text-white
                                    gap-4
                                    "
                                :class="typeTagColor"
                            >
                                <Virtual />
                                {{ slide.type }}
                            </div>
                            <div
                                v-else-if="slide.type === 'Hybrid'"
                                class="
                                    TypeTag
                                    absolute
                                    top-4
                                    -right-6
                                    bg-secondary-red
                                    flex
                                    h-[39px]
                                    p-1
                                    place-items-center
                                    px-6
                                    rounded
                                    text-white
                                    gap-4
                                    "
                                :class="typeTagColor"
                            >
                                <Hybrid />
                                {{ slide.type }}
                            </div>
                        </template>

                        <!-- For advice types -->
                        <template
                            v-if="sectionType === 'advice'"
                            #typeTag
                        >
                            <div
                                v-if="slide.type === 'DAG advice'"
                                class="
                                    TypeTag
                                    absolute
                                    top-4
                                    -right-6
                                    bg-secondary-yellow
                                    flex
                                    h-[39px]
                                    p-1
                                    place-items-center
                                    px-6
                                    rounded
                                    text-white
                                    gap-4
                                    "
                                :class="typeTagColor"
                            >
                                {{ slide.type }}
                            </div>

                            <div
                                v-else-if="slide.type === 'Your Classroom'"
                                class="
                                    TypeTag
                                    absolute
                                    top-4
                                    -right-6
                                    bg-secondary-green
                                    flex
                                    h-[39px]
                                    p-1
                                    place-items-center
                                    px-6
                                    rounded
                                    text-white
                                    gap-4
                                    "
                                :class="typeTagColor"
                            >
                                {{ slide.type }}
                            </div>

                            <div
                                v-else-if="slide.type === 'Your Work'"
                                class="
                                    TypeTag
                                    absolute
                                    top-4
                                    -right-6
                                    bg-secondary-green
                                    flex
                                    h-[39px]
                                    p-1
                                    place-items-center
                                    px-6
                                    rounded
                                    text-white
                                    gap-4
                                    "
                                :class="typeTagColor"
                            >
                                {{ slide.type }}
                            </div>

                            <div
                                v-else-if="slide.type === 'Your Learning'"
                                class="
                                    TypeTag
                                    absolute
                                    top-4
                                    -right-6
                                    bg-secondary-green
                                    flex
                                    h-[39px]
                                    p-1
                                    place-items-center
                                    px-6
                                    rounded
                                    text-white
                                    gap-4
                                    "
                                :class="typeTagColor"
                            >
                                {{ slide.type }}
                            </div>

                            <div
                                v-if="slide.type === 'Partner'"
                                class="
                                    TypeTag
                                    absolute
                                    top-4
                                    -right-6
                                    bg-secondary-yellow
                                    flex
                                    h-[39px]
                                    p-1
                                    place-items-center
                                    px-6
                                    rounded
                                    text-white
                                    gap-4
                                    "
                                :class="typeTagColor"
                            >
                                {{ slide.type }}
                            </div>
                        </template>

                        <template
                            v-if="sectionType === 'advice'"
                            #icon
                        >
                            <AdviceCardIcon
                                class="absolute right-4 bottom-2 group-hover:-bottom-32 icon transition-all"
                                :advice-icon-name="randomIconName"
                            />
                        </template>
                    </GenericCard>

                    <GenericCard
                        v-else-if="sectionType === 'schools'"
                        :title="slide.title"
                        :like-bookmark-data="getLikeBookmarkData(slide)"
                        :override-content="true"
                        :number-per-row="colCount"
                        :extra-classes="'!w-[300px]'"
                        :section-type="sectionType"
                        :item="slide"
                        @emitCardClick="handleClickCard"
                    >
                        <template #overiddenContent>
                            <div
                                class="flex flex-col h-full"
                                @mouseenter="handleMouseEnterCard(slide.id)"
                                @mouseleave="handleMouseExitCard"
                            >
                                <div
                                    class="
                                        bg-center
                                        bg-cover
                                        bg-white
                                        cardTopCoverImage
                                        group-hover:h-0
                                        group-hover:min-h-[0%]
                                        min-h-[35%]
                                        relative
                                        transition-all
                                        "
                                >
                                    <div
                                        :class="`bg-[url('${imageURL}/${slide.cover_image}')] bg-cover`"
                                        :style="`background-image: url(${imageURL}/${slide.cover_image}) `"
                                        class="group-hover:h-0 h-36 transition-all"
                                    />
                                </div>
                                <div class="px-6 py-4 relative transition-all">
                                    <!-- CARD CONTENT -->
                                    <div class="card-content_title min-h-[72px] transition-all">
                                        <!-- CARD CONTENT HEADER -->
                                        <h5
                                            class="
                                                cardTitle
                                                flex
                                                justify-between
                                                place-items-center
                                                transition-all
                                                "
                                        >
                                            {{ slide.title }}
                                        </h5>
                                    </div>
                                    <div class="card-content_body transition-all">
                                        <p class="font-medium pt-6 text-[18px] text-black">
                                            Tech used:
                                        </p>
                                        <div
                                            class="
                                                cursor-grab
                                                flex
                                                justify-between
                                                items-center
                                                flex-row
                                                iconListContainer
                                                overflow-scroll
                                                overflow-x-auto
                                                pb-6
                                                pt-4
                                                w-full
                                                gap-4
                                                "
                                        >
                                            <!-- :show-first-tech="firstTechId === slide.id ? showFirstTech : !showFirstTech"
                                                    :show-first-tech="showFirstTech" -->
                                            <SchoolCardIconList
                                                :tech-list="slide.tech_used"
                                                :show-first-tech="canShowFirstTech(slide.id)"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </GenericCard>
                </Slide>

                <template #addons>
                    <navigation />
                    <pagination />
                </template>
            </Carousel>
        </div>

        <div
            v-else
            class="carousel__wrapper"
            :class="loadingClasses"
        >
            <CardLoading
                :number-per-row="colCount"
                :number-of-rows="rowCount"
            />
        </div>
    </div>
</template>

<style lang="scss">
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
}
</style>
