<script setup>
import mammoth from 'mammoth';
import {ref} from "vue";



//all initial variables are here
const htmlContent = ref('');
const error = ref('');
const jsonContent = ref({});

//variables used to store json format value
const topicHeading = ref('')
const topicCategory = ref('')
const taskSummaryTitle = ref('')
const taskSummaryParagraph = ref('')
const sessionOverviewTitle = ref('')
const sessionOverivewParagraphs = ref([])
const sessionOverviewSubheadings = ref([])
const sessionOverviewParagraph = ref([])
const digiTechTitle = ref('')
const digiTechListings = ref([])
const requiredResourcesTitle = ref('')
const requiredResourcesParagraph = ref([])
const requiredResourcesListHeadings = ref([])
const requiredResourcesSubListHeadings = ref([])
const requiredResourcesLinksByListHeading = ref([])
const requiredResourcesAllLinks = ref([])




//handle file upload
const handleFileUpload = async (event) => {
    const file = event.target.files[0];
    if (!file) return;
    try {
        const html = await convertToHtml(file);
        htmlContent.value = html;
        error.value = ''; // Clear any previous errors

        extractTextById(htmlContent.value, '_1gy27kj6jprf')
        extractTextById(htmlContent.value, '_1p99sr8cjimz')
        extractTextById(htmlContent.value, '_r9sioprybg6g')
        extractTextById(htmlContent.value, '_o25ffby0w0ip')
        extractTextById(htmlContent.value, '_jhqnd16qn0md')
        extractTextById(htmlContent.value, '_2jqga89deyn')

        extractAllContentByEachId(htmlContent.value, '_r9sioprybg6g')
        extractAllContentByEachId(htmlContent.value, '_o25ffby0w0ip')
        extractAllContentByEachId(htmlContent.value, '_jhqnd16qn0md')
        extractAllContentByEachId(htmlContent.value, '_2jqga89deyn')


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

//filters only text content out-of html data
const stripHtml = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || '';
};

//downloads the json/ts file with objects formatted content out of html
const downloadFormattedJson = () => {
    jsonContent.value = JSON.stringify({
        //arrange the formatting of the json file
        "Topic Heading": topicHeading.value,
        "Topic Category": topicCategory.value,
        "Task Summary": {
            "Title": taskSummaryTitle.value,
            "Paragraphs": taskSummaryParagraph.value
        },
        "Component1": {
            "Session Overview": {
                "Title": sessionOverviewTitle.value,
                "Paragraphs": sessionOverivewParagraphs.value,
                "Sub Headings": sessionOverviewSubheadings.value,
                "Contents": sessionOverviewParagraph.value
            },
            "Digital Technologies": {
                "Title": digiTechTitle.value,
                "Listings": digiTechListings.value
            },
            "Required Resources": {
                "Title": requiredResourcesTitle.value,
                "Paragraphs": requiredResourcesParagraph.value,
                "List Headings": requiredResourcesListHeadings.value,
                "Sub-List Headings": requiredResourcesSubListHeadings.value,
                "Links By List Heading": requiredResourcesLinksByListHeading.value,
                "All Links": requiredResourcesAllLinks.value
            }
        }




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

const extractTextById = (html, id) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const element = doc.getElementById(id);

    // text inside the <h1> from child's <a> tag id
    if (element && element.parentNode && id === "_1gy27kj6jprf") {
        topicHeading.value = element.parentNode.textContent.trim();
    }
    if (element && element.parentNode && id === "_1p99sr8cjimz") {
        topicCategory.value = element.parentNode.textContent.trim();
    }
    if (element && element.parentNode && id === "_r9sioprybg6g") {
        taskSummaryTitle.value = element.parentNode.textContent.trim();
    }
    if (element && element.parentNode && id === "_o25ffby0w0ip") {
        sessionOverviewTitle.value = element.parentNode.textContent.trim();
    }
    if (element && element.parentNode && id === "_jhqnd16qn0md") {
        digiTechTitle.value = element.parentNode.textContent.trim();
    }
    if (element && element.parentNode && id === "_2jqga89deyn") {
        requiredResourcesTitle.value = element.parentNode.textContent.trim();
    }
    else {
        // displayTopicHeading.value = `Content with ID ${id} not found.`;
    }
};

const extractAllContentByEachId = (html, id) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const element = doc.getElementById(id);


    // paragraph <p> inside the sibling of id element
    if(element && element.parentNode.nextSibling) {
        if (id==="_r9sioprybg6g")
        {
            taskSummaryParagraph.value = element.parentNode.nextSibling.textContent.trim();
        }
    }

    // list of paragraphs <p> inside the <th> as sibling of the id element
    if (element && element.parentNode.parentNode.nextSibling) {
        const sibling = element.parentNode.parentNode.nextSibling;
        if (sibling.nodeName === 'TH') {
            const paragraphElements = sibling.querySelectorAll('p');
            paragraphElements.forEach(paragraph => {
                if(id==="_o25ffby0w0ip")
                {
                    sessionOverivewParagraphs.value.push(paragraph.textContent.trim());
                }
            });
        }
    }

    // list of <p> without any children's tags
    if (element && element.parentNode.parentNode.nextSibling && id==="_2jqga89deyn") {
        const sibling = element.parentNode.parentNode.nextSibling;
        if(sibling.nodeName === 'TH') {
            const paragraphElements = sibling.querySelectorAll('p');
            paragraphElements.forEach(paragraph => {
                if (paragraph.children.length === 0) {
                    requiredResourcesParagraph.value.push(paragraph.textContent.trim());
                }
            });
        }
    }

    // list of <strong> inside each list of <p> inside the <th> as sibling of the id element
    if (element && element.parentNode.parentNode.nextElementSibling) {
        const sibling = element.parentNode.parentNode.nextElementSibling;
        if (sibling.nodeName === 'TH') {
            const paragraphElements = sibling.querySelectorAll('p');
            paragraphElements.forEach(paragraph => {
                const strongElements = paragraph.querySelectorAll('strong');
                strongElements.forEach(strong => {
                    if(id === "_o25ffby0w0ip")
                    {
                        sessionOverviewSubheadings.value.push(strong.textContent.trim());
                    }
                    if(id === "_2jqga89deyn")
                    {
                        requiredResourcesListHeadings.value.push(strong.textContent.trim());
                    }
                });
            });
        }
    }

    // list of <p> without the texts inside the <strong> inside the <th> as sibling of the id element
    if (element && element.parentNode.parentNode.nextElementSibling) {
        const sibling = element.parentNode.parentNode.nextElementSibling;
        if (sibling.nodeName === 'TH') {
            const paragraphElements = sibling.querySelectorAll('p');
            paragraphElements.forEach(paragraph => {
                let textContent = '';
                paragraph.childNodes.forEach(node => {
                    if (node.nodeName !== 'STRONG') {
                        textContent += node.textContent.trim();
                    }
                });
                if (id === "_o25ffby0w0ip"){
                    sessionOverviewParagraph.value.push(textContent);
                }
            });
        }
    }

    // list of all the <li> inside the <ul> inside the <th> as sibling of the id element
    if (element && element.parentNode.parentNode.nextElementSibling) {
        const sibling = element.parentNode.parentNode.nextElementSibling;
        if (sibling.nodeName === 'TH') {
            const listItems = sibling.querySelectorAll('ul li');
            listItems.forEach(item => {
                if (id === "_jhqnd16qn0md")
                {
                    digiTechListings.value.push(item.textContent.trim());
                }
            });
        }
    }

    // list of all <li> whose parent <ul> is just next to the list of each <p>
    if (element && element.parentNode.parentNode.nextSibling && id === '_2jqga89deyn') {
        const sibling = element.parentNode.parentNode.nextSibling;
        if (sibling.nodeName === 'TH') {
            const paragraphElements = sibling.querySelectorAll('p');
            paragraphElements.forEach(paragraph => {
                const nextUL = paragraph.nextElementSibling;
                if (nextUL && nextUL.nodeName === 'UL') {
                    const listItems = nextUL.querySelectorAll('li');
                    listItems.forEach(item => {
                        requiredResourcesSubListHeadings.value.push(item.textContent.trim());
                    });
                    // Now listContent array contains all the items inside the ul tags
                }
            });

        }
    }

    // extracting all the links, text, link text on the basis of id
    const topTrTag = element.parentNode.parentNode.parentNode
    const h1Tags = topTrTag.querySelectorAll('h1');
    const strongTags = topTrTag.querySelectorAll('p strong');
    const inSession1 = false; // Flag to track if currently in Session 1
    const inSession2 = false; // Flag to track if currently in Session 2
    const sessionText = ref('')
    if(h1Tags.length > 0 && strongTags.length > 0)
    {
        let idCounter = 0;
        sessionText.value = h1Tags[0].textContent.trim();
        //console.log(h1Tags.length + ' . ' + strongTags.length + ' . ' + sessionText.value);
        for (let j = 0; j < strongTags.length; j++){
            const strongTag = strongTags[j];
            const sessionText = strongTag.parentNode.textContent.trim();
            //console.log(strongTag + (' _ ') + sessionText)

            const pTag = strongTag.parentNode;
            const ulTag = pTag.nextElementSibling;

            if (ulTag && ulTag.tagName.toLowerCase() === 'ul') {
                const liTags = ulTag.querySelectorAll('li');
                liTags.forEach((liTag, index) => {
                    const aTags = liTag.querySelectorAll('a');

                    if (aTags.length > 0) {
                        aTags.forEach(aTag => {
                            const href = aTag.getAttribute('href');
                            const text = aTag.textContent.trim();
                            const list_text = liTag.textContent.trim();
                            // Increment id counter
                            const id = idCounter++;
                            requiredResourcesAllLinks.value.push({ id, text, href, list_text });
                        });
                    }
                });
            }

        }
    }
}

// raw implementation will be here






</script>

<template>
    <div class="ml-10 mt-36">
        <input
            type="file"
            accept=".doc,.docx"
            @change="handleFileUpload"
        >
        <div
            v-if="htmlContent"
            class="mt-12 text-xl"
        >
            Html content generated Successfully!!
        </div>
        <div
            v-if="error"
            class="error"
        >
            {{ error }}
        </div>
    </div>
    <div>
        <div
            class="mt-14"
        >
            <div
                id="sessionOverview"
                v-html="displayedJsonContent"
            />
        </div>
    </div>
    <div class="border-2 border-black border-dashed h-full mt-12 p-6">
        <button
            class="border-2 border-black border-dashed p-2 rounded-2xl"
            @click="displaySelectedContent"
        >
            Display required content from JSON file
        </button>
        <div
            v-if="htmlContent"
            class="mt-10"
        >
            <div class="flex flex-row">
                <div class="w-[35%]">
                    Below buttons are for debuging purpose only.
                </div>
                <div
                    v-if="htmlContent"
                    class="border-2 border-dashed border-gray-400 h-[0.5px] mt-3 w-full"
                />
            </div>
            <div>
                <div class="mt-10">
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
                        Download row content JSON
                    </button>
                    <button
                        class="border-2 border-black ml-20 p-2"
                        @click="downloadFormattedJson"
                    >
                        Download required content in JSON
                    </button>
                    <button
                        v-if="Object.keys(jsonContent).length > 0"
                        class="border-2 border-black ml-20 p-2"
                        @click="displayRawJsonContent"
                    >
                        Display content below
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div
        class="border-2 border-gray-200 mt-10"
    />
    <div>
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
                <div class="border-2 border-blue-600 p-4 rounded-2xl w-full">
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
                <div class="border-2 border-green-600 p-4 rounded-2xl w-full">
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
                <div class="border-2 border-red-600 p-4 rounded-2xl w-full">
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
                            class="border-2 border-red-200 mt-4 p-4 rounded-2xl"
                        >
                            <div
                                class="font-bold mb-2 mt-2 text-2xl"
                                v-html="heading.content"
                            />
                            <!-- Check if the current heading is "Hardware" -->
                            <template v-if="heading.content === 'Hardware'">
                                <div class="grid grid-cols-2 gap-10">
                                    <div class="flex flex-row">
                                        <div
                                            v-if="displayRRHardwareListS2"
                                            class="mb-1"
                                        />
                                        <div v-html="displayRRHardwareListS1" />
                                    </div>
                                    <div class="flex flex-row">
                                        <div
                                            v-if="displayRRHardwareListS2"
                                            class="mb-1"
                                        />
                                        <div v-html="displayRRHardwareListS2" />
                                    </div>
                                </div>
                            </template>
                            <!-- Check if the current heading is "Apps" -->
                            <template v-if="heading.content === 'Apps' || heading.content === 'App'">
                                <div class="flex flex-col gap-10">
                                    <div class="flex flex-row">
                                        <div
                                            v-if="displayAppsListS1"
                                            class="mb-1"
                                        />
                                        <div v-html="displayAppsListS1" />
                                    </div>
                                    <div class="flex flex-row">
                                        <div
                                            v-if="displayAppsListS2"
                                        />
                                        <div v-html="displayAppsListS2" />
                                    </div>
                                </div>
                            </template>
                            <!-- Check if the current heading is "Teaching Resources" -->
                            <template v-if="heading.content === 'Teaching resources'">
                                <div class="flex flex-col gap-10">
                                    <div class="flex flex-row">
                                        <div
                                            v-if="displayTeachingResourcesListS1"
                                        />
                                        <div v-html="displayTeachingResourcesListS1" />
                                    </div>
                                    <div class="flex flex-row">
                                        <div
                                            v-if="displayTeachingResourcesListS2"
                                        />
                                        <div v-html="displayTeachingResourcesListS2" />
                                    </div>
                                </div>
                            </template>
                            <!-- Check if the current heading is "VR videos" -->
                            <template v-if="heading.content === 'VR videos' || heading.content === 'Videos' || heading.content === 'VR Videos'">
                                <div class="flex flex-col gap-10">
                                    <div class="flex flex-row">
                                        <!-- Loop over video titles -->
                                        <div v-if="displayVRVideosListText">
                                            <div
                                                v-for="(videoTitle, index) in displayVRVideosListText"
                                                :key="index"
                                            >
                                                <div v-html="videoTitle" />
                                                <!-- Display corresponding video link -->
                                                <iframe
                                                    v-if="displayVRVideosListS1Link[index]"
                                                    :key="'video_' + index"
                                                    class="bg-black mb-20 mt-4 rounded-3xl"
                                                    width="480"
                                                    height="360"
                                                    :src="getYouTubeEmbedUrl(extractVideoId(displayVRVideosListS1Link[index]))"
                                                    title="Mitosis in an animal cell Under the Microscope"
                                                    frameborder="0"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                    referrerpolicy="strict-origin-when-cross-origin"
                                                    allowfullscreen
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="border-2 border-blue-600 p-4 rounded-2xl w-full">
                    <div class="text-3xl">
                        Other Resources to try
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
                            class="border-2 border-blue-200 mt-4 p-4 rounded-2xl"
                        >
                            <div
                                class="font-bold mb-2 mt-2 text-2xl"
                                v-html="heading.content"
                            />
                            <!-- Check if the current heading is "Hardware" -->
                            <template v-if="heading.content === 'Hardware'">
                                <div v-html="displayORHardwareListS1" />
                            </template>
                        </div>
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
        </div>
    </div>
</template>

<style scoped>
.error {
    color: red;
}
</style>
