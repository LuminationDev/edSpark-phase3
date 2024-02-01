<script setup>
import {computed, ref} from 'vue'

import {sampleLearningTask} from "@/js/components/learningtask/sampleLearningTask";

const parser = new DOMParser();

// Parse the HTML string
const doc = parser.parseFromString(sampleLearningTask, 'text/html');

// Access the body of the parsed document
const parsedContent = ref(doc.body.innerHTML);
</script>

<template>
    <div class="LearningTask flex mt-40 px-20 w-full">
        <!--        <pre class="break-words max-w-full w-full"> {{ parsedContent }}</pre>-->
        <div v-html="parsedContent" />
    </div>
</template>


<style lang="scss">
.LearningTask {
    table {
        width: 100%
    }

}
</style>
