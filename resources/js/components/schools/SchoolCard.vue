<script setup>
import {useRouter} from "vue-router";

import GenericCard from "@/js/components/card/GenericCard.vue";
import SchoolCardIconList from "@/js/components/schools/SchoolCardIconList.vue";

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    showIcon: {
        type: Boolean,
        required: false,
        default: true
    }

})


const router = useRouter()

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
        :title="data.title"
        :display-content="data.excerpt"
        :display-author="data.author"
        :display-date="data.modified_at"
        :cover-image="data.cover_image"
        :click-callback="handleClickSchoolCard"
        :section-type="'school'"
        :is-liked-by-user="data.isLikedByUser"
        :is-bookmarked-by-user="data.isBookmarkedByUser"
        :guid="data.guid"
    >
        <template
            v-if="showIcon"
            #icon
        >
            <div class="card-content_title min-h-[72px] px-2">
                <!-- CARD CONTENT HEADER -->
                <h5
                    class="flex justify-start font-semibold text-2xl text-left"
                >
                    {{ data.name }}
                </h5>
                <div class="cardDisplayPreview line-clamp school-card-body text-left">
                    {{ data.content_blocks.blocks[1].data.text }}
                </div>
            </div>
        </template>
        <template
            v-if="data.name.length > 0"
            #typeTag
        >
            <div
                class="fill-secondary-blue flex justify-center items-center flex-wrap gap-2 h-full min-h-[130px] px-6 py-4 w-full"
            >
                <SchoolCardIconList
                    :tech-list="data.tech_used"
                    :guid="data.guid"
                />
            </div>
        </template>
    </GenericCard>
</template>

<style scoped>
.line-clamp {
    /* width: 400px; */
    margin: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: initial;
    display: -webkit-box;
    -webkit-line-clamp: 7;
    -webkit-box-orient: vertical;
    max-height: 210px;
}

.line-clamp p {
    display: contents;
}


</style>