<script setup lang="ts">

import {capitalize} from "vue";
import {useRouter} from "vue-router";

import Loader from "@/js/components/spinner/Loader.vue";
import {formatDateToDayTime} from "@/js/helpers/dateHelper";
import {BasePostType} from "@/js/types/PostTypes";

const props = defineProps({
    draftArray: {
        type: Array as () => BasePostType,
        required: false,
        default: () => {
        }
    },
    draftLoading: {
        type: Boolean,
        required: true
    }
})
const router = useRouter()

const handleClickEditDraft = (postData: BasePostType) : Promise<void> => {
    let targetForm = ''
    const lowerCasePostType = postData.post_type.toLowerCase()
    if (lowerCasePostType === 'advice') {
        targetForm = 'createGuide'
    } else if (lowerCasePostType === 'event') {
        targetForm = 'createEvent'
    } else if (lowerCasePostType === 'software') {
        targetForm = 'createSoftware'
    }
    if (targetForm) {
        router.push({
            name: targetForm,
            state: {draftContent: JSON.stringify(postData)}
        })
    }
    return;

}
</script>

<template>
    <div class="UserDraftListContainer flex items-start flex-col my-6 w-full">
        <div
            class="draftsHeader font-semibold grid grid-cols-12 mb-4 w-full"
        >
            <div class="col-span-6 grid place-items-start">
                Title
            </div>
            <div class="col-span-3 grid place-items-start">
                Created at
            </div>
            <div class="col-span-3 grid place-items-start">
                Type
            </div>
        </div>
        <template
            v-if="!props.draftLoading"
        >
            <div
                v-for="(draft,index) in draftArray"
                :key="index"
                :class="{'border-t-[1px]' : index === 0}"
                class="
                    border-b-[1px]
                    border-gray-100
                    flex-row
                    items-center
                    grid
                    grid-cols-12
                    h-16
                    w-full
                    hover:bg-gray-50
                    hover:cursor-pointer
                    "
                @click="() => handleClickEditDraft(draft)"
            >
                <div class="col-span-6">
                    <div class="DraftTitle p-4">
                        {{ draft.title }}
                    </div>
                    <!--                    <div class="DraftExcerpt line-clamp-1 text-gray-400">-->
                    <!--                        {{ stripHtmlTags(draft.excerpt) }}-->
                    <!--                    </div>-->
                </div>
                <div class="col-span-3 createdAtColumn grid place-items-start">
                    {{ formatDateToDayTime(new Date(draft.created_at)) }}
                </div>
                <div class="col-span-3 grid place-items-start postTypeColumn">
                    <div class="flex flex-col titleAndExcerpt">
                        {{ capitalize(draft.post_type) }}
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <Loader
                loader-message="fetching your drafts"
                loader-type="inline"
            />
        </template>
    </div>
</template>

