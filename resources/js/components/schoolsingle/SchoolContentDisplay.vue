<script setup>
import SchoolContentHeading from "@/js/components/schoolsingle/schoolContent/SchoolContentHeading.vue";
import SchoolContentParagraph from "@/js/components/schoolsingle/schoolContent/SchoolContentParagraph.vue";
import SchoolContentList from "@/js/components/schoolsingle/schoolContent/SchoolContentList.vue";
import SchoolContentImage from "@/js/components/schoolsingle/schoolContent/SchoolContentImage.vue";
import SchoolContentVideo from '@/js/components/schoolsingle/schoolContent/SchoolContentVideo.vue';
/**
 * Receive the whole content from the parents
 * either temp data or data received from Https call
 */
const props = defineProps({
    schoolContentBlocks:{
        type: Object,
        required: true
    }
})

/**
 * schoolContentBlocks.blocks
 * [{id: string,
 * type: string ( Heading, paragraph, list)
 * data:
 *     Object<paragraph> {
 *          text: string
 *      }
 *     Object<heading> {
 *          text: string
 *          level: integer (default 2)
 *      }
 *     Object<list> {
 *          style: string (ordered)
 *          items: Array<String>
 *      }
 *      Object<image>{
 *          url: string,
 *          caption: string,
 *          withBorder: boolean
 *          withBackground: boolean,
 *          stretched: boolean
 *      }
 * }
 * ]
 */
props.schoolContentBlocks.blocks.forEach(block => {
    console.log(block.data.file);
})
</script>

<template>
    <div
        v-for="(item,index) in schoolContentBlocks.blocks"
        :key="index"
        class="schoolContentIterator mb-2"
    >
        <!-- All styles are still hardcoded-->
        <!-- Once we figured out styling from Editorjs, render accordingly     -->
        <template v-if="item.type == 'header'">
            <SchoolContentHeading :data="item" />
        </template>
        <template v-else-if="item.type == 'paragraph'">
            <SchoolContentParagraph :data="item" />
        </template>
        <template v-else-if="item.type == 'list'">
            <SchoolContentList :data="item" />
        </template>
        <template v-else-if="item.type == 'image'">
            <SchoolContentImage :data="item" />
        </template>
        <template v-else-if="item.type === 'videoRecorder'">
            <SchoolContentVideo :data="item" />
        </template>
    </div>
</template>
