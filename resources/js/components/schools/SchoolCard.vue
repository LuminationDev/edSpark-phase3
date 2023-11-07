<script setup>
import GenericCard from "@/js/components/card/GenericCard.vue";
import lowerSlugify from "@/js/helpers/slugifyHelper";
import {useRouter} from "vue-router";
import {likeURL, bookmarkURL} from "@/js/constants/serverUrl";
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";
import SchoolCardIconList from "@/js/components/schools/SchoolCardIconList.vue";

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    showIcon:{
        type: Boolean,
        required: false,
        default: true
    }

})


const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)
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
                    <div class="school-card-body cardDisplayPreview line-clamp text-left">
                        {{ data.content_blocks.blocks[1].data.text }}
                    </div>
            </div>
            
           
        </template>
        <template
            v-if="data.name.length > 0"
            #typeTag
        > <div
                class="
                    flex
                    flex-wrap
                    justify-center
                    items-center
                    py-4
                    px-6
                    w-full
                    gap-2
                    min-h-[130px]
                    h-full
                    fill-secondary-blue
                    "
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
  margin:0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: initial;
  display: -webkit-box;
  -webkit-line-clamp: 7;
  -webkit-box-orient: vertical;
  max-height:210px;
}

.line-clamp p {
  display: contents;
}


</style>