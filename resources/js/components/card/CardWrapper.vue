<script setup>
/**
 * Card wrapper stuff
 */

import {computed, ref} from 'vue';
import {useRouter} from 'vue-router';
import {storeToRefs} from 'pinia';
import {useUserStore} from '../../stores/useUserStore';

import GenericCard from './GenericCard.vue';
import CardLoading from './CardLoading.vue';
import SoftwareCardIcon from '../software/SoftwareCardIcon.vue';

import {cardDataHelper} from '../../helpers/cardDataHelper';

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
    adviceType: {
        type: String,
        required: false
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

// const checkingStuff = computed(() => {
//     console.log(props.additionalClasses);
//     return props.cardData;
// });

const getLatestContent = (data) => {
    data.sort(function compare(a, b) {
        let dateA = new Date(a.post_date);
        let dateB = new Date(b.post_date);
        return dateA - dateB;
    })
};

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

            case 'Dashboard':
                mutatedData = getLatestContent(props.cardData);
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

    router.push({
        name: `${props.sectionType}-single`,
        params: {id: item.id},
        state: {content: JSON.stringify(content[0])}
    })
};
</script>

<template>
    <div class="">
        <slot name="biggerInfoSection" />
        <div
            v-if="!loading && loadingState === 'SUCCESS' || !loading && loadingState === 'VALIDATING' || (typeof loadingState === 'boolean' && !loadingState)"
            class="card__wrapper"
            :class="additionalClasses + hasInfoSection ? '' : ''"
        >
            <div
                class="flex flex-row flex-1 flex-wrap w-full justify-between px-[29px]"
            >
                <div
                    v-for="(col, index) in (props.rowCount * props.colCount)"
                    class="!max-w-[400px] w-[400px] mb-[42px]"
                    :class="additionalClasses"
                >
                    <GenericCard
                        v-if="computedCardData"
                        :key="computedCardData[index].id"
                        :item="computedCardData[index]"
                        :title="computedCardData[index].title"
                        :display-content="computedCardData[index].excerpt"
                        :display-author="computedCardData[index].author"
                        :display-date="computedCardData[index].created_at"
                        :cover-image="computedCardData[index].cover_image"
                        :number-per-row="2"
                        :like-bookmark-data="getLikeBookmarkData(computedCardData[index])"
                        :extra-classes="additionalClasses ? additionalClasses : '!max-w-[400px] w-[400px]'"
                        :section-type="sectionType"
                        @emit-card-click="handleClickCard"
                    >
                        <template
                            v-if="sectionType === 'advice'"
                            #typeTag
                        >
                            <div
                                v-if="computedCardData[index].type === 'D.A.G advice'"
                                class="TypeTag absolute gap-4 -right-6 top-4 p-1 px-6 h-[39px] place-items-center bg-secondary-yellow text-white flex rounded"
                                :class="typeTagColor"
                            >
                                {{ computedCardData[index].type }}
                            </div>

                            <div
                                v-else-if="computedCardData[index].type === 'Your Classroom'"
                                class="TypeTag absolute gap-4 -right-6 top-4 p-1 px-6 h-[39px] place-items-center bg-secondary-green text-white flex rounded"
                                :class="typeTagColor"
                            >
                                {{ computedCardData[index].type }}
                            </div>

                            <div
                                v-else-if="computedCardData[index].type === 'Your Work'"
                                class="TypeTag absolute gap-4 -right-6 top-4 p-1 px-6 h-[39px] place-items-center bg-secondary-green text-white flex rounded"
                                :class="typeTagColor"
                            >
                                {{ computedCardData[index].type }}
                            </div>

                            <div
                                v-else-if="computedCardData[index].type === 'Your Learning'"
                                class="TypeTag absolute gap-4 -right-6 top-4 p-1 px-6 h-[39px] place-items-center bg-secondary-green text-white flex rounded"
                                :class="typeTagColor"
                            >
                                {{ computedCardData[index].type }}
                            </div>

                            <div
                                v-if="computedCardData[index].type === 'Partner'"
                                class="TypeTag absolute gap-4 -right-6 top-4 p-1 px-6 h-[39px] place-items-center bg-secondary-yellow text-white flex rounded"
                                :class="typeTagColor"
                            >
                                {{ computedCardData[index].type }}
                            </div>
                        </template>

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
