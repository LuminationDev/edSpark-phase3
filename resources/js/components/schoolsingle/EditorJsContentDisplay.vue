<script setup lang="ts">
import {computed} from "vue";

import EditorJsContentHeading from "@/js/components/schoolsingle/schoolContent/EditorJsContentHeading.vue";
import EditorJsContentImage from "@/js/components/schoolsingle/schoolContent/EditorJsContentImage.vue";
import EditorJsContentList from "@/js/components/schoolsingle/schoolContent/EditorJsContentList.vue";
import EditorJsContentParagraph from "@/js/components/schoolsingle/schoolContent/EditorJsContentParagraph.vue";
import EditorJsContentVideo from "@/js/components/schoolsingle/schoolContent/EditorJsContentVideo.vue";
import {emptyEditorJsData} from "@/js/constants/schoolContentDefault";
import {EditorJSDataType} from "@/js/types/EditorJsTypes";

/**
 * Receive the whole content from the parents
 * either temp data or data received from Https call
 */
const props = defineProps({
    contentBlocks: {
        type: Object as () => EditorJSDataType,
        required: true
    },
    defaultContent:{
        type: Object, required : false,
        default: emptyEditorJsData
    }
})


const defaultContentBlocksIfSchoolIsNew = computed(() => {
    if (props.contentBlocks && props.contentBlocks.blocks?.length) return props.contentBlocks
    else {
        return props.defaultContent
    }
})

</script>

<template>
    <div
        v-for="(item,index) in defaultContentBlocksIfSchoolIsNew.blocks"
        :key="index"
        class="editorJsContentIterator mb-1 mr-2 p-4 md:!mr-12 md:!scale-100"
    >
        <template v-if="item.type === 'header'">
            <EditorJsContentHeading :data="item" />
        </template>
        <template v-else-if="item.type === 'paragraph'">
            <EditorJsContentParagraph :data="item" />
        </template>
        <template v-else-if="item.type === 'list'">
            <EditorJsContentList :data="item" />
        </template>
        <template v-else-if="item.type === 'image'">
            <EditorJsContentImage :data="item" />
        </template>
        <template v-else-if="item.type === 'video'">
            <EditorJsContentVideo :data="item" />
        </template>
    </div>
</template>
