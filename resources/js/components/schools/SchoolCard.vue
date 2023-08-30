<script setup>
import SchoolCardIconList from "@/js/components/schools/SchoolCardIconList.vue";
import GenericCard from "@/js/components/card/GenericCard.vue";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";
import {ref} from "vue";
import {useRouter} from "vue-router";

const {currentUser} = storeToRefs(useUserStore())
const router = useRouter()
const imageURL = import.meta.env.VITE_SERVER_IMAGE_API
const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    linkTarget: {
        type: String,
        required: false,
        default: ''
    }
})
/**
 * data props: {
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

const handleMouseExitCard = () => {
    showFirstTech.value = false
}
const handleClickSchoolCard = () => {
    router.push({
        path: `/schools/${props.data.name}`
    })
}

</script>
<template>
    <GenericCard
        :id="data.id"
        :key="data.guid"
        :title="data.name"
        :number-per-row="1"
        :override-content="true"
        :click-callback="handleClickSchoolCard"
        :is-liked-by-user="data.isLikedByUser"
        :is-bookmarked-by-user="data.isBookmarkedByUser"
        :section-type="'school'"
        :guid="data.guid"
    >
        <template #overiddenContent>
            <div
                class="flex flex-col h-full"
                @mouseenter="handleMouseEnterCard"
                @mouseleave="handleMouseExitCard"
            >
                <div class="group-hover:h-0 h-36 relative transition-all">
                    <div
                        :class="`bg-[url('${imageURL}/${data.cover_image}')] bg-cover`"
                        :style="`background-image: url(${imageURL}/${data.cover_image}) `"
                        class="group-hover:h-0 h-36 transition-all"
                    />
                </div>
                <div class="px-6 py-4 relative transition-all">
                    <!-- CARD CONTENT -->
                    <div class="card-content_title group-hover:mr-0 min-h-[72px] transition-all">
                        <!-- CARD CONTENT HEADER -->
                        <h5
                            class="flex justify-start font-medium group-hover:mr-0 text-left text-xl transition-all"
                        >
                            {{ data.name }}
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
                                :tech-list="data.tech_used"
                                :show-first-tech="showFirstTech"
                                :guid="data.guid"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </GenericCard>
</template>
<style scoped>
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
