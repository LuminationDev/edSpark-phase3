<script setup lang="ts">
type DraftFromAutoSave = {
    content: string,
    title: string,
    coverImage: string,
    excerpt: string,
    extraContent: string,
    id: number,
    updatedAt: string,
    createdAt: string,
    type: Array<string> | string,
    post_type: string
}

import {capitalize, ref} from "vue";

import Loader from "@/js/components/spinner/Loader.vue";
import {formatDateToDayTime} from "@/js/helpers/dateHelper";
import {stripHtmlTags} from "@/js/helpers/slugifyHelper";

const props = defineProps({
    draftArray: {
        type: Array as () => DraftFromAutoSave,
        required: false,
        default: []
    },
    draftLoading: {
        type: Boolean,
        required: true
    }
})
console.log(props.draftArray)
</script>

<template>
    <div class="UserDraftListContainer flex items-start flex-col mb-6 w-full">
        <div
            class="draftsHeader font-semibold grid grid-cols-12 mb-4 w-full"
        >
            <div class="col-span-6 grid place-items-center">
                Title
            </div>
            <div class="col-span-3 grid place-items-center">
                Created at
            </div>
            <div class="col-span-3 grid place-items-center">
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
            >
                <div class="col-span-6">
                    <div class="DraftTitle">
                        {{ draft.title }}
                    </div>
                    <!--                    <div class="DraftExcerpt line-clamp-1 text-gray-400">-->
                    <!--                        {{ stripHtmlTags(draft.excerpt) }}-->
                    <!--                    </div>-->
                </div>
                <div class="col-span-3 createdAtColumn grid place-items-center">
                    {{ formatDateToDayTime(new Date(draft.created_at)) }}
                </div>
                <div class="col-span-3 grid place-items-center postTypeColumn">
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

