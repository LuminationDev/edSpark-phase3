<script setup>
    /**
     * Card wrapper stuff
     */

    import { computed, ref } from 'vue';
    import { storeToRefs } from 'pinia';
    import { useUserStore } from '../../stores/useUserStore';

    import GenericCard from './GenericCard.vue';
    import CardLoading from './CardLoading.vue';
    import SoftwareCardIcon from '../software/SoftwareCardIcon.vue';

    import { cardDataHelper } from '../../helpers/cardDataHelper';

    const { currentUser } = storeToRefs(useUserStore());

    const props = defineProps({
        cardData: {
            type: Array,
            required: true
        },
        loading: {
            type: Boolean,
            required: true
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
        hasInfoSection: {
            type: Boolean,
            required: false,
            default: false
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
</script>

<template>
    <div class="">
        <slot name="biggerInfoSection" />

        <div
            class="card__wrapper"
            :class="additionalClasses + hasInfoSection ? 'w-[66.66%]' : ''"
            v-if="!loading"
        >
            <div
                class="flex flex-row flex-1 flex-wrap w-full justify-between"
            >
                <div
                    v-for="(col, index) in (props.rowCount + props.colCount)"
                    class="!max-w-[400px] w-[400px] mb-[42px]"
                >
                    <GenericCard
                        :key="computedCardData[index].id"
                        :title="computedCardData[index].title"
                        :display-content="computedCardData[index].content"
                        :display-author="computedCardData[index].author"
                        :display-date="computedCardData[index].created_at"
                        :cover-image="computedCardData[index].cover_image"
                        :number-per-row="2"
                        :like-bookmark-data="getLikeBookmarkData(computedCardData[index])"
                        :click-callback="handleClickCard"
                        :extra-classes="'!max-w-[400px] w-[400px] '"
                    >
                        <template #icon>
                            <SoftwareCardIcon
                                class="icon absolute -top-6 -right-6 "
                                :software-icon-name="computedCardData[index].type"
                            />
                        </template>
                    </GenericCard>
                </div>
            </div>

        </div>
        <div v-else>
            <CardLoading
                :number-of-rows="rowCount"
                :number-per-row="colCount"
            />
        </div>
    </div>
</template>
