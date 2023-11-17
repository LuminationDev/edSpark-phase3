<script setup>
import {computed} from "vue";

import { alignmentClassGenerator} from "@/js/helpers/stringHelpers";

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    alignment: {
        type: String,
        required: false,
        default: 'left'
    }
});

const headingClass = computed(() => {
    switch (props.data.data.level) {
    case 1:
        return 'text-[36px]'
    case 2:
        return 'text-[33px]'
    case 3:
        return 'text-[30px]'
    case 4:
        return 'text-[27px]'
    case 5:
        return 'text-[24px]'
    case 6:
        return 'text-[21px]'
    }
})

const alignmentClass = computed(() =>{
    return alignmentClassGenerator(props.alignment)
})
</script>
<template>
    <div
        class="editorJsHeadings flex"
        :class="`${headingClass} ${alignmentClass}`"
    >
        <span v-html="data.data.text" />
    </div>
</template>

<style scoped lang="scss">
.editorJsHeadings {

}
</style>

/* HEADING 1 */
.ce-block__content h1 {
font-size: 36px;
}

.ce-block__content h2 {
font-size: 33px;
}

.ce-block__content h3 {
font-size: 30px;
}

.ce-block__content h4 {
font-size: 27px;
}

.ce-block__content h5 {
font-size: 24px;
}

.ce-block__content h6 {
font-size: 21px;
}
