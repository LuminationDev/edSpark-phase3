<script setup lang="ts">
import axios from "axios";
import { debounce } from "lodash";
import { storeToRefs } from "pinia";
import { computed, ComputedRef, defineProps, Ref, ref } from "vue";

import BookMark from "@/js/components/svg/BookMark.vue";
import BookmarkFull from "@/js/components/svg/BookmarkFull.vue";
import Like from "@/js/components/svg/Like.vue";
import LikeFull from "@/js/components/svg/LikeFull.vue";
import ShareIcon from "@/js/components/svg/ShareIcon.vue";
import { bookmarkURL, imageURL, likeURL } from "@/js/constants/serverUrl";
import { cardLinkGenerator } from "@/js/helpers/cardDataHelper";
import { useUserStore } from "@/js/stores/useUserStore";

const { currentUser } = storeToRefs(useUserStore())
import purify from "dompurify";
import {Tippy} from "vue-tippy";
import { toast } from "vue3-toastify";

import { guid as genGuid } from "@/js/helpers/guidGenerator";


const props = defineProps({
    id: {
        type: [String, Number],
        required: false,
        default: ''
    },
    title: {
        type: String,
        required: false,
        default:""
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
    guid: {
        type: String,
        required: false,
        default: genGuid()
    }

});
const currentUserLiked: Ref<boolean> = ref(props.isLikedByUser);
const currentUserBookmarked: Ref<boolean> = ref(props.isBookmarkedByUser);
const shareTippyMessage: Ref<string> = ref('Copy link');



const stripHTML = (value) => {
    const div = document.createElement('div');
    div.innerHTML = value;
    return div.textContent;
};



/**
 * Check if both start and end date are provided.
 * return both date with "-" if true
 * else if just return start date
 * else return empty string if invalid
 * @type {ComputedRef<unknown>}
 */
const formattedDate: ComputedRef<string> = computed(() => {
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

const likeOrBookmarkPayload = {
    post_id: props.id,
    user_id: currentUser.value.id || 9999,
    post_type: props.sectionType
}

const handleDefaultLike = async () => {
    currentUserLiked.value = !currentUserLiked.value
    console.log(likeOrBookmarkPayload)
    axios.post(likeURL, likeOrBookmarkPayload).then(res => {
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
    axios.post(bookmarkURL, likeOrBookmarkPayload).then(res => {
        if (res.data?.status === 200) {
            console.log(res.data)
            currentUserBookmarked.value = res.data.isBookmarked
        }
    }).catch(err => {
        console.log(err.message)
    })
}

const handleClickShare = (): void => {
    const link = cardLinkGenerator(props.sectionType, props.title, +props.id ?? null)
    console.log(link)
    if (link) {
        navigator.clipboard.writeText(link)
        shareTippyMessage.value = 'Link copied to clipboard!'
        toast.success('Copied link to clipboard!')
        setTimeout(() => {
            shareTippyMessage.value = 'Copy link'
        }, 1500)

    } else {
        toast.error('Failed to copy link. Please try again later')
    }
}

// function parentWidth(elem) {
//     return elem.parentNode.clientWidth;
// }
// parentWidth(document.getElementById('typehead'));

const cardFlexDirection = computed(() => {
    if (props.sectionType == 'school') {
        return 'flex-col items-center';
    } else {
        return 'flex-row items-end';
    }
})


const handleResetTippyMessage = (): void => {
    shareTippyMessage.value = 'Copy link'
}

const debouncedDefaultLike = debounce(() => {
    handleDefaultLike()
}, 150)

const debouncedDefaultBookmark = debounce(() => {
    handleDefaultBookmark()
}, 150)

const cardHoverToggle: Ref<boolean> = ref(false);


// group-hover:brightness-50
// group-hover:min-h-[15%]

</script>

<template>
    <div
        class="!border-slate-300 GenericCardContainer card_parent generic-card__wrapper group overflow-hidden rounded"
        :class="props.sectionType"
        @mouseenter="cardHoverToggle = true"
    >
        <template v-if="!props.overrideContent">
            <div
                class="
                    bg-center
                    bg-cover
                    cardTopCoverImage
                    filter
                    group-hover:brightness-75
                    min-h-[35%]
                    overflow-visible
                    relative
                    rounded-t
                    z-0
                    "
                :style="`background-image: url('${imageURL}/${coverImage}');`"
            />
            <div
                class="cardContent group-hover:-mt-[120px] m-0 p-0 z-10"
                @click="clickCallback"
            >
                <div class="bg-white cardContentWrapper group-hover:h-[315px] h-[210px] p-6">
                    <div class="flex items-center flex-row gap-4 mb-3 relative">
                        <div v-if="$slots.icon">
                            <slot name="icon" />
                        </div>

                        <div
                            v-if="props.title"
                            class="!mb-0 cardTitle title-line-clamp"
                        >
                            {{ props.title }}
                        </div>
                    </div>
                    <div class="card-content_body">
                        <div
                            v-if="props.displayAuthor"
                            class="cardAuthor"
                        >
                            {{ props.displayAuthor['author_name'] || props.displayAuthor }}
                        </div>
                        <div
                            v-if="props.displayDate"
                            class="cardDate"
                        >
                            {{ formattedDate }}
                        </div>
                        <div
                            v-if="props.displayContent"
                            class="cardDisplayPreview line-clamp"
                            v-html="stripHTML(props.displayContent)"
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


        <div
            class="bg-white cardFooter flex justify-between left-0 w-full"
            :class="cardFlexDirection"
        >
            <div v-if="$slots.typeTag">
                <slot name="typeTag" />
            </div>

            <div
                class="
                    bg-white
                    flex
                    justify-end
                    justify-items-end
                    flex-row
                    generic-card__footer
                    group-hover:h-14
                    h-0
                    ml-auto
                    mt-auto
                    pl-4
                    transition-all
                    transition-height
                    "
            >
                <tippy content="Like">
                    <div
                        class="bg-white like-share m-1 mb-2 rounded hover:cursor-pointer"
                        @click="debouncedDefaultLike"
                    >
                        <LikeFull
                            v-if="currentUserLiked"
                            :key="props.guid"
                        />
                        <Like
                            v-else
                            :key="props.guid"
                        />
                    </div>
                </tippy>
                <tippy content="Bookmark">
                    <div
                        class="bg-white like-share m-1 mb-2 rounded hover:cursor-pointer"
                        @click="debouncedDefaultBookmark"
                    >
                        <BookmarkFull
                            v-if="currentUserBookmarked"
                            :key="props.guid"
                        />
                        <BookMark
                            v-else
                            :key="props.guid"
                        />
                    </div>
                </tippy>
                <tippy :content="shareTippyMessage">
                    <div
                        class="bg-white like-share m-1 mb-2 mr-2 rounded hover:cursor-pointer"
                        @click="handleClickShare"
                    >
                        <ShareIcon />
                    </div>
                </tippy>
            </div>
        </div>
    </div>
</template>

                    <!-- @mouseleave="handleResetTippyMessage"
                    @mouseenter="handleResetTippyMessage"  -->

<style>
/* body {
  background: url('https://images.unsplash.com/photo-1531315630201-bb15abeb1653?w=500') center no-repeat;
  background-size: cover;
} */

*,
*:before,
*:after {
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}

@media (max-width: 1279px) {

    .EduAdviceCards .cardFooter {
        flex-direction: column;
        align-items: center;
    }

}


.fadebg {
    z-index: 1;
    background-color: white;
    box-shadow: 0px -5px 10px 5px rgba(255, 255, 255, 1);
    -webkit-box-shadow: 0px -5px 10px 5px rgba(255, 255, 255, 1);
    -moz-box-shadow: 0px -5px 10px 5px rgba(255, 255, 255, 1);
}


.cardTitle {
    height: fit-content;
}

.like-share {
    opacity: 0;
    transition: 0.3s;
    z-index: 1;
    border: 1px solid #95C1C5;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    width: 44px;
    height: 44px;
    padding-top: 12px
}

.like-share svg {
    width: 20px;
    height: 20px;
    overflow: visible;
    margin: auto;
}

.GenericCardContainer:hover .like-share {
    display: block;
    opacity: 1;
    transition: 0.3s;
}

.like-share:hover path,
.like-share:hover svg {
    stroke-width: 2px;
}
.GenericCardContainer .title-line-clamp{
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.GenericCardContainer .line-clamp {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    height: 3.2lh;
    -webkit-box-orient: vertical;
    margin: 0 auto;
}


.GenericCardContainer:hover .line-clamp {
    -webkit-line-clamp: 6;
    height: 6.2lh;
}


.generic-card__footer {
    /* background-color: #FFF;
    box-shadow: 0px -23px 7px -15px rgba(255, 255, 255, 1);
    -webkit-box-shadow: 0px -23px 7px -15px rgba(255, 255, 255, 1);
    -moz-box-shadow: 0px -23px 7px -15px rgba(255, 255, 255, 1); */
}

.generic-card__footer div {
    /* border: 1px solid #cbcaca; */
    /* transition: all; */
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

/* p:after {
  content: "\A";
  white-space:pre;
} */

.card-content_title {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

/* .card_parent:hover .card-content_title {
    -webkit-line-clamp: 4 !important;
} */
</style>
