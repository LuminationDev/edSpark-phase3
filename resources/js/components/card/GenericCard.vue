<script setup>
import {guid} from "@/js/helpers/guidGenerator";
import {debounce} from "lodash";
import {computed, ref} from "vue";
import {bookmarkURL, imageURL, likeURL} from "@/js/constants/serverUrl";
import Tooltip from "@/js/components/global/Tooltip/Tooltip.vue";

import Like from "@/js/components/svg/Like.vue";
import LikeFull from "@/js/components/svg/LikeFull.vue";
import BookMark from "@/js/components/svg/BookMark.vue";
import BookmarkFull from "@/js/components/svg/BookmarkFull.vue";

import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";

const {currentUser, userLikeList, userBookmarkList} = storeToRefs(useUserStore())


const props = defineProps({
    id: {
        type: [String, Number],
        required: false,
        default: ''
    },
    title: {
        type: String,
        required: true
    },
    displayAuthor: {
        type: [Object, String],
        required: false,
        default: () => {
        }
    },
    displayDate: {
        type: String,
        required: false,
        default: ''
    },
    endDate: {
        type: String,
        required: false,
        default: ''
    },
    displayContent: {
        type: String,
        required: false,
        default: ''
    },
    coverImage: {
        type: String,
        required: false,
        default: ''
    },
    clickCallback: {
        type: Function,
        required: false,
        default: () => {
        }
    },
    overrideContent: {
        type: Boolean,
        required: false,
        default: false
    },
    sectionType: {
        type: String,
        required: false,
        default: ""
    },
    isLikedByUser: {
        type: Boolean,
        required: true,
    },
    isBookmarkedByUser: {
        type: Boolean,
        required: true,
    },
    guid:{
        type: String,
        required: false,
        default: guid()
    }

});
const currentUserLiked = ref(props.isLikedByUser)
const currentUserBookmarked = ref(props.isBookmarkedByUser)

/**
 * Check if both start and end date are provided.
 * return both date with "-" if true
 * else if just return start date
 * else return empty string if invalid
 * @type {ComputedRef<unknown>}
 */
const formattedDate = computed(() => {
    if (props.displayDate && props.endDate) {
        const startDate = new Date(Date.parse(props.displayDate)).toDateString()
        const endDate = new Date(Date.parse(props.endDate)).toDateString()

        // simple comparison for one day event
        if (startDate === endDate) {
            return startDate
        } else {
            return `${startDate} - ${endDate}`

        }
    } else if (props.displayDate) {
        return new Date(Date.parse(props.displayDate)).toDateString()
    } else {
        return ""
    }
})

let likeOrBookmarkPayload = {
    post_id: props.id,
    user_id: currentUser.value.id || 9999,
    post_type: props.sectionType
}

const handleDefaultLike = async () => {
    currentUserLiked.value = !currentUserLiked.value
    console.log(likeOrBookmarkPayload)
    await axios.post(likeURL, likeOrBookmarkPayload).then(res => {
        if (res.data?.status === 200) {
            currentUserLiked.value = res.data.isLiked
        }
    }).catch(err => {
        console.log(err.message)
    })
}

const handleDefaultBookmark = async () => {
    currentUserBookmarked.value = !currentUserBookmarked.value
    console.log(likeOrBookmarkPayload)
    await axios.post(bookmarkURL, likeOrBookmarkPayload).then(res => {
        if (res.data?.status === 200) {
            console.log(res.data)
            currentUserBookmarked.value = res.data.isBookmarked
        }
    }).catch(err => {
        console.log(err.message)
    })
}

const debouncedDefaultLike = debounce(() => {
    handleDefaultLike()
}, 150)

const debouncedDefaultBookmark = debounce(() => {
    handleDefaultBookmark()
}, 150)

const cardHoverToggle = ref(false);

const emits = defineEmits(['emitCardClick']);

</script>

