<script setup>
import ContentSection from "@/js/components/global/ContentSection.vue";
import SchoolCardIconList from "@/js/components/schools/SchoolCardIconList.vue";
import GenericCard from "@/js/components/card/GenericCard.vue";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";
import {ref} from "vue";

const {currentUser} = storeToRefs(useUserStore())

const imageURL = import.meta.env.VITE_SERVER_IMAGE_API
const props = defineProps({
    schoolData: {
        type: Object,
        required: true
    },
    linkTarget: {
        type: String,
        required: false,
        default: ''
    }
})
const likeBookmarkData = {
    post_id: props.schoolData.id,
    user_id: currentUser.value.id,
    post_type: 'school'
}
/**
 * SchoolData props: {
 *     content_blocks: Object,
 *     cover_image: string (link),
 *     id: number,
 *     logo: string(link),
 *     owner : Object {
 *         owner_id : number,
 *         owner_name: string
 *     }
 *     pedagogical_approaches: {
 *         Object -- EditorJs
 *     },
 *     site: Object,
 *     tech_landscape: Object,
 *     tech_used: Array<Object>
 *
 * }
 */
const showFirstTech = ref(false)
const handleMouseEnterCard = () => {
    showFirstTech.value = true
}

const handleMouseExitCard = () =>{
    showFirstTech.value = false
}
</script>
<template>
    <GenericCard
        :title="props.schoolData.name"
        :like-bookmark-data="likeBookmarkData"
        :number-per-row="1"
        :override-content="true"
    >
        <template #overiddenContent>
            <div
                class="flex flex-col h-full"
                @mouseenter="handleMouseEnterCard"
                @mouseleave="handleMouseExitCard"
            >
                <router-link :to="`/schools/${props.schoolData.name}`">
                    <div class="group-hover:h-0 h-36 relative transition-all">
                        <div
                            :class="`bg-[url('${imageURL}/${props.schoolData.cover_image}')] bg-cover`"
                            :style="`background-image: url(${imageURL}/${props.schoolData.cover_image}) `"
                            class="group-hover:h-0 h-36 transition-all"
                        />
                    </div>
                    <div class="px-6 py-4 relative transition-all">
                        <!-- CARD CONTENT -->
                        <div class="card-content_title group-hover:mr-24 min-h-[72px] transition-all">
                            <!-- CARD CONTENT HEADER -->
                            <h5
                                class="
                                    flex
                                    justify-between
                                    font-medium
                                    group-hover:mr-8
                                    place-items-center
                                    text-xl
                                    transition-all
                                    "
                            >
                                {{ props.schoolData.name }}
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
                                    justify-start
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
                                <span />
                                <SchoolCardIconList
                                    :tech-list="props.schoolData.tech_used"
                                    :show-first-tech="showFirstTech"
                                />
                            </div>
                        </div>
                    </div>
                </router-link>
            </div>
        </template>
    </GenericCard>
</template>
<style>
.iconListContainer::-webkit-scrollbar {
    display: none;
}

.iconListContainer {
    -ms-overflow-style: none;
    scrollbar-width: none;
    padding-right: 20px;
    padding-left: 0px;

}

/* Much potentialo */
.iconListContainer:after {
    content: '';
    position: absolute;
    right: 0;
    bottom: 0;
    background-image: linear-gradient(270deg, #fff 50%, 80%, transparent);
    width: 15%;
    height: 8em;
}

.iconListContainer span:after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    background-image: linear-gradient(90deg, #fff 50%, 80%, transparent);
    width: 15%;
    height: 6em;
}

.card-content_body {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 6;
    -webkit-box-orient: vertical;
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

.card-content_body {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
}

.card_parent:hover .card-content_title {
    -webkit-line-clamp: 4 !important;
}
</style>
