<script setup>
import ContentSection from "@/js/components/global/ContentSection.vue";
import SchoolCardIconList from "@/js/components/schools/SchoolCardIconList.vue";
import GenericCard from "@/js/components/card/GenericCard.vue";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";

const { currentUser } = storeToRefs(useUserStore())

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

</script>
<template>
    <GenericCard
        :title="props.schoolData.name"
        :like-bookmark-data="likeBookmarkData"
        :number-per-row="1"
        :override-content="true"
    >
        <template #overiddenContent>
            <div class="h-full flex flex-col ">
                <router-link :to="`/schools/${props.schoolData.name}`">
                    <div class="relative h-36 group-hover:h-0 transition-all">
                        <div
                            :class="`bg-[url('${imageURL}/${props.schoolData.cover_image}')] bg-cover`"
                            :style="`background-image: url(${imageURL}/${props.schoolData.cover_image}) `"
                            class="h-36 group-hover:h-0 transition-all"
                        />
                    </div>
                    <div class="px-6 py-4 relative transition-all">
                        <!-- CARD CONTENT -->
                        <div class="card-content_title min-h-[72px] transition-all group-hover:mr-24">
                            <!-- CARD CONTENT HEADER -->
                            <h5 class="text-xl font-medium transition-all flex justify-between place-items-center group-hover:mr-8">
                                {{ props.schoolData.name }}
                            </h5>
                        </div>
                        <div class="card-content_body transition-all">
                            <p class="pt-6 text-black text-[18px] font-medium">
                                Tech used:
                            </p>
                            <div
                                class="iconListContainer pt-4 flex flex-row w-full justify-between overflow-scroll gap-4 overflow-x-auto items-center pb-6 cursor-grab"
                            >
                                <SchoolCardIconList :tech-list="props.schoolData.tech_used" />
                            </div>
                        </div>
                    </div>
                </router-link>
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
