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

const stripHTML = (value) => {
    const div = document.createElement('div');
    div.innerHTML = value;
    return div.textContent;
};


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
                    class="flex justify-start font-semibold text-xl text-left"
                >
                    {{ data.name }}
                </h5>
                <div
                    class="cardDisplayPreview line-clamp-2 school-card-body text-left"
                    v-html="stripHTML(data.content_blocks)"
                />
            </div>
        </template>
        <template
            v-if="data.name.length > 0"
            #typeTag
        >
            <div
                class="fill-secondary-blue flex content-end flex-wrap gap-2 h-full min-h-[130px] px-6 py-4 w-full"
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
 .line-clamp-2 {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    height: 3.1lh;
    -webkit-box-orient: vertical;
    margin: 0 auto;
    width: 99%;
    font-size: 16px;
}

.GenericCardContainer:hover .line-clamp-2 {
    -webkit-line-clamp: 6;
    height: 6.2lh;
}

</style>