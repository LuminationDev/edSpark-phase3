<script setup>
import SchoolContentHeading from "@/js/components/schoolsingle/schoolContent/SchoolContentHeading.vue";
import SchoolContentParagraph from "@/js/components/schoolsingle/schoolContent/SchoolContentParagraph.vue";
import SchoolContentList from "@/js/components/schoolsingle/schoolContent/SchoolContentList.vue";
/**
 * Receive the whole content from the parents
 * either temp data or data received from Https call
 * @type {Readonly<ExtractPropTypes<{schoolContent: {type: ObjectConstructor, required: boolean}}>>}
 */
const props = defineProps({
    schoolContent:{
        type: Object,
        required: true
    }
    /**
     * schoolContent.blocks
     * [{id: string,
     * type: string ( Heading, paragraph, list)
     * data: Object {
     *     text: string
     * } <paragraph>
     * or Object {
     *     text: string
     *     level: integer (default 2)
     * } <heading>
     *     Object {
     *     style: string (ordered)
     *     items: Array<String>
     * } <List>
     * }
     * ]
     */
})
</script>

<template>
    <div
        v-for="(item,index) in schoolContent.blocks"
        :key="index"
        class="schoolContentIterator mb-2"
    >
        <!-- All styles are still hardcoded-->
        <!--  Once we figured out styling from Editorjs, render accordingly     -->
        <template v-if="item.type == 'header'">
            <SchoolContentHeading :data="item" />
        </template>
        <template v-else-if="item.type == 'paragraph'">
            <SchoolContentParagraph :data="item" />
        </template>
        <template v-else-if="item.type == 'list'">
            <SchoolContentList :data="item" />
        </template>
    </div>
</template>