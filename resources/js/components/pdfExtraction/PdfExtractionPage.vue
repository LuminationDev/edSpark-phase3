<script setup>
import mammoth from 'mammoth';
import {ref} from 'vue';

import { data } from './dataJson'

//all initial variables are here
const htmlContent = ref('');
const error = ref('');
const jsonContent = ref({});
const displayedContent = ref('');
const displayedObjectJson_1 = ref('')
const displayedObjectJson_2 = ref('')
const displayedObjectJson_3 = ref('')
const displayHref = ref('')
// we can add some more variables here for those keywords
const criteriaSections = ref([]);
const digitalTechnologiesSections = ref([]);
const requiredResourcesSections = ref([]);
const otherResourcesSections = ref([]);

//just to debug
console.log("jsonContent:", jsonContent.value);

//handle file upload
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

//creates html content out of doc file
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

//downloads extracted content in html format
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

//downloads the json file with extracted content from html without formatting
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

//downloads the json/ts file with objects formatted content out of html
const downloadCriteriaJson = () => {
    jsonContent.value = JSON.stringify({
        // we can add more keywords here if we need
        "Session overview": criteriaSections.value,
        "Digital Technologies": digitalTechnologiesSections.value,
        "Required Resources": requiredResourcesSections.value,
        "Other resources to try (optional)": otherResourcesSections.value
    }, (key, value) => {
        if (key === 'content') {
            return stripHtml(value); // Strips HTML tags
        }
        return value;
    }, 2);
    const dataExportedJson = 'export const data = ' + jsonContent.value; // Adding export statement
    const blob = new Blob([dataExportedJson], {type: 'text/javascript'});
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'dataJson.ts'; // Setting download filename with extension
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
};

//displays the content in json but not in a format
const displayContent = () => {
    displayedContent.value = jsonContent.value;
};

//filters only text content out-of html data
const stripHtml = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || '';
};

//extraction of content and json formatting in a proper Arrays format
const extractSections = (html, keyword, sectionsRef) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const elements = doc.querySelectorAll('*');
    let found = false;
    let section = {};
    let currentSection = null;

    //renaming of tag objects in json file
    const renameTag = (tagName, currentSection) => {
        switch (tagName) {
        case 'p':
            return 'paragraph';
        case 'td':
            return 'td';
        case 'th':
            return 'th';
        case 'strong':
            return 'strong';
        case 'li':
            return 'list';
        case 'ul':
            return 'ul';
        case 'tr':
            return 'tr';
        case 'a':
            return `${currentSection}_link`;
        default:
            return tagName;
        }
    };

    //creates href link naming
    const generateLinkName = (currentSection, linkIndex) => {
        return `${currentSection}_link${linkIndex}`;
    };

    //conditions for formatting objects in json file
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
                    const linkName = generateLinkName(currentSection, section[customTagName] ? section[customTagName].length + 1 : 1); // Generate unique link name
                    if (!section[customTagName]) {
                        section[customTagName] = [];
                    }
                    section[customTagName].push({ name: linkName, href: element.getAttribute('href'), text: element.textContent.trim() });
                } else {
                    if (!section[customTagName]) {
                        section[customTagName] = [];
                    }
                    // Update here to merge paragraphs into a single object
                    if (customTagName === 'paragraph' || customTagName === 'strong' || customTagName === 'tr' || customTagName === 'th' || customTagName === 'td' || customTagName === 'list' || customTagName === 'ul') {
                        section[customTagName] = section[customTagName] || [];
                        section[customTagName].push(element.textContent.trim());
                    } else {
                        const childSection = {};
                        childSection[customTagName] = element.textContent.trim();
                        section[customTagName].push(childSection);
                    }
                }
            }
        }
    }

    // Check if there are no href links in the section and set href=null accordingly
    if (currentSection === 'a_links' && (!section['a_links'] || section['a_links'].length === 0)) {
        delete section['a_links']; // Remove the placeholder link
    }
    if (Object.keys(section).length > 0) {
        sectionsRef.value.push(section);
    }
};



// All the keywords functions can be added here
const extractCriteriaSections = (html) => {
    extractSections(html, 'Session Overview', criteriaSections);
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

// function to filter required content
const displaySelectedContent = () => {
    //get the content from the object's array
    const paragraphContent_0 = data['Session overview'][0]?.paragraph?.[0] || "Paragraph content not found.";
    const strongContent = data['Session overview'][0]?.strong?.[1] || "Strong content not found.";
    const content_1 = data['Digital Technologies'][0]?.list?.[2] || "Content1 not found.";
    //get the content for href from the object's array on the basis of name
    const requiredResourceLink4 = data['Required Resources'][0]?.["Required Resources_link"]?.find(link => link.name === 'Required Resources_link4');
    const linkHref = requiredResourceLink4 ? requiredResourceLink4.href : "Link not found.";

    //assign the extracted content to the variables
    displayedObjectJson_1.value = paragraphContent_0;
    displayedObjectJson_2.value = strongContent;
    displayedObjectJson_3.value = content_1;
    displayHref.value = linkHref;
};

// function to extract video ID from YouTube URL
const extractVideoId = (url) => {
    const youtubeMatch = url.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([^&#?]+)/);
    return youtubeMatch ? youtubeMatch[1] : null;
};
// function to generate YouTube embed URL from video ID
const getYouTubeEmbedUrl = (videoId) => {
    if (!videoId) return ''; // If no videoId provided, return empty string

    return `https://www.youtube.com/embed/${videoId}`;
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
                id="sessionOverview"
                v-html="displayedContent"
            />
        </div>
    </div>
    <div>
        <button
            class="border-2 border-black p-2 rounded-2xl"
            @click="displaySelectedContent"
        >
            Display required content from json file
        </button>
        <div>
            <div
                class="flex flex-row gap-20 mt-14"
            >
                <div>
                    <div>Session1 of Session Overview:</div>
                    <div
                        id="sessionOverview"
                        class="bg-adminTeal border-2 border-black p-4 text-white"
                        v-html="displayedObjectJson_1"
                    />
                </div>
                <div>
                    <div>List 3 of Digital Technologies:</div>
                    <div
                        id="sessionOverview"
                        class="bg-yellow-200 border-2 border-black p-4"
                        v-html="displayedObjectJson_2"
                    />
                </div>


                <div>
                    <div>List 5 of Digital Technologies:</div>
                    <div
                        id="sessionOverview"
                        class="bg-red-400 border-2 border-black p-4"
                        v-html="displayedObjectJson_3"
                    />
                </div>
            </div>
            <div class="mt-10">
                <div>HREF: link5</div>
                <div
                    id="sessionOverview"
                    class="bg-blue-700 border-2 border-black p-4 text-white"
                    v-html="displayHref"
                />
                <iframe
                    class="mt-1"
                    width="480"
                    height="360"
                    :src="getYouTubeEmbedUrl(extractVideoId(displayHref))"
                    title="Mitosis in an animal cell Under the Microscope"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin"
                    allowfullscreen
                />
            </div>
        </div>
    </div>
</template>

<style scoped>
.error {
    color: red;
}
</style>
