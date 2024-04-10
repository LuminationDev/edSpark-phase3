<script setup>
import mammoth from 'mammoth';
import {ref} from 'vue';

import { data } from './dataJson'

//all initial variables are here
const htmlContent = ref('');
const error = ref('');
const jsonContent = ref({});

//variables used to store json format value
const topicHeading = ref('')
const topicCategory = ref('')

//variable to display content on layout without json (Test-Phase)
const displayedJsonContent = ref('');

//variable to display content on layout from json file on button press
const displayHeading = ref('')
const displayCategory = ref('')
const displayTaskSummary = ref('')
const displaySessionOverview = ref('')
const displayDigitalTechnologies = ref('')
const displayRequiredResourcesParagraph = ref('')
const displayRequiredResourcesHeadings = ref([])


const displayHref = ref('')

// we can add some more variables here to store children's content in array for json file.
const taskSummarySections = ref([]);
const sessionOverviewSections = ref([]);
const digitalTechnologiesSections = ref([]);
const requiredResourcesSections = ref([]);
const otherResourcesSections = ref([]);

//just to debug
//console.log("jsonContent:", jsonContent.value);

//handle file upload
const handleFileUpload = async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    try {
        const html = await convertToHtml(file);
        htmlContent.value = html;
        error.value = ''; // Clear any previous errors
        //extracts child content on the basis of ID
        extractTopicCategoryById(htmlContent.value, '_1p99sr8cjimz');
        extractTopicHeadingById(htmlContent.value, '_1gy27kj6jprf');
        //extract child elements on the basis of content heading
        extractTaskSummarySections(html)
        extractSessionOverviewSections(html);
        extractDigitalTechnologiesSections(html)
        extractRequiredResourcesSections(html)
        extractOtherResourcesSections(html)
        //console.log(htmlContent.value)

    } catch (error) {
        console.error('Error processing file:', error);
        htmlContent.value = ''; // Clear content in case of error
        error.value = 'Error processing file: ' + error.message;
    }
};

//creates html content out of doc file when uploading the doc file
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

//downloads extracted content in html format (for debugging)
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

//downloads the json file with extracted content from html without formatting (for debugging)
const downloadJson = () => {
    const text = stripHtml(htmlContent.value);
    const jsonRawContent = JSON.stringify({content: text}, null, 2);
    const blob = new Blob([jsonRawContent], {type: 'application/json'});
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
const downloadFormattedJson = () => {
    jsonContent.value = JSON.stringify({
        //arrange the formatting of the json file
        "Topic Heading": topicHeading.value,
        "Topic Category": topicCategory.value,
        "Task Summary": taskSummarySections.value,
        "Session Overview": sessionOverviewSections.value,
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
    //console.log(jsonContent.value)
};

//another approach to download the json file with objects formatted content out of html (Not Currently Used)
const downloadFormattedJson1 = () => {
    //Create a new object to include displayTopicHeading
    const fullJsonContent = {
        "topicHeading": topicHeading.value,
        "data": {
            // Your existing data structure here...
            "Session overview": sessionOverview.value,
            "Digital Technologies": digitalTechnologiesSections.value,
            "Required Resources": requiredResourcesSections.value,
            "Other resources to try (optional)": otherResourcesSections.value
        }
    };
    //Convert the full JSON content to string with proper indentation
    const jsonString = JSON.stringify(fullJsonContent, null, 2);
    //Create a blob from the JSON string
    const blob = new Blob([jsonString], { type: 'application/json' });
    //Create a URL for the blob
    const url = window.URL.createObjectURL(blob);
    //Create a link element to trigger download
    const a = document.createElement('a');
    a.href = url;
    a.download = 'dataJson.json'; // Change the filename if needed
    //Append the link to the body and trigger click
    document.body.appendChild(a);
    a.click();
    //Clean up
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
};

//displays the content in json but not in a format
const displayRawJsonContent = () => {
    displayedJsonContent.value = jsonContent.value;
    console.log(displayedJsonContent.value)
};

//filters only text content out-of html data
const stripHtml = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || '';
};

//Function to extract content with specified ID from HTML
const extractTopicHeadingById = (html, id) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const element = doc.getElementById(id);
    if (element && element.parentNode) {
        topicHeading.value = element.parentNode.textContent.trim();
    } else {
        // displayTopicHeading.value = `Content with ID ${id} not found.`;
    }
};

//Function to extract content with specified ID from HTML
const extractTopicCategoryById = (html, id) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const element = doc.getElementById(id);
    if (element && element.parentNode) {
        topicCategory.value = element.parentNode.textContent.trim();
    } else {
        // displayTopicHeading.value = `Content with ID ${id} not found.`;
    }
};

