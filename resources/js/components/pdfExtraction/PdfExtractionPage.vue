<template>
    <div class="ml-10 mt-24">
        <input
            type="file"
            accept=".doc,.docx"
            @change="handleFileUpload"
        >
        <div
            v-if="htmlContent"
            v-html="htmlContent"
        />
        <div
            v-if="error"
            class="error"
        >
            {{ error }}
        </div>
        <div class="mt-20">
            <button
                v-if="htmlContent"
                class="border-2 border-black p-2"
                @click="downloadHtml"
            >
                Download HTML
            </button>
            <button
                v-if="htmlContent"
                class="border-2 border-black ml-20 p-2"
                @click="downloadJson"
            >
                Download JSON
            </button>
            <button
                v-if="Object.keys(criteriaSections).length > 0"
                class="border-2 border-black ml-20 p-2"
                @click="downloadCriteriaJson"
            >
                Download Criteria JSON
            </button>
            <button
                v-if="Object.keys(jsonContent).length > 0"
                @click="displayContent"
            >
                Display Content
            </button>
        </div>
    </div>
    <div>
        <div
            v-if="displayedContent"
            class="mt-14"
        >
            <div
                id="successCriteria"
                v-html="displayedContent"
            />
        </div>
    </div>
</template>

<script setup>
import mammoth from 'mammoth';
import {ref} from 'vue';

const htmlContent = ref('');
const error = ref('');
const jsonContent = ref({});
const displayedContent = ref('');
// we can add some more variables here for those keywords
const criteriaSections = ref([]);
const digitalTechnologiesSections = ref([]);
const requiredResourcesSections = ref([]);
const otherResourcesSections = ref([]);

console.log("jsonContent:", jsonContent.value);

const handleFileUpload = async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    try {
        const html = await convertToHtml(file);
        htmlContent.value = html;
        error.value = ''; // Clear any previous errors
        // we can add some more keywords functions here
        extractCriteriaSections(html);
        extractDigitalTechnologiesSections(html)
        extractRequiredResourcesSections(html)
        extractOtherResourcesSections(html)

    } catch (error) {
        console.error('Error processing file:', error);
        htmlContent.value = ''; // Clear content in case of error
        error.value = 'Error processing file: ' + error.message;
    }
};

const convertToHtml = async (file) => {
    const reader = new FileReader();
    const buffer = await new Promise((resolve, reject) => {
        reader.onload = () => resolve(reader.result);
        reader.onerror = reject;
        reader.readAsArrayBuffer(file);
    });

    const result = await mammoth.convertToHtml({arrayBuffer: buffer});
    return result.value;
};

const downloadHtml = () => {
    const blob = new Blob([htmlContent.value], {type: 'text/html'});
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'document.html';
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
};

const downloadJson = () => {
    const text = stripHtml(htmlContent.value);
    const jsonContent = JSON.stringify({content: text}, null, 2);
    const blob = new Blob([jsonContent], {type: 'application/json'});
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'document.json';
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
};

const downloadCriteriaJson = () => {
    jsonContent.value = JSON.stringify({
        // we can add more keywords here if we need
        "Success criteria": criteriaSections.value,
        "Digital Technologies": digitalTechnologiesSections.value,
        "Required Resources": requiredResourcesSections.value,
        "Other resources to try (optional)": otherResourcesSections.value
    }, (key, value) => {
        if (key === 'content') {
            return stripHtml(value); // Strips HTML tags
        }
        return value;
    }, 2);
    const blob = new Blob([jsonContent.value], {type: 'application/json'});
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'document.json';
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
    //the actual content with keywords is inside jsonContent
    console.log(jsonContent.value)

};

//displayes "Success criteria section not found in the JSON content."
// const displayContent = () => {
//     console.log("jsonContent:", jsonContent.value);
//
//     if (jsonContent.value && jsonContent.value.hasOwnProperty("Success criteria")) {
//         const successCriteriaArray = jsonContent.value["Success criteria"];
//         if (successCriteriaArray.length > 0) {
//             displayedContent.value = successCriteriaArray[0].content;
//         } else {
//             displayedContent.value = "Success criteria section is empty.";
//         }
//     } else {
//         displayedContent.value = "Success criteria section not found in the JSON content.";
//     }
// };

//displayes "Success criteria section not found in the JSON content."
// const displayContent = () => {
//     if (jsonContent.value && jsonContent.value.hasOwnProperty("Success criteria")) {
//         const successCriteriaArray = jsonContent.value["Success criteria"];
//         if (successCriteriaArray.length > 0) {
//             displayedContent.value = successCriteriaArray[0].content;
//         } else {
//             displayedContent.value = "Success criteria section is empty.";
//         }
//     } else {
//         displayedContent.value = "Success criteria section not found in the JSON content.";
//     }
// };

//displays the content in json but not in a format
const displayContent = () => {
    displayedContent.value = jsonContent.value;
};

const stripHtml = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || '';
};

const extractSections = (html, keyword, sectionsRef) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const elements = doc.querySelectorAll('*');
    let found = false;
    let section = {};
    let currentSection = null;
    let paragraphCount = 1;
    let listCount = 1;

    const renameTag = (tagName, currentSection) => {

        if (currentSection === 'Success Criteria' && tagName === 'p') {
            return `paragraph${paragraphCount++}`;
        } else if (currentSection === 'Digital Technologies' && tagName === 'p') {
            return `paragraph${paragraphCount++}`;
        } else if (currentSection === 'Digital Technologies' && tagName === 'li') {
            return `List${listCount++}`;
        } else if (currentSection === 'Required Resources' && tagName === 'p') {
            return `paragraph${paragraphCount++}`;
        }
        else {
            return tagName;
        }
    };

    for (let i = 0; i < elements.length; i++) {
        const element = elements[i];
        if (element.textContent.trim().toLowerCase() === keyword.toLowerCase()) {
            found = true;
            currentSection = keyword;
        } else if (found) {
            if (element.tagName === 'H1' || element.tagName === 'H2' || element.tagName === 'H3' || element.tagName === 'H4' || element.tagName === 'H5' || element.tagName === 'H6') {
                found = false;
                sectionsRef.value.push(section);
                section = {};
                currentSection = null;
            } else {
                const tagName = element.tagName.toLowerCase();
                const customTagName = renameTag(tagName, currentSection);
                if (!section[customTagName]) {
                    section[customTagName] = [];
                }
                const childSection = {};
                childSection[customTagName] = element.textContent.trim();
                section[customTagName].push(childSection);
            }
        }
    }
    if (Object.keys(section).length > 0) {
        sectionsRef.value.push(section);
    }
};





// All the keywords functions can be added here
const extractCriteriaSections = (html) => {
    extractSections(html, 'Success Criteria', criteriaSections);
};

const extractDigitalTechnologiesSections = (html) => {
    extractSections(html, 'Digital Technologies', digitalTechnologiesSections);
};

const extractRequiredResourcesSections = (html) => {
    extractSections(html, 'Required Resources', requiredResourcesSections);
};

const extractOtherResourcesSections = (html) => {
    extractSections(html, 'Other Resources to Try (Optional)', otherResourcesSections);
};
</script>

<style scoped>
.error {
    color: red;
}
</style>
