<script setup>
import {computed, ref} from 'vue'

import {sampleLearningTask} from "@/js/components/learningtask/sampleLearningTask";

const parser = new DOMParser();

// Parse the HTML string
const doc = parser.parseFromString(sampleLearningTask, 'text/html');

// Access the body of the parsed document
const parsedContent = doc.body

const contentNodeLibrary = {};
const headings = parsedContent.querySelectorAll('h1');

headings.forEach((heading, index) => {
    // Find the <a> tag within the current h1 element
    const aTag = heading.querySelector('a');

    // Check if the <a> tag exists before extracting the inner HTML
    if (aTag) {
        // Find the following text node after the <a> tag
        const textNode = aTag.nextSibling;

        // Extract and print the text content after the <a> tag
        const textContent = textNode && textNode.textContent ? textNode.textContent.trim() : '';

        // Store the content in contentNodeLibrary
        const key = textContent.toLowerCase().replace(/\s+/g, '_');
        contentNodeLibrary[key] = textNode;
    } else {
        console.log(`No <a> tag found in Heading ${index + 1}`);
    }
});

console.log(contentNodeLibrary);

function getRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

// Iterate over contentNodeLibrary and apply random color
for (const key in contentNodeLibrary) {
    if (contentNodeLibrary.hasOwnProperty(key)) {
        const node = contentNodeLibrary[key];

        // Apply random color to the node
        const randomColor = getRandomColor();
        // Check if node is defined before applying styles
        if (node) {
            // Create style property if not present
            if (!node.style) {
                node.style = {};
            }

            // Apply random color to the node
            const randomColor = getRandomColor();
            node.style.color = randomColor;

            console.log(`Applied random color "${randomColor}" to node with key "${key}"`);
        } else {
            console.log(`Node with key "${key}" is undefined`);
        }

        console.log(`Applied random color "${randomColor}" to node with key "${key}"`);
    }
}
 /*
 Task Intentions
 Success Criteria
 Digital Technologies
 Required Resources
 Other Resources to try (opt)
 Planning and Preparation
 Task Sequence
 Curriculum connections
 Australian Curriculum v9
 Competencies
 Cross-Curriculum Priorities
 General capabilities
  */




</script>

<template>
    <div class="LearningTask flex mt-40 px-20 w-full">
        <!--        <pre class="break-words max-w-full w-full"> {{ parsedContent }}</pre>-->
        <div v-html="parsedContent.innerHTML" />
    </div>
</template>


<style lang="scss">
.LearningTask {
    table {
        width: 100%
    }

}
</style>