//extraction of content and json formatting in a proper Arrays format on the basis of keyword
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

//extraction of content and json formatting in a proper Arrays format on the basis of ID
const extractSectionsById = (html, id, sectionsRef) => {
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
        if (element.id === id) {
            found = true;
            currentSection = id;
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


//All the keywords functions can be added here
const extractTaskSummarySections = (html) => {
    extractSectionsById(html, '_r9sioprybg6g', taskSummarySections);
};
const extractSessionOverviewSections = (html) => {
    extractSections(html, 'Session Overview', sessionOverviewSections);
};
const extractDigitalTechnologiesSections = (html) => {
    extractSectionsById(html, '_jhqnd16qn0md', digitalTechnologiesSections);
};
const extractRequiredResourcesSections = (html) => {
    extractSections(html, 'Required Resources', requiredResourcesSections);
};
const extractOtherResourcesSections = (html) => {
    extractSections(html, 'Other Resources to Try (Optional)', otherResourcesSections);
};

//function to filter required content and display on the layout
const displaySelectedContent = () => {
    //get the content from the object's array that has lists of contents - "Session Overview"
    let summarySO = "";
    if (data['Session Overview'][0]?.paragraph) {
        summarySO += "<ul>";
        data['Session Overview'][0].paragraph.forEach((sentence, index) => {
            summarySO += "<li>"+ sentence + "</li>";
            // Add <br> tags after each list item except for the last one
            if (index !== data['Session Overview'][0].paragraph.length - 1) {
                summarySO += "<br>";
            }
        });
        summarySO += "</ul>";
        displaySessionOverview.value = summarySO.trim();
    } else {
        displaySessionOverview.value = "Session Overview content not found.";
    }
    //get the content from the object's array that has lists of contents - "Session Overview"
    let summaryDT = "";
    if (data['Digital Technologies'][0]?.list) {
        summaryDT += "<ul>";
        data['Digital Technologies'][0].list.forEach((sentence, index) => {
            summaryDT += "<li>"+ sentence + "</li>";
            // Add <br> tags after each list item except for the last one
            if (index !== data['Digital Technologies'][0].list.length - 1) {
                summaryDT += "<br>";
            }
        });
        summaryDT += "</ul>";
        displayDigitalTechnologies.value = summaryDT.trim();
    } else {
        displayDigitalTechnologies.value = "Digital Technologies content not found.";
    }
    //the logic to populate displayRequiredResourcesHeadings and removing the colon after list content
    let summaryRR_Headings = "";
    if (data['Required Resources'][0]?.strong) {
        data['Required Resources'][0].strong.forEach((sentence) => {
            // Remove colons from the sentence
            sentence = sentence.replace(/:/g, '');
            summaryRR_Headings += sentence;
            displayRequiredResourcesHeadings.value.push({ title: "Title Text will be here", content: summaryRR_Headings.trim() });
            summaryRR_Headings = ""; // Reset summaryRR_Headings for the next item

        });
    } else {
        displayRequiredResourcesHeadings.value.push({ title: "Title Text will be here", content: "Digital Technologies content not found." });
    }
    //get the content from the object's array
    displayHeading.value = data['Topic Heading'] || "Heading not found"
    displayCategory.value = data['Topic Category'] || "Category not found"
    displayTaskSummary.value = data['Task Summary'][0]?.paragraph || "Task Summary not found"
    displayRequiredResourcesParagraph.value = data['Required Resources'][0]?.paragraph[0]
    //get the content for href from the object's array on the basis of name
    const requiredResourceLink4 = data['Required Resources'][0]?.["Required Resources_link"]?.find(link => link.name === 'Required Resources_link4');
    const linkHref = requiredResourceLink4 ? requiredResourceLink4.href : "Link not found.";

    //assign the extracted content to the variables
    displayHref.value = linkHref;

    //console
    console.log(displayRequiredResourcesHeadings)
};

//function to extract video ID from YouTube URL
const extractVideoId = (url) => {
    const youtubeMatch = url.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([^&#?]+)/);
    return youtubeMatch ? youtubeMatch[1] : null;
};
//function to generate YouTube embed URL from video ID
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
                Download complete HTML
            </button>
            <button
                v-if="htmlContent"
                class="border-2 border-black ml-20 p-2"
                @click="downloadJson"
            >
                Download only Content JSON
            </button>
            <button
                v-if="Object.keys(sessionOverviewSections).length > 0"
                class="border-2 border-black ml-20 p-2"
                @click="downloadFormattedJson"
            >
                Download Selected Content JSON
            </button>
            <button
                v-if="Object.keys(jsonContent).length > 0"
                @click="displayRawJsonContent"
            >
                Display Content
            </button>
        </div>
    </div>
    <div>
        <div
            v-if="displayedJsonContent"
            class="mt-14"
        >
            <div
                id="sessionOverview"
                v-html="displayedJsonContent"
            />
        </div>
    </div>
    <div class="border-2 border-black h-full">
        <button
            class="border-2 border-black mt-6 p-2 rounded-2xl"
            @click="displaySelectedContent"
        >
            Display required content from json file
        </button>
        <div>
            <div class="mt-10">
                <div>HREF: link4</div>
                <div
                    id="sessionOverview"
                    class="bg-blue-700 border-2 border-black p-4 text-white"
                    v-html="displayHref"
                />
                <iframe
                    v-if="displayHref"
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
    <div class="border-2 border-gray-300 mt-10 p-6">
        <div
            class="mt-2 text-4xl"
        >
            <div
                v-if="displayHeading"
                v-html="displayHeading"
            />
            <div v-else>
                Topic Heading
            </div>
        </div>
        <div class="grid grid-cols-2 mt-2">
            <div class="mt-2 text-lg">
                <div
                    v-if="displayCategory"
                    v-html="displayCategory"
                />
                <div v-else>
                    AR/VR Learning Tasks
                </div>
            </div>
            <div class="flex flex-col gap-4 text-lg">
                <div class="flex flex-row">
                    <div>Year Level: </div><div>Year level will come here.</div>
                </div>
                <div class="flex flex-row">
                    <div>Learning Area: </div><div>Learning area will come here.</div>
                </div>
                <div class="flex flex-row">
                    <div>Duration: </div><div>Duration will come here.</div>
                </div>
            </div>
        </div>
        <div class="mt-10">
            <div class="text-3xl">
                Task Summary
            </div>
            <div class="mt-4 text-xl">
                <div
                    v-if="displayTaskSummary"
                    v-html="displayTaskSummary"
                />
                <div v-else>
                    Task Summary paragraph will come here.
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-2 mt-10">
            <div class="border-2 border-gray-300 p-4 w-full">
                <div class="text-3xl">
                    Session Overview
                </div>
                <div class="mt-4 text-xl">
                    <div
                        v-if="displaySessionOverview"
                        v-html="displaySessionOverview"
                    />
                    <div v-else>
                        Session Overview paragraph will come here.
                    </div>
                </div>
            </div>
            <div class="border-2 border-gray-300 p-4 w-full">
                <div class="text-3xl">
                    Digital Technologies
                </div>
                <div class="mt-4 text-xl">
                    <div
                        v-if="displayDigitalTechnologies"
                        v-html="displayDigitalTechnologies"
                    />
                    <div v-else>
                        Digital technologies paragraph will come here.
                    </div>
                </div>
            </div>
            <div class="border-2 border-gray-300 p-4 w-full">
                <div class="text-3xl">
                    Required Resources
                </div>
                <div class="mt-4 text-xl">
                    <div
                        v-if="displayRequiredResourcesParagraph"
                        v-html="displayRequiredResourcesParagraph"
                    />
                    <div v-else>
                        Required resources paragraph will come here.
                    </div>
                    <div
                        v-for="(heading, index) in displayRequiredResourcesHeadings"
                        :key="index"
                        class="border-2 border-gray-300 mt-4 p-4 rounded-2xl"
                    >
                        <div
                            class="mt-2 text-xl"
                            v-html="heading.content"
                        />
                    </div>
                </div>
            </div>
            <div class="border-2 border-gray-300 p-4 w-full">
                <div class="text-3xl">
                    Other Resources to try
                </div>
                <div class="mt-4 text-xl">
                    Other resources paragraph will come here.
                </div>
            </div>
            <div class="border-2 border-gray-300 p-4 w-full">
                <div class="text-3xl">
                    Planning and preparation
                </div>
                <div class="mt-4 text-xl">
                    Planning Preparation paragraph will come here.
                </div>
            </div>
        </div>
        <div class="mt-6 p-4">
            <div class="text-3xl">
                Task Sequence
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div class="border-2 border-gray-300 mt-4 p-4 rounded-2xl">
                    <div class="text-2xl">
                        Task No. 1
                    </div>
                    <div class="mt-2 text-xl">
                        Task No. 1 content will come here
                    </div>
                </div>
                <div class="border-2 border-gray-300 p-4 rounded-2xl">
                    <div class="text-2xl">
                        Task No. 2
                    </div>
                    <div class="mt-2 text-xl">
                        Task No. 2 content will come here
                    </div>
                </div>
                <div class="border-2 border-gray-300 p-4 rounded-2xl">
                    <div class="text-2xl">
                        Task No. 3
                    </div>
                    <div class="mt-2 text-xl">
                        Task No. 3 content will come here
                    </div>
                </div>
                <div class="border-2 border-gray-300 p-4 rounded-2xl">
                    <div class="text-2xl">
                        Task No. 4
                    </div>
                    <div class="mt-2 text-xl">
                        Task No. 4 content will come here
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 p-4">
            <div class="text-3xl">
                Curriculum Connections
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div class="border-2 border-gray-300 mt-4 p-4 rounded-2xl">
                    <div class="text-2xl">
                        Curriculum Connections No. 1
                    </div>
                    <div class="mt-2 text-xl">
                        Curriculum Connections No. 1 content/lists will come here
                    </div>
                </div>
                <div class="border-2 border-gray-300 p-4 rounded-2xl">
                    <div class="text-2xl">
                        Curriculum Connections No. 2
                    </div>
                    <div class="mt-2 text-xl">
                        Curriculum Connections No. 2 content/lists will come here
                    </div>
                </div>
                <div class="border-2 border-gray-300 p-4 rounded-2xl">
                    <div class="text-2xl">
                        Curriculum Connections No. 3
                    </div>
                    <div class="mt-2 text-xl">
                        Curriculum Connections No. 3 content/lists will come here
                    </div>
                </div>
                <div class="border-2 border-gray-300 p-4 rounded-2xl">
                    <div class="text-2xl">
                        Curriculum Connections No. 4
                    </div>
                    <div class="mt-2 text-xl">
                        Curriculum Connections No. 4 content/lists will come here
                    </div>
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
