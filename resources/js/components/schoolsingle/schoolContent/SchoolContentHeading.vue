<script setup>
const props = defineProps({
    data: {
        type: Object,
        required: true
    }
});

console.log(props.data)
</script>
<template>
    <div
        class="editorJsHeadings"
        :class="{
            'text-[36px]' : data.data.level === 1,
            'text-[33px]' : data.data.level === 2,
            'text-[30px]' : data.data.level === 3,
            'text-[27px]' : data.data.level === 4,
            'text-[24px]' : data.data.level === 5,
            'text-[21px]' : data.data.level === 6,
        }"
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