<template>
    <div
        class="GenericCardContainer card_parent generic-card__wrapper group"
        @mouseenter="cardHoverToggle = true"
    >
        <template v-if="!props.overrideContent">
            <div
                class="
                    bg-center
                    bg-cover
                    bg-slate-50
                    cardTopCoverImage
                    group-hover:h-0
                    group-hover:min-h-[0%]
                    min-h-[35%]
                    overflow-visible
                    relative
                    transition-all
                    "
                :class="`bg-[url('${imageURL}/${coverImage}')]`"
                :style="`background-image: url('${imageURL}/${coverImage}')`"
            >
                <div
                    v-if="$slots.typeTag"
                >
                    <slot
                        name="typeTag"
                    />
                </div>

                <div
                    v-if="$slots.icon"
                >
                    <slot name="icon" />
                </div>
            </div>
            <div
                class="cardContent transition-all"
                @click="clickCallback"
            >
                <div class="cardContentWrapper">
                    <div
                        v-if="props.title"
                        class="cardTitle transition-all"
                        :class="{
                            'group-hover:w-3/5': ['advice','events','partners'].includes(sectionType),
                            'group-hover:w-4/5': sectionType === 'software'
                        }"
                    >
                        {{ props.title }}
                    </div>
                    <div class="card-content_body transition-all">
                        <div
                            v-if="props.displayAuthor"
                            class="cardAuthor transition-all"
                        >
                            {{ props.displayAuthor['author_name'] || props.displayAuthor }}
                        </div>
                        <div
                            v-if="props.displayDate"
                            class="cardDate transition-all"
                        >
                            {{ formattedDate }}
                        </div>
                        <div
                            v-if="props.displayContent"
                            class="cardDisplayPreview"
                            v-html="props.displayContent"
                        />
                    </div>
                </div>
            </div>
            <!-- </div> -->
        </template>
        <template v-else>
            <div
                class="schoolCardContentOverridding w-full"
                @click="props.clickCallback"
            >
                <slot name="overiddenContent" />
            </div>
        </template>
        <div class="flex flex-row gap-4 generic-card__footer h-18 mt-auto pl-4 place-items-end">
            <div class="p-2">
                <span class="has-tooltip relative">
                    <LikeFull
                        v-if="currentUserLiked"
                        :key="props.guid"
                        @click="debouncedDefaultLike"
                    />
                    <Like
                        v-else
                        :key="props.guid"
                        @click="debouncedDefaultLike"
                    />
                    <Tooltip
                        tip="Like post"
                        class="absolute w-24"
                    />
                </span>
            </div>
            <div class="p-2">
                <span class="has-tooltip">
                    <BookmarkFull
                        v-if="currentUserBookmarked"
                        :key="props.guid"
                        @click="debouncedDefaultBookmark"
                    />
                    <BookMark
                        v-else
                        :key="props.guid"

                        @click="debouncedDefaultBookmark"
                    />
                    <Tooltip
                        tip="Bookmark post"
                        class="absolute w-36"
                    />
                </span>
            </div>
        </div>
    </div>
</template>


<style scoped>

.generic-card__footer {
    background-color: #FFF;
    box-shadow: 0px -23px 7px -15px rgba(255, 255, 255, 1);
    -webkit-box-shadow: 0px -23px 7px -15px rgba(255, 255, 255, 1);
    -moz-box-shadow: 0px -23px 7px -15px rgba(255, 255, 255, 1);
}

.cardContentWrapper {
    /* -moz-box-shadow: inset 0 -10px 10px -10px #000000;
    -webkit-box-shadow: inset 0 -10px 10px -10px #000000;
    box-shadow: inset 0 -10px 10px -10px #000000; */
}

.card-content_body {
    /* overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 6;
    -webkit-box-orient: vertical; */
}

.card_parent:hover .card-content_body {
    -webkit-line-clamp: 14;
}

.card-content_title {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

/* .card-content_body {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
} */

.card_parent:hover .card-content_title {
    -webkit-line-clamp: 4 !important;
}
</style>
