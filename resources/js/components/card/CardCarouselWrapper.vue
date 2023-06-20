<script setup>
    /**
     * Carousel stuff
     */
    import 'vue3-carousel/dist/carousel.css';
    import {Carousel, Slide, Pagination, Navigation} from 'vue3-carousel';
    import { computed, ref } from 'vue';
    import { storeToRefs } from 'pinia';
    import { useUserStore } from '../../stores/useUserStore';

    import GenericCard from './GenericCard.vue';
    import CardLoading from '../card/CardLoading.vue';
    import AdviceCardIcon from '../advice/AdviceCardIcon.vue';

    import SchoolCardIconList from '../schools/SchoolCardIconList.vue';

    import InPerson from '../svg/InPerson.vue';
    import Virtual from '../svg/Virtual.vue';

    import { cardDataHelper } from '../../helpers/cardDataHelper.js';

    const {currentUser } = storeToRefs(useUserStore())

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
            required: false
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
            required:false
        }
    });

    const getLikeBookmarkData = (cardData) => {
        return {
            post_id: cardData.id,
            user_id: currentUser.value.id, // to be replaced with userId from userStore
            post_type: 'event'
        }
    };

    const checkingStuff = computed(() => {
        console.log(props.additionalClasses);
        return props.cardData;
    });

    const computedCardData = computed(() => {
        if (props.loading) {
            return;
        } else {
            if (props.sectionType === 'advice') {
                let mutatedData = [];

                switch (props.adviceType) {
                    case 'DAG':
                            mutatedData = props.cardData.filter(data => data.advice_type.includes('D.A.G advice'));
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
                    default:
                        break;
                }

                return cardDataHelper(mutatedData, props.sectionType);

            } else {
                return cardDataHelper(props.cardData, props.sectionType);
            }
        }
    });

    const handleClickCard = () => {

    }

    const randomIconName = computed(() => {
        const source = ['iconBookLight', 'iconBookStars', 'iconBookSearch']
        return source[Math.floor(Math.random() * source.length)]
    });

    console.log(computedCardData.value);
</script>

<template>
    <div class="py-8 px-huge flex">
        <slot name="cardInfoSection" />

        <div
            class="carousel__wrapper"
            :class="additionalClasses"
            v-if="!loading || loadingState === 'SUCCESS'"
        >
            <Carousel
                :items-to-show="colCount"
                :snap-align="'start'"
                :wrap-around="true"
            >
                <Slide
                    v-for="(slide, index) in computedCardData"
                    :key="slide"
                >
                    <GenericCard
                        v-if="sectionType !== 'schools'"
                        :key="slide.id"
                        :title="slide.title"
                        :display-content="slide.excerpt"
                        :display-author="slide.author"
                        :display-date="slide.created_at"
                        :cover-image="slide.cover_image"
                        :number-per-row="colCount"
                        :like-bookmark-data="getLikeBookmarkData(slide)"
                        :click-callback="handleClickCard"
                        :section-type="sectionType"
                    >
                        <!-- For event types -->
                        <template
                            #typeTag
                            v-if="sectionType === 'events'"
                        >
                            <div
                                class="TypeTag absolute gap-4 -right-6 top-4 p-1 px-6 h-[39px] place-items-center bg-secondary-red text-white flex rounded"
                                :class="typeTagColor"
                                v-if="slide.type === 'In Person'"
                            >
                                <InPerson />{{ slide.type }}
                            </div>

                            <div
                                class="TypeTag absolute gap-4 -right-6 top-4 p-1 px-6 h-[39px] place-items-center bg-secondary-red text-white flex rounded"
                                :class="typeTagColor"
                                v-else-if="slide.type === 'Virtual'"
                            >
                                <Virtual />{{ slide.type }}
                            </div>
                        </template>

                        <!-- For advice types -->
                        <template
                            #typeTag
                            v-if="sectionType === 'advice'"
                        >
                            <div
                                class="TypeTag absolute gap-4 -right-6 top-4 p-1 px-6 h-[39px] place-items-center bg-secondary-yellow text-white flex rounded"
                                :class="typeTagColor"
                                v-if="slide.type === 'D.A.G advice'"
                            >
                                {{ slide.type }}
                            </div>

                            <div
                                class="TypeTag absolute gap-4 -right-6 top-4 p-1 px-6 h-[39px] place-items-center bg-secondary-green text-white flex rounded"
                                :class="typeTagColor"
                                v-else-if="slide.type === 'Your Classroom'"
                            >
                                {{ slide.type }}
                            </div>

                            <div
                                class="TypeTag absolute gap-4 -right-6 top-4 p-1 px-6 h-[39px] place-items-center bg-secondary-green text-white flex rounded"
                                :class="typeTagColor"
                                v-else-if="slide.type === 'Your Work'"
                            >
                                {{ slide.type }}
                            </div>

                            <div
                                class="TypeTag absolute gap-4 -right-6 top-4 p-1 px-6 h-[39px] place-items-center bg-secondary-green text-white flex rounded"
                                :class="typeTagColor"
                                v-else-if="slide.type === 'Your Learning'"
                            >
                                {{ slide.type }}
                            </div>

                            <div
                                class="TypeTag absolute gap-4 -right-6 top-4 p-1 px-6 h-[39px] place-items-center bg-secondary-yellow text-white flex rounded"
                                :class="typeTagColor"
                                v-if="slide.type === 'Partner'"
                            >
                                {{ slide.type }}
                            </div>
                        </template>

                        <template
                            v-if="sectionType === 'advice'"
                            #icon
                        >
                            <AdviceCardIcon
                                class="icon absolute right-4 bottom-2 transition-all group-hover:-bottom-32"
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
                    >
                        <template #overiddenContent>
                            <div class="h-full flex flex-col ">
                                <router-link :to="`/schools/${slide.title}`">
                                    <div class="cardTopCoverImage relative min-h-[35%] bg-white bg-cover bg-center transition-all group-hover:min-h-[0%] group-hover:h-0">
                                        <div
                                            :class="`bg-[url('${imageURL}/${slide.cover_image}')] bg-cover`"
                                            :style="`background-image: url(${imageURL}/${slide.cover_image}) `"
                                            class="h-36 group-hover:h-0 transition-all"
                                        />
                                    </div>
                                    <div class="px-6 py-4 relative transition-all">
                                        <!-- CARD CONTENT -->
                                        <div class="card-content_title min-h-[72px] transition-all">
                                            <!-- CARD CONTENT HEADER -->
                                            <h5 class="cardTitle transition-all flex justify-between place-items-center ">
                                                {{ slide.title }}
                                            </h5>
                                        </div>
                                        <div class="card-content_body transition-all">
                                            <p class="pt-6 text-black text-[18px] font-medium">
                                                Tech used:
                                            </p>
                                            <div
                                                class="iconListContainer pt-4 flex flex-row w-full justify-between overflow-scroll gap-4 overflow-x-auto items-center pb-6 cursor-grab"
                                            >
                                                <SchoolCardIconList :tech-list="slide.tech_used" />
                                            </div>
                                        </div>
                                    </div>
                                </router-link>
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
            class="carousel__wrapper"
            v-else
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
