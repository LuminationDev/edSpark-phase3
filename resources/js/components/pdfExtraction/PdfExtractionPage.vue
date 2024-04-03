<script setup>
import mammoth from 'mammoth';
import {ref} from 'vue';

import { data } from './dataJson'

const htmlContent = ref('');
const error = ref('');
const jsonContent = ref({});
const displayedContent = ref('');
const displayedObjectJson_1 = ref('')
const displayedObjectJson_2 = ref('')
const displayedObjectJson_3 = ref('')
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
    let thCount = 1;
    let trCount = 1;
    let strongCount = 1;
    let ulCount = 1;

    const renameTag = (tagName) => {
        if (tagName === 'p') {
            return `paragraph_${paragraphCount++}`;
        } else if (tagName === 'li') {
            return `list_${listCount++}`;
        } else if (tagName === 'th') {
            return `th_${thCount++}`;
        } else if (tagName === 'tr') {
            return `tr_${trCount++}`;
        } else if (tagName === 'strong') {
            return `strong_${strongCount++}`;
        } else if (tagName === 'ul') {
            return `ul_${ulCount++}`;
        }
        else {
            return tagName;
        }
    };

    const generateLinkName = (currentSection, linkIndex) => {
        return `${currentSection}_link${linkIndex}`;
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
                if (tagName === 'a') {
                    const linkName = generateLinkName(currentSection, section[customTagName + '_links'] ? section[customTagName + '_links'].length + 1 : 1); // Generate unique link name
                    if (!section[customTagName + '_links']) {
                        section[customTagName + '_links'] = [];
                    }
                    section[customTagName + '_links'].push({ name: linkName, href: element.getAttribute('href'), text: element.textContent.trim() });
                } else {
                    if (!section[customTagName]) {
                        section[customTagName] = [];
                    }
                    const childSection = {};
                    childSection[customTagName] = element.textContent.trim();
                    section[customTagName].push(childSection);
                }
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

const displayStrongContent = () => {
    if (data && data['Success criteria']) {
        const successCriteriaArray = data['Success criteria'];
        if (successCriteriaArray.length > 0) {
            const strongContent = successCriteriaArray[0].paragraph_1;
            if (strongContent && strongContent.length > 0) {
                displayedObjectJson_1.value = strongContent[0].paragraph_1;
            } else {
                displayedObjectJson_1.value = "Strong content not found in Success criteria section.";
            }
        } else {
            displayedObjectJson_1.value = "Success criteria section is empty.";
        }
    } else {
        displayedObjectJson_1.value = "Success criteria section not found in the JSON content.";
    }
    if (data && data['Digital Technologies']) {
        const digitalTechnologiesArray = data['Digital Technologies'];
        if (digitalTechnologiesArray.length > 0) {
            const listContent_3 = digitalTechnologiesArray[0].list_3;
            const listContent_5 = digitalTechnologiesArray[0].list_5;
            let content1 = '';
            let content2 = '';
            if (listContent_3 && listContent_3.length > 0) {
                content1 += 'List 3: ' + listContent_3[0].list_3 + '<br>';
            } else {
                content1 += "List 3 content not found in Digital Technologies section.<br>";
            }
            if (listContent_5 && listContent_5.length > 0) {
                content2 += 'List 5: ' + listContent_5[0].list_5;
            } else {
                content2 += "List 5 content not found in Digital Technologies section.";
            }
            displayedObjectJson_2.value = content1;
            displayedObjectJson_3.value = content2;
        } else {
            displayedObjectJson_2.value = "Digital Technologies section is empty.";
        }
    } else {
        displayedObjectJson_2.value = "Digital Technologies section not found in the JSON content.";
    }
};

</script>

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
    <div>
        <button
            @click="displayStrongContent"
        >
            Display Strong Content from Success Criteria
        </button>
        <div>
            <div
                class="flex flex-row gap-20 mt-14"
            >
                <div>
                    <div>Session1 of Success Criteria:</div>
                    <div
                        id="successCriteria"
                        class="border-2 border-black p-4"
                        v-html="displayedObjectJson_1"
                    />
                </div>
                <div>
                    <div>List 3 of Digital Technologies:</div>
                    <div
                        id="successCriteria"
                        class="border-2 border-black ml-20 p-4 w-32"
                        v-html="displayedObjectJson_2"
                    />
                </div>


                <div>
                    <div>List 5 of Digital Technologies:</div>
                    <div
                        id="successCriteria"
                        class="border-2 border-black ml-20 p-4 w-32"
                        v-html="displayedObjectJson_3"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.error {
    color: red;
}
</style>
