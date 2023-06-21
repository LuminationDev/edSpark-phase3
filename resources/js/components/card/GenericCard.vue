<script setup>
import {computed, ref} from "vue";
import {bookmarkURL, imageURL, likeURL} from "@/js/constants/serverUrl";
import Tooltip from "@/js/components/global/Tooltip/Tooltip.vue";

import Like from "@/js/components/svg/Like.vue";
import LikeFull from "@/js/components/svg/LikeFull.vue";
import BookMark from "@/js/components/svg/BookMark.vue";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";
import BookmarkFull from "@/js/components/svg/BookmarkFull.vue";

const {userLikeList, userBookmarkList} = storeToRefs(useUserStore())

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    numberPerRow: {
        type: Number,
        required: false,
        default: 3
    },
    displayAuthor: {
        type: String,
        required: false,
        default: ''
    },
    displayDate: {
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
    likeBookmarkData: {
        type: Object,
        required: false,
        default: () => {
        }
    },
    overrideContent: {
        type: Boolean,
        required: false,
        default: false
    },
    extraClasses: {
        type: String,
        required: false
    },
    sectionType: {
        type: String,
        required: false
    },
    item: {
        type: Object,
        required: true
    }
});

const currentUserLiked = computed(() => {
    if (userLikeList.value[props.likeBookmarkData.post_type] &&
        userLikeList.value[props.likeBookmarkData.post_type].length > 0 &&
        userLikeList.value[props.likeBookmarkData.post_type].filter(eachItem => {
            if (eachItem == props.likeBookmarkData.post_id) return true
        }).length > 0) {
        return true
    } else {
        return false
    }
})

const currentUserBookmarked = computed(() => {
    if (userBookmarkList.value[props.likeBookmarkData.post_type] &&
        userBookmarkList.value[props.likeBookmarkData.post_type].length > 0 &&
        userBookmarkList.value[props.likeBookmarkData.post_type].filter(eachItem => {
            if (eachItem == props.likeBookmarkData.post_id) return true
        }).length > 0) {
        return true
    } else {
        return false
    }
})

const formattedDate = computed(() => {
    const date = new Date(Date.parse(props.displayDate))
    return date.toDateString()
})

const handleDefaultLike = async (data) => {
    const {post_type} = props.likeBookmarkData
    await axios.post(likeURL, data)
        .then(res => {
            if (res.data.isLiked) {
                if (!userLikeList.value[post_type]) {
                    userLikeList.value[post_type] = []
                }
                userLikeList.value[post_type].push(data.post_id)
            } else {
                if (userLikeList.value[post_type]) {
                    const indexRemoval = userLikeList.value[post_type].indexOf(data.post_id)
                    if (indexRemoval !== -1) {
                        userLikeList.value[post_type].splice(indexRemoval, 1)
                    } else {
                        console.log('tried to unlike something that doesnt exist in the database')
                    }
                }
            }
        })
        .catch(err => {
            console.log(err);
        })
}

const handleDefaultBookmark = async (data) => {
    const {post_type} = props.likeBookmarkData
    await axios.post(bookmarkURL, data)
        .then(res => {
            if (res.data.isBookmarked) {
                if (!userBookmarkList.value[post_type]) {
                    userBookmarkList.value[post_type] = []
                }
                userBookmarkList.value[post_type].push(data.post_id)
            } else {
                if (userBookmarkList.value[post_type]) {
                    const indexRemoval = userBookmarkList.value[post_type].indexOf(data.post_id)
                    if (indexRemoval !== -1) {
                        userBookmarkList.value[post_type].splice(indexRemoval, 1)
                    } else {
                        console.log('tried to unbookmark something that doesnt exist in the database')
                    }
                }
            }
        })
        .catch(err => {
            console.log(err)
        })
}


const cardHoverToggle = ref(false);

const setTheBackground = computed(() => {
    return `${imageURL}/${props.coverImage}`;
});

const emits = defineEmits(['emitCardClick']);

const handleEmitClick = () => {
    console.log('ive clicked in the card');
    emits('emitCardClick', props.item);
}

</script>

<template>
    <div

        class="GenericCardContainer card_parent generic-card__wrapper group"
        :class="extraClasses"
        @mouseenter="cardHoverToggle = true"
        @click="handleEmitClick"
    >
        <template v-if="!props.overrideContent">
            <div
                class="cardTopCoverImage relative min-h-[35%] bg-slate-50 bg-cover bg-center transition-all group-hover:min-h-[0%] group-hover:h-0"
                :class="`bg-[url('${imageURL}/${coverImage}')]`"
                :style="`background-image: url('${imageURL}/${coverImage}')`"
            >
                <template
                    v-if="$slots.typeTag"
                >
                    <slot
                        name="typeTag"
                    />
                </template>

                <div
                    v-if="$slots.icon"
                >
                    <slot name="icon" />
                </div>
            </div>
            <!-- <div class="cardContentOuter"> -->
                <div
                    class="cardContent transition-all"
                    @click="clickCallback"
                >
                    <div
                        v-if="props.title"
                        class="cardTitle transition-all"
                        :class="(sectionType === 'events' || sectionType === 'advice') ? 'group-hover:w-3/4' : ''"
                    >
                        {{ props.title }}
                    </div>
                    <div class="card-content_body transition-all">
                        <div
                            v-if="props.displayAuthor"
                            class="cardAuthor transition-all"
                        >
                            {{ props.displayAuthor }}
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
            <!-- </div> -->
        </template>
        <template v-else>
            <slot name="overiddenContent" />
        </template>
        <div class="generic-card__footer flex flex-row h-18 place-items-end pl-4 gap-4 mt-auto">
            <div class="p-2">
                <span class="has-tooltip">
                    <LikeFull
                        v-if="currentUserLiked"
                        @click="() => handleDefaultLike(props.likeBookmarkData)"
                    />
                    <Like
                        v-else
                        @click="() => handleDefaultLike(props.likeBookmarkData)"
                    />
                    <Tooltip
                        tip="Like this post"
                        :tool-tip-margin="{'!-ml-28' : numberPerRow > 3, '-ml-36' : true}"
                    />
                </span>
            </div>
            <div class="p-2">
                <span class="has-tooltip">
                    <BookmarkFull
                        v-if="currentUserBookmarked"
                        @click="() => handleDefaultBookmark(props.likeBookmarkData)"
                    />
                    <BookMark
                        v-else
                        @click="() => handleDefaultBookmark(props.likeBookmarkData)"
                    />
                    <Tooltip
                        tip="Bookmark this post"
                        tool-tip-margin="ml-8"
                    />
                </span>
            </div>
        </div>
    </div>
</template>


<style scoped>
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

<style>
    .generic-card__footer {
        background: rgb(255,255,255);
        /* background: linear-gradient(0deg, rgba(255,255,255,1) 0%, rgba(255,255,255,1) 70%, rgba(255,255,255,0.8491771708683473) 84%, rgba(255,255,255,0.7511379551820728) 92%, rgba(255,255,255,0.40948879551820727) 100%, rgba(255,255,255,0) 100%); */
    }

    .cardContent {
        box-shadow: 0px -109px 54px -65px rgba(255,255,255,0.87) inset;
        -webkit-box-shadow: 0px -109px 54px -65px rgba(255,255,255,0.87) inset;
        -moz-box-shadow: 0px -109px 54px -65px rgba(255,255,255,0.87) inset;
    }
</style>
