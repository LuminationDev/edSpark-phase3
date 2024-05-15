<script setup>
import mammoth from 'mammoth';
import {ref} from "vue";

// linked JSON File
import { data } from './dataJson'

// all initial variables are here
const htmlContent = ref('');
const error = ref('');
const jsonContent = ref({});

// variables used to store json format value
const topicHeading = ref('')
const topicCategory = ref('')
const learningArea = ref('')
const yearLevel = ref('')
const duration = ref('')
const taskSummaryTitle = ref('')
const taskSummaryParagraph = ref('')
const sessionOverviewTitle = ref('')
const sessionOverviewParagraphs = ref([])
const sessionOverviewSubheadings = ref([])
const sessionOverviewParagraph = ref([])
const digiTechTitle = ref('')
const digiTechListings = ref([])
const requiredResourcesTitle = ref('')
const requiredResourcesParagraph = ref([])
const requiredResourcesListHeadings = ref([])
const requiredResourcesSubListHeadings = ref([])
const requiredResourcesListingSubListing = ref([])
const requiredResourcesAllLinks = ref([])
const otherResourcesTitle = ref('')
const otherResourcesParagraph = ref([])
const otherResourcesListHeadings = ref([])
const otherResourcesSubListHeadings = ref([])
const otherResourcesListingSubListing = ref([])
const otherResourcesAllLinks = ref([])
const planningPreparationTitle = ref('')
const planningPreparationSubTitleParagraph = ref([])
const planningPreparationParagraph = ref([])
const planningPreparationListHeadings = ref([])
const planningPreparationSubListHeadings = ref([])
const planningPreparationListingSubListing = ref([])
const planningPreparationListingTree = ref([])
const planningPreparationAllLinks = ref([])
const taskSequenceText = ref('')
const introductoryNumbering = ref("")
const introductoryTitle = ref("")
const introductoryTime = ref("")
const introductoryParagraph = ref([])
const introductoryHeading = ref([])
const introductoryListing = ref([])
const introductoryImageLinks = ref([])
const introductoryLinks = ref([])
const priorKnowledgeNumbering = ref("")
const priorKnowledgeTitle = ref("")
const priorKnowledgeTime = ref("")
const priorKnowledgeParagraph = ref([])
const priorKnowledgeHeading = ref([])
const priorKnowledgeListing = ref([])
const priorKnowledgeImageLinks = ref([])
const priorKnowledgeLinks = ref([])
const activitiesNumbering = ref("")
const activitiesTitle = ref("")
const activitiesTime = ref("")
const activitiesParagraph = ref([])
const activitiesHeading = ref([])
const activitiesListing = ref([])
const activitiesImageLinks = ref([])
const activitiesLinks = ref([])
const checkUnderstandingNumbering = ref("")
const checkUnderstandingTitle = ref("")
const checkUnderstandingTime = ref("")
const checkUnderstandingParagraph = ref([])
const checkUnderstandingHeading = ref([])
const checkUnderstandingListing = ref([])
const checkUnderstandingImageLinks = ref([])
const checkUnderstandingLinks = ref([])
const curriculumText = ref("")
const ausCurriculumTitle = ref("")
const ausCurriculumParagraph = ref([])
const ausCurriculumHeadings = ref([])
const ausCurriculumListing = ref([])
const ausCurriculumListingTree = ref([])
const ausCurriculumLinks = ref([])
const crossCurriculumTitle = ref("")
const crossCurriculumParagraph = ref([])
const crossCurriculumHeadings = ref([])
const crossCurriculumListing = ref([])
const crossCurriculumListingTree = ref([])
const crossCurriculumLinks = ref([])
const generalCapabilitiesTitle = ref("")
const generalCapabilitiesParagraph = ref([])
const generalCapabilitiesHeadings = ref([])
const generalCapabilitiesListing = ref([])
const generalCapabilitiesListingTree = ref([])
const generalCapabilitiesLinks = ref([])

// variables used to display on the template
const topicHeading_Display = ref('')
const topicCategory_Display = ref('')
const learningArea_Display = ref('')
const yearLevel_Display = ref('')
const duration_Display = ref('')
const taskSummaryTitle_Display = ref('')
const taskSummaryParagraph_Display = ref('')
const sessionOverviewTitle_Display = ref('')
const sessionOverviewParagraphs_Display = ref('')
const sessionOverviewSubheadings_Display = ref([])
const digiTechTitle_Display = ref('')
const digiTechListings_Display = ref('')
const requiredResourcesTitle_Display = ref('')
const requiredResourcesParagraph_Display = ref('')
const requiredResourcesListHeadings_Display = ref([])
const requiredResourcesSubListHeadings_Display = ref([])
const requiredResourcesListingSubListing_Display = ref([])
const requiredResourcesAllLinks_Display = ref([])
const otherResourcesTitle_Display = ref('')
const otherResourcesParagraph_Display = ref([])
const otherResourcesListHeadings_Display = ref([])
const otherResourcesSubListHeadings_Display = ref('')
const otherResourcesListingSubListing_Display = ref('')
const otherResourcesAllLinks_Display = ref([])
const planningPreparationTitle_Display = ref('')
const planningPreparationListingTree_Display = ref('')
const taskSequenceText_Display = ref('')
const introductoryNumbering_Display = ref('')
const introductoryTitle_Display = ref("")
const introductoryTime_Display = ref("")
const introductoryParagraph_Display = ref([])
const introductoryHeading_Display = ref([])
const introductoryListing_Display = ref([])
const introductoryImageLinks_Display = ref([])
const introductoryLinks_Display = ref([])
const priorKnowledgeNumbering_Display = ref("")
const priorKnowledgeTitle_Display = ref("")
const priorKnowledgeTime_Display = ref("")
const priorKnowledgeParagraph_Display = ref([])
const priorKnowledgeHeading_Display = ref([])
const priorKnowledgeListing_Display = ref([])
const priorKnowledgeImageLinks_Display = ref([])
const priorKnowledgeLinks_Display = ref([])
const activitiesNumbering_Display = ref("")
const activitiesTitle_Display = ref("")
const activitiesTime_Display = ref("")
const activitiesParagraph_Display = ref([])
const activitiesHeading_Display = ref([])
const activitiesListing_Display = ref([])
const activitiesImageLinks_Display = ref([])
const activitiesLinks_Display = ref([])
const checkUnderstandingNumbering_Display = ref("")
const checkUnderstandingTitle_Display = ref("")
const checkUnderstandingTime_Display = ref("")
const checkUnderstandingParagraph_Display = ref([])
const checkUnderstandingHeading_Display = ref([])
const checkUnderstandingListing_Display = ref([])
const checkUnderstandingImageLinks_Display = ref([])
const checkUnderstandingLinks_Display = ref([])
const curriculumText_Display = ref("")
const ausCurriculumTitle_Display = ref("")
const ausCurriculumParagraph_Display = ref([])
const ausCurriculumHeadings_Display = ref([])
const ausCurriculumListing_Display = ref([])
const ausCurriculumListingTree_Display = ref([])
const ausCurriculumLinks_Display = ref([])
const crossCurriculumTitle_Display = ref("")
const crossCurriculumParagraph_Display = ref([])
const crossCurriculumHeadings_Display = ref([])
const crossCurriculumListing_Display = ref([])
const crossCurriculumListingTree_Display = ref([])
const crossCurriculumLinks_Display = ref([])
const generalCapabilitiesTitle_Display = ref("")
const generalCapabilitiesParagraph_Display = ref([])
const generalCapabilitiesHeadings_Display = ref([])
const generalCapabilitiesListing_Display = ref([])
const generalCapabilitiesListingTree_Display = ref([])
const generalCapabilitiesLinks_Display = ref([])

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
        extractTextById(htmlContent.value, '_fmhbwmb7feqy')
        extractTextById(htmlContent.value, '_2jqga89deyn')
        extractTextById(htmlContent.value, '_jkhi5jchiyqa')
        extractTextById(htmlContent.value, '_kv7kogslxlmu')
        extractTextById(htmlContent.value, '_8hqu05q343sr')

        extractAllContentByEachId(htmlContent.value, '_1gy27kj6jprf')
        extractAllContentByEachId(htmlContent.value, '_r9sioprybg6g')
        extractAllContentByEachId(htmlContent.value, '_o25ffby0w0ip')
        extractAllContentByEachId(htmlContent.value, '_jhqnd16qn0md')
        extractAllContentByEachId(htmlContent.value, '_fmhbwmb7feqy')
        extractAllContentByEachId(htmlContent.value, '_2jqga89deyn')
        extractAllContentByEachId(htmlContent.value, '_jkhi5jchiyqa')
        extractAllContentByEachId(htmlContent.value, '_kv7kogslxlmu')
        extractAllContentByEachId(htmlContent.value, '_8hqu05q343sr')
        extractAllContentByEachId(htmlContent.value, '_14z3n7fivubg')

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
    a.download = (topicHeading.value + '.html');
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
    a.download = (topicHeading.value + '.json');
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
        "Learning Area": learningArea.value,
        "Year Level": yearLevel.value,
        "Duration": duration.value,
        "Task Summary": {
            "Title": taskSummaryTitle.value,
            "Paragraphs": taskSummaryParagraph.value
        },
        "Component1": {
            "Session Overview": {
                "Title": sessionOverviewTitle.value,
                "Paragraphs": sessionOverviewParagraphs.value,
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
                "Lists with Sub-Lists": requiredResourcesListingSubListing.value,
                "All Links": requiredResourcesAllLinks.value
            },
            "Other Resources": {
                "Title": otherResourcesTitle.value,
                "Paragraphs": otherResourcesParagraph.value,
                "List Headings": otherResourcesListHeadings.value,
                "Sub-List Headings": otherResourcesSubListHeadings.value,
                "Lists with Sub-Lists": otherResourcesListingSubListing.value,
                "All Links": otherResourcesAllLinks.value
            },
            "Planning And Preparation": {
                "Title": planningPreparationTitle.value,
                "Sub-Title Paragraph": planningPreparationSubTitleParagraph.value,
                "Paragraphs": planningPreparationParagraph.value,
                "List Headings": planningPreparationListHeadings.value,
                "Sub-List Headings": planningPreparationSubListHeadings.value,
                "Lists with Sub-Lists": planningPreparationListingSubListing.value,
                "Lists Tree": planningPreparationListingTree.value,
                "All Links": planningPreparationAllLinks.value
            }
        },
        "Component2": {
            "Main_Heading": taskSequenceText.value,
            "Introductory Activity": {
                "Number": introductoryNumbering.value,
                "Title": introductoryTitle.value,
                "Duration": introductoryTime.value,
                "Paragraphs": introductoryParagraph.value,
                "List Headings": introductoryHeading.value,
                "Listings": introductoryListing.value,
                "Image Links": introductoryImageLinks.value,
                "All Links": introductoryLinks.value
            },
            "Prior Knowledge Check": {
                "Number": priorKnowledgeNumbering.value,
                "Title": priorKnowledgeTitle.value,
                "Duration": priorKnowledgeTime.value,
                "Paragraphs": priorKnowledgeParagraph.value,
                "List Headings": priorKnowledgeHeading.value,
                "Listings": priorKnowledgeListing.value,
                "Image Links": priorKnowledgeImageLinks.value,
                "All Links": priorKnowledgeLinks.value
            },
            "Activities": {
                "Number": activitiesNumbering.value,
                "Title": activitiesTitle.value,
                "Duration": activitiesTime.value,
                "Paragraphs": activitiesParagraph.value,
                "List Headings": activitiesHeading.value,
                "Listings": activitiesListing.value,
                "Image Links": activitiesImageLinks.value,
                "All Links": activitiesLinks.value
            },
            "Check Understanding": {
                "Number": checkUnderstandingNumbering.value,
                "Title": checkUnderstandingTitle.value,
                "Duration": checkUnderstandingTime.value,
                "Paragraphs": checkUnderstandingParagraph.value,
                "List Headings": checkUnderstandingHeading.value,
                "Listings": checkUnderstandingListing.value,
                "Image Links": checkUnderstandingImageLinks.value,
                "All Links": checkUnderstandingLinks.value
            }
        },
        "Component3": {
            "Main_Heading": curriculumText.value,
            "Australian Curriculum": {
                "Title": ausCurriculumTitle.value,
                "Paragraphs": ausCurriculumParagraph.value,
                "List Headings": ausCurriculumHeadings.value,
                "Listings": ausCurriculumListing.value,
                "Lists Tree": ausCurriculumListingTree.value,
                "Links": ausCurriculumLinks.value
            },
            "Cross Curriculum": {
                "Title": crossCurriculumTitle.value,
                "Paragraphs": crossCurriculumParagraph.value,
                "List Headings": crossCurriculumHeadings.value,
                "Listings": crossCurriculumListing.value,
                "Lists Tree": crossCurriculumListingTree.value,
                "Links": crossCurriculumLinks.value
            },
            "General Capabilities": {
                "Title": generalCapabilitiesTitle.value,
                "Paragraphs": generalCapabilitiesParagraph.value,
                "List Headings": generalCapabilitiesHeadings.value,
                "Listings": generalCapabilitiesListing.value,
                "Lists Tree": generalCapabilitiesListingTree.value,
                "Links": generalCapabilitiesLinks.value
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
    a.download = (topicHeading.value + '.ts'); // Setting download filename (Topic Heading) with extension (.ts)
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
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
    if (element && element.parentNode && id === "_fmhbwmb7feqy") {
        digiTechTitle.value = element.parentNode.textContent.trim();
    }
    if (element && element.parentNode && id === "_2jqga89deyn") {
        requiredResourcesTitle.value = element.parentNode.textContent.trim();
    }
    if (element && element.parentNode && id === "_jkhi5jchiyqa") {
        otherResourcesTitle.value = element.parentNode.textContent.trim();
    }
    if (element && element.parentNode && id === "_kv7kogslxlmu") {
        planningPreparationTitle.value = element.parentNode.textContent.trim();
    }
    if (element && element.parentNode && id === "_8hqu05q343sr") {
        taskSequenceText.value = element.parentNode.textContent.trim();
    }
    else {
        // displayTopicHeading.value = `Content with ID ${id} not found.`;
    }
};

const extractAllContentByEachId = (html, id) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const element = doc.getElementById(id);

    // extracting Learning Area, Year Level and Duration text.
    if (element && element.parentElement.parentElement.parentElement) {
        const next_TH= element.parentElement.nodeName === 'P' ? element.parentElement.parentElement ? element.parentElement.parentElement.nextElementSibling : null : null;
        const LA_Text = next_TH ? next_TH.querySelector('p') ? next_TH.querySelector('p').textContent.trim() : null : null;
        const first_TR_Element = element.parentElement.parentElement ? element.parentElement.parentElement.parentElement : null;
        const second_TR_Element = first_TR_Element.nextElementSibling ? first_TR_Element.nextElementSibling.nodeName === 'TR' ? first_TR_Element.nextElementSibling : null : null;
        const LA_Text_Result = second_TR_Element ? second_TR_Element.querySelector('th').querySelector('p') ? second_TR_Element.querySelector('th').querySelector('p').textContent.trim() : null : null;
        const third_TR_Element = second_TR_Element ? second_TR_Element.nextElementSibling ? second_TR_Element.nextElementSibling.nodeName === 'TR' ? second_TR_Element.nextElementSibling : null : null : null;
        const YL_Text = third_TR_Element ? third_TR_Element.querySelector('th').querySelector('p') ? third_TR_Element.querySelector('th').querySelector('p').textContent.trim() : null : null;
        const forth_TR_Element = third_TR_Element ? third_TR_Element.nextElementSibling ? third_TR_Element.nextElementSibling.nodeName === 'TR' ? third_TR_Element.nextElementSibling : null : null : null;
        const YL_Text_Result = forth_TR_Element ? forth_TR_Element.querySelector('th').querySelector('p') ? forth_TR_Element.querySelector('th').querySelector('p').textContent.trim() : null : null;
        const fifth_TR_Element = forth_TR_Element ? forth_TR_Element.nextElementSibling ? forth_TR_Element.nextElementSibling.nodeName === 'TR' ? forth_TR_Element.nextElementSibling : null : null : null;
        const Duration_Text = fifth_TR_Element ? fifth_TR_Element.querySelector('th').querySelector('p') ? fifth_TR_Element.querySelector('th').querySelector('p').textContent.trim() : null : null;
        const sixth_TR_Element = fifth_TR_Element ? fifth_TR_Element.nextElementSibling ? fifth_TR_Element.nextElementSibling.nodeName === 'TR' ? fifth_TR_Element.nextElementSibling : null : null : null;
        const Duration_Text_Result = sixth_TR_Element ? sixth_TR_Element.querySelector('th').querySelector('p') ? sixth_TR_Element.querySelector('th').querySelector('p').textContent.trim() : null : null;
        // extracting on different conditions
        if (LA_Text ? LA_Text.toLowerCase().includes('learning') : null) {
            learningArea.value = LA_Text_Result;
        }
        if (YL_Text ? YL_Text.toLowerCase().includes('year') : null) {
            yearLevel.value = YL_Text_Result;
        }
        if (Duration_Text ? Duration_Text.toLowerCase().includes('duration') : null) {
            duration.value = Duration_Text_Result;
        }
    }

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
                    sessionOverviewParagraphs.value.push(paragraph.textContent.trim());
                }
            });
        }
    }

    // list of <p> without any children's tags
    if (element && element.parentNode.parentNode.nextSibling) {
        const sibling = element.parentNode.parentNode.nextSibling;
        if(sibling.nodeName === 'TH') {
            const paragraphElements = sibling.querySelectorAll('p');
            paragraphElements.forEach(paragraph => {
                if (paragraph.children.length === 0) {
                    if (id==="_2jqga89deyn") {
                        requiredResourcesParagraph.value.push(paragraph.textContent.trim());
                    }
                    if (id==="_jkhi5jchiyqa") {
                        otherResourcesParagraph.value.push(paragraph.textContent.trim());
                    }
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
                    if (id === "_o25ffby0w0ip")
                    {
                        sessionOverviewSubheadings.value.push(strong.textContent.trim().replace(/:/g, ''));
                    }
                    if (id === "_2jqga89deyn")
                    {
                        requiredResourcesListHeadings.value.push(strong.textContent.trim().replace(/:/g, ''));
                    }
                    if (id === "_jkhi5jchiyqa")
                    {
                        otherResourcesListHeadings.value.push(strong.textContent.trim().replace(/:/g, ''));
                    }
                    if (id === "_kv7kogslxlmu")
                    {
                        planningPreparationListHeadings.value.push(strong.textContent.trim().replace(/:/g, ''));
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
                if (id === "_fmhbwmb7feqy")
                {
                    digiTechListings.value.push(item.textContent.trim());
                }
            });
        }
    }

    // list of all <li> whose parent <ul> is just next to the list of each <p>
    if (element && element.parentNode.parentNode.nextSibling) {
        const sibling = element.parentNode.parentNode.nextSibling;
        if (sibling.nodeName === 'TH') {
            const paragraphElements = sibling.querySelectorAll('p');
            paragraphElements.forEach(paragraph => {
                const nextUL = paragraph.nextElementSibling;
                if (nextUL && nextUL.nodeName === 'UL') {
                    const listItems = nextUL.querySelectorAll('li');
                    listItems.forEach(item => {
                        if (id === '_2jqga89deyn'){
                            requiredResourcesSubListHeadings.value.push(item.textContent.trim());
                        }
                        if (id === '_jkhi5jchiyqa'){
                            otherResourcesSubListHeadings.value.push(item.textContent.trim())
                        }
                        if (id === '_kv7kogslxlmu'){
                            planningPreparationSubListHeadings.value.push(item.textContent.trim())
                        }
                    });
                    // Now listContent array contains all the items inside the ul tags
                }
            });
        }
    }

    // extracting all the [links, text, link] (with assigning keys), on the basis of different id's
    const topTrTag = element ? element.parentNode ? element.parentNode.parentNode ? element.parentNode.parentNode.parentNode : null : null : null;
    const h1Tags = topTrTag ? topTrTag.querySelectorAll('h1') : null;
    const pTags = topTrTag ? topTrTag .querySelectorAll('p') : null;
    const sessionText = ref('')
    if((h1Tags ? h1Tags.length > 0 : null) && (pTags ? pTags.length > 0 : null))
    {
        let keyCounter = 0;
        sessionText.value = h1Tags[0].textContent.trim();
        for (let j = 0; j < pTags.length; j++){
            const pTag = pTags[j];
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
                            const key = keyCounter++;
                            if (id === '_2jqga89deyn') {
                                requiredResourcesAllLinks.value.push({ key, text, href, list_text });
                            }
                            if (id === '_jkhi5jchiyqa') {
                                otherResourcesAllLinks.value.push({ key, text, href, list_text });
                            }
                            if (id === '_kv7kogslxlmu') {
                                planningPreparationAllLinks.value.push({ key, text, href, list_text });
                            }
                        });
                    }
                });
            }
        }
    }

    // extracting Sub-Listing with their each Listing in json format
    if (element && element.parentNode.parentNode.nextElementSibling) {
        const sibling = element.parentNode.parentNode.nextElementSibling;
        if (sibling.nodeName === 'TH') {
            // Extract Sub-List Headings and their respective list items
            const ulElements = sibling.querySelectorAll('ul');
            ulElements.forEach(ulElement => {
                const strongElement = ulElement.previousElementSibling;
                const pElement = ulElement.previousElementSibling;
                // make <strong> that is inside <p> as heading, that is just before <ul>
                if (strongElement && strongElement.nodeName === 'P' && strongElement.querySelector('strong'))
                {
                    const strongText = strongElement.querySelector('strong').textContent.trim();
                    const subListItems = Array.from(ulElement.querySelectorAll('li')).map(li => {
                        const linkElement = li.querySelector('a');
                        if (linkElement) {
                            return {
                                "text": li.textContent.trim(),
                                "link": linkElement.getAttribute('href')
                            };
                        } else {
                            return {
                                "text": li.textContent.trim(),
                                "link": {}
                            };
                        }
                    });
                    if (id === '_2jqga89deyn'){
                        requiredResourcesListingSubListing.value.push({ [strongText]: subListItems });
                    }
                    if (id === '_jkhi5jchiyqa'){
                        otherResourcesListingSubListing.value.push({ [strongText]: subListItems });
                    }
                    if (id === '_kv7kogslxlmu'){
                        planningPreparationListingSubListing.value.push({ [strongText]: subListItems });
                    }
                }
                // make <p> as heading if <strong> inside <p> is not found, just before <ul>
                else if (pElement && pElement.nodeName === 'P') {
                    const pText = pElement.textContent.trim();
                    const subListItems = Array.from(ulElement.querySelectorAll('li')).map(li => {
                        const linkElement = li.querySelector('a');
                        if (linkElement) {
                            return {
                                "text": li.textContent.trim(),
                                "link": linkElement.getAttribute('href')
                            };
                        } else {
                            return {
                                "text": li.textContent.trim(),
                                "link": {}
                            };
                        }
                    });
                    if (id === '_kv7kogslxlmu'){
                        planningPreparationListingSubListing.value.push({ [pText]: subListItems });
                    }
                }
            });
        }
    }

    // extracting <em> from each parent <p> that is the sibling of <h1>
    if (element && element.parentNode.nextSibling) {
        const sibling = element.parentNode.nextSibling;
        if(sibling.nodeName === 'P') {
            const emElements = sibling.querySelectorAll('em');
            emElements.forEach(emElement => {
                if (id === '_kv7kogslxlmu')
                {
                    planningPreparationSubTitleParagraph.value = emElement.textContent.trim();
                }
            })
        }
    }

    // extracting list within another listing - nested listing
    if (element && element.parentNode.parentNode.nextElementSibling) {
        const sibling = element.parentNode.parentNode.nextElementSibling;
        if (sibling.nodeName === 'TH') {
            const ulElements = sibling.querySelectorAll('ul');
            ulElements.forEach(ulElement => {
                const strong1Element = ulElement.previousElementSibling;
                const strong2Element = ulElement.previousElementSibling ? ulElement.previousElementSibling.previousElementSibling : null;
                const strong1 = strong1Element ? strong1Element.nodeName === 'ul' : null;
                const strong2 = strong2Element ? strong2Element.querySelector('strong') : null;
                let hierarchicalFormat;
                // all paragraphs text (without <strong>) under the id element.
                const paragraphTextOnly = [];
                for (const child of sibling.children) {
                    if (child.nodeName === 'P' && !child.querySelector('strong')) {
                        paragraphTextOnly.push(child.textContent.trim());
                    }
                }

                // extracting <p><strong> that are 1 previous sibling to the each <ul>, but should not have two <p><strong> above the each <ul>
                if (strong1Element && strong1Element.nodeName === 'P' && strong1Element.querySelector('strong') && (strong1 || !strong2)) {
                    const strongText = strong1Element.querySelector('strong').textContent.trim();
                    const pText = strong1Element ? strong1Element.textContent.trim() : null;
                    const nodeNameFound = strong1Element.previousElementSibling ? strong1Element.previousElementSibling.nodeName === 'P' : null;
                    const paragraphTextBeforeStrong1Element = nodeNameFound ? strong1Element.previousElementSibling.textContent.trim() : null;
                    const subListItems = Array.from(ulElement.children).map(listItem => {
                        let textContent = '';
                        // extract <li> text without nested <ul>
                        if (listItem.nodeName === 'LI' && listItem.querySelector('ul')) {
                            listItem.childNodes.forEach(ulNode => {
                                if (ulNode.nodeName !== 'UL') {
                                    textContent = ulNode.textContent.trim();
                                }
                            })
                        }
                        const subList = listItem.querySelector('ul');
                        if (subList) {
                            const subListItems = Array.from(subList.querySelectorAll('li')).map(subListItem => {
                                return {
                                    [subListItem.textContent.trim() ]: { "List Items:": [] }
                                };
                            });
                            return {
                                [ listItem.textContent.trim() ]: { "Layer 2 List Items:": subListItems }
                            };
                        } else {
                            return {
                                [ listItem.textContent.trim() ]: { "List Items:": [] }
                            };
                        }
                    });
                    planningPreparationParagraph.value = paragraphTextOnly;
                    hierarchicalFormat = {
                        [ strongText ]: { "List Items:": subListItems }
                    };
                    if (strongText === 'session 2') {
                        hierarchicalFormat[pText] = hierarchicalFormat[strongText];
                        delete hierarchicalFormat[strongText];
                    }
                    if (strongText.startsWith('Additional'))
                    {
                        //console.log(paragraphTextBeforeStrong1Element)
                    }

                    if (id === "_kv7kogslxlmu")
                    {
                        planningPreparationListingTree.value.push(hierarchicalFormat);

                    }
                }
                // extracting <p><strong> that are 2 previous sibling to the each <ul>
                if (strong2Element && strong2Element.nodeName === 'P' && strong2Element.querySelector('strong')) {
                    const strongText = strong2Element ? strong2Element.querySelector('strong').textContent.trim() : null;
                    const pText =  strong2Element ? strong2Element.nextElementSibling.textContent.trim() : null;
                    const subListItems = Array.from(ulElement.children).map(listItem => {
                        const subList = listItem.querySelector('ul');
                        if (subList) {
                            const subListItems = Array.from(subList.querySelectorAll('li')).map(subListItem => {
                                return {
                                    [subListItem.textContent.trim()]: {"List Items": []}
                                };
                            });
                            return {
                                [listItem.textContent.trim()]: {"List Items": subListItems}
                            };
                        } else {
                            return {
                                [listItem.textContent.trim()]:{"List Items": []}
                            };
                        }
                    });
                    const hierarchicalLayer2 = {
                        "List Items": subListItems
                    };
                    const hierarchicalLayer1 = {
                        [strongText]: { [pText]: hierarchicalLayer2}
                    };
                    if(id === "_kv7kogslxlmu")
                    {
                        planningPreparationListingTree.value.push(hierarchicalLayer1);
                    }
                }
            });
        }
    }

    // extracts the Number, Title, and Duration of 'Task Sequence' on the basis of <br> inside <p> inside <th> inside <tr> inside <thead> inside <table> that is sibling of parent element of the ID Element
    if (element && element.parentElement.nextElementSibling) {
        const siblingTable = element.parentElement.nextElementSibling;
        if (siblingTable.nodeName === 'TABLE') {
            const theadElement = siblingTable.querySelector('thead');
            const trElement = theadElement.querySelectorAll('tr');
            trElement.forEach(tr => {
                let countTH = 0;
                tr.childNodes.forEach(th => {
                    countTH++;
                    th.childNodes.forEach(p => {
                        const pText = p.textContent.trim();
                        const nextSib = p ? p.nextSibling : null;
                        const nextSibP = nextSib ? (p.nextSibling.nodeName === "P" ? p.nextSibling.textContent.trim() : null) : null;
                        const previousSib = p ? p.previousSibling : null;
                        const previousSibP = previousSib ? ((p.previousSibling.nodeName === "P" && p.previousSibling.textContent.length === 1) ? p.previousSibling.textContent.trim() : null) : null;
                        let count = 0;
                        p.childNodes.forEach(br => {
                            const brText = br.textContent.trim();
                            count++;
                            if (countTH === 1) {        // checks for the first TH only that has numbers, title, and duration of task sequence
                                if (pText.toLowerCase().includes('provocation')) {
                                    if (count === 1  && !previousSibP) {
                                        introductoryNumbering.value = brText;
                                    }
                                    if (previousSibP) {
                                        introductoryNumbering.value = previousSibP;
                                    }
                                    if (count === 3 && !previousSibP) {
                                        introductoryTitle.value = brText;
                                    }
                                    if (count === 1 && previousSibP) {
                                        introductoryTitle.value = brText;
                                    }
                                    if (count === 5  && !previousSibP) {
                                        introductoryTime.value = brText;
                                    }
                                    if (count === 3 && previousSibP) {
                                        introductoryTime.value = brText;
                                    }
                                    if (nextSibP) {
                                        introductoryTime.value = nextSibP;
                                    }
                                }
                                if (pText.toLowerCase().includes('prior') || pText.toLowerCase().includes('pre') || pText.toLowerCase().includes('discussion')) {
                                    if (count === 1  && !previousSibP && brText) {
                                        priorKnowledgeNumbering.value = brText;
                                    }
                                    if (previousSibP) {
                                        priorKnowledgeNumbering.value = previousSibP;
                                    }
                                    if (count === 3 && !previousSibP) {
                                        priorKnowledgeTitle.value = brText;
                                    }
                                    if (count === 1 && previousSibP) {
                                        priorKnowledgeTitle.value = brText;
                                    }
                                    if (count === 5  && !previousSibP) {
                                        priorKnowledgeTime.value = brText;
                                    }
                                    if (count === 3 && previousSibP) {
                                        priorKnowledgeTime.value = brText;
                                    }
                                    if (nextSibP) {
                                        priorKnowledgeTime.value = nextSibP;
                                    }
                                }
                                if (pText.toLowerCase().includes('activities')) {
                                    if (count === 1  && !previousSibP) {
                                        activitiesNumbering.value = brText;
                                    }
                                    if (previousSibP) {
                                        activitiesNumbering.value = previousSibP;
                                    }
                                    if (count === 3 && !previousSibP) {
                                        activitiesTitle.value = brText;
                                    }
                                    if (count === 1 && previousSibP) {
                                        activitiesTitle.value = brText;
                                    }
                                    if (count === 5  && !previousSibP) {
                                        activitiesTime.value = brText;
                                    }
                                    if (count === 3 && previousSibP) {
                                        activitiesTime.value = brText;
                                    }
                                    if (nextSibP) {
                                        activitiesTime.value = nextSibP;
                                    }
                                }
                                if (pText.toLowerCase().includes('understanding')) {
                                    if (count === 1  && !previousSibP) {
                                        checkUnderstandingNumbering.value = brText;
                                    }
                                    if (previousSibP) {
                                        checkUnderstandingNumbering.value = previousSibP;
                                    }
                                    if (count === 3 && !previousSibP) {
                                        checkUnderstandingTitle.value = brText;
                                    }
                                    if (count === 1 && previousSibP) {
                                        checkUnderstandingTitle.value = brText;
                                    }
                                    if (count === 5  && !previousSibP) {
                                        checkUnderstandingTime.value = brText;
                                    }
                                    if (count === 3 && previousSibP) {
                                        checkUnderstandingTime.value = brText;
                                    }
                                    if (nextSibP) {
                                        checkUnderstandingTime.value = nextSibP;
                                    }
                                }
                            }
                        })
                    })
                })
            })
        }
        else {
            return null; // will not throw error if there is any null value in the doc as per the conditions.
        }
    }

    // extracts the RHS, list of <p> text and <strong> text.
    if (element && element.parentElement.nextElementSibling) {
        const siblingTable = element.parentElement.nextElementSibling;
        if (siblingTable.nodeName === 'TABLE') {
            const theadElement = siblingTable.querySelector('thead');
            const trElement = theadElement.querySelectorAll('tr');
            trElement.forEach(tr => {
                let countTH = 0;
                tr.childNodes.forEach(th => {
                    countTH++;
                    th.childNodes.forEach(p => {        // checks all <p> text on the basis of ID
                        const pText = p.textContent.trim();
                        const parentTH = p ? (p.parentElement ? (p.parentElement.nodeName === 'TH' ? (p.parentElement.nextElementSibling ? (p.parentElement.nextElementSibling.nodeName === 'TH' ? p.parentElement.nextElementSibling.querySelectorAll('p') : null) : null) : null) : null) : null;
                        if (p.nodeName === 'TABLE') {
                            p.textContent.trim();
                        }
                        if (countTH === 1) {
                            parentTH.forEach(nextElementP => {
                                let strongContent = '';
                                const strongElements = nextElementP.querySelectorAll('strong');
                                strongElements.forEach(strong => {
                                    strongContent = strong.textContent.trim();
                                })
                                if (pText.toLowerCase().includes('provocation')) {       // checks all the <p> text on the basis of text
                                    introductoryParagraph.value.push(nextElementP.textContent.trim());
                                    if (strongContent !== "" ) {        // checks if the <strong> is empty
                                        introductoryHeading.value.push(strongContent);
                                    }
                                }
                                if ((pText.toLowerCase().includes('prior') || pText.toLowerCase().includes('pre') || pText.toLowerCase().includes('discussion'))) {       // checks all the <p> text on the basis of text
                                    priorKnowledgeParagraph.value.push(nextElementP.textContent.trim());
                                    if (strongContent !== "" ) {        // checks if the <strong> is empty
                                        priorKnowledgeHeading.value.push(strongContent);
                                    }
                                }
                                if (pText.toLowerCase().includes('activities')) {       // checks all the <p> text on the basis of text
                                    activitiesParagraph.value.push(nextElementP.textContent.trim());
                                    if (strongContent !== "" ) {        // checks if the <strong> is empty
                                        activitiesHeading.value.push(strongContent);
                                    }
                                }
                                if (pText.toLowerCase().includes('understanding')) {       // checks all the <p> text on the basis of text
                                    checkUnderstandingParagraph.value.push(nextElementP.textContent.trim());
                                    if (strongContent !== "" ) {        // checks if the <strong> is empty
                                        checkUnderstandingHeading.value.push(strongContent);
                                    }
                                }
                            })
                        }
                    })
                })
            })
        }
        else {
            return null; // will not throw error if there is any null value in the doc as per the conditions.
        }
    }

    // extracting text from the nested <table>
    if (element && element.parentElement.nextElementSibling) {
        const siblingTableThead = element.parentElement.nextElementSibling ? (element.parentElement.nextElementSibling.nodeName === 'TABLE' ? element.parentElement.nextElementSibling.querySelectorAll('thead') : null) : null;
        siblingTableThead.forEach(tr => {
            tr.childNodes.forEach(th => {
                th.childNodes.forEach(table => {
                    table.childNodes.forEach(tables => {
                        if (tables.nodeName === 'TABLE') {
                        }
                    })
                })
            })
        })
    }

    // extracting text from nested listing in Task Sequence
    if (element && element.parentNode.nextElementSibling) {
        const nextTable = element.parentNode.nextElementSibling;
        if (nextTable.nodeName === 'TABLE') {
            const tHeadElement = nextTable.querySelector('thead');
            const trElements = tHeadElement.querySelectorAll('tr');
            trElements.forEach(tr => {
                const thElements = tr.querySelectorAll('th');
                // Check if <th> elements exist before accessing them
                if (thElements.length >= 2) {
                    const mainHeading = thElements[0].querySelector('p') ? thElements[0].querySelector('p').textContent.trim() : null;
                    const subHeadings = [];

                    const firstUl = thElements[1] ? thElements[1].firstChild.nodeName === 'UL' ? thElements[1].querySelector('ul') : null : null; // Select the first ul element only
                    const firstOl = thElements[1] ? thElements[1].firstChild.nodeName === 'OL' ? thElements[1].querySelector('ol') : null : null; // Select the first ol element only
                    if (firstOl) {
                        const olItems = Array.from(firstOl.querySelectorAll('li')).map(li => li.textContent.trim());
                        olItems.forEach(item => {
                            subHeadings.push(item);
                        });
                    }
                    if (firstUl) {
                        const ulItems = Array.from(firstUl.querySelectorAll('li')).map(li => li.textContent.trim());
                        ulItems.forEach(item => {
                            subHeadings.push(item);
                        });
                    }
                    const imageLinks = [];
                    // Loop through all <p> elements under the second <th>
                    const pElements = thElements[1].querySelectorAll('p');
                    pElements.forEach((p, index) => {
                        const subHeading = p.textContent.trim();
                        const nextElement = p.nextElementSibling;
                        const imageElement = nextElement ? nextElement.querySelector('img') : null;
                        const imageElementSRC = imageElement ? imageElement.getAttribute('src') : null;
                        if (imageElementSRC !== null) {
                            imageLinks.push({ 'Text': subHeading, 'Image Link': imageElementSRC })
                        }
                        // Check if the next element is a list (<ul> or <ol>)
                        if (nextElement && (nextElement.tagName === 'UL' || nextElement.tagName === 'OL')) {
                            const subSubHeadings = [];
                            const listItems = Array.from(nextElement.querySelectorAll('li')).map(li => {
                                const subListItem = li.textContent.trim();
                                const subList = li.querySelector('ul');
                                if (subList) {
                                    const subSubListItems = Array.from(subList.querySelectorAll('li')).map(subLi => subLi.textContent.trim());
                                    subSubHeadings.push({ [subListItem]: subSubListItems });
                                } else {
                                    subSubHeadings.push(subListItem);
                                }
                            });
                            // Check if subSubHeadings are already included in parent subHeading
                            const isAlreadyIncluded = subHeadings.some(sh => {
                                if (typeof sh === 'object') {
                                    const existingSubSubHeadings = Object.values(sh)[0];
                                    const existingSubSubHeadingsTexts = existingSubSubHeadings.map(ssh => {
                                        if (typeof ssh === 'object') {
                                            return Object.keys(ssh)[0];
                                        } else {
                                            return ssh;
                                        }
                                    });
                                    return existingSubSubHeadingsTexts.includes(subHeading);
                                }
                                return false;
                            });
                            if (!isAlreadyIncluded) {
                                subHeadings.push({ [subHeading]: subSubHeadings });
                            }
                        } else {
                            subHeadings.push(subHeading);
                        }
                    });
                    if (mainHeading ? mainHeading.toLowerCase().includes('provocation') : null) {
                        introductoryListing.value.push( subHeadings );
                        introductoryImageLinks.value.push(imageLinks)
                    }
                    if (mainHeading ? (mainHeading.toLowerCase().includes('prior') || mainHeading.toLowerCase().includes('pre') || mainHeading.toLowerCase().includes('discussion')) : null) {
                        priorKnowledgeListing.value.push( subHeadings );
                        priorKnowledgeImageLinks.value.push(imageLinks)
                    }
                    if (mainHeading ? mainHeading.toLowerCase().includes('activities') : null) {
                        activitiesListing.value.push( subHeadings );
                        activitiesImageLinks.value.push(imageLinks)
                    }
                    if (mainHeading ? mainHeading.toLowerCase().includes('understanding') : null) {
                        checkUnderstandingListing.value.push( subHeadings );
                        checkUnderstandingImageLinks.value.push(imageLinks)
                    }
                }
            });
        }
    }

    // extracting the Curriculum Connections informations including the links, paragraphs, title, headings and lists.
    if (element && element.parentElement) {
        curriculumText.value = element.parentElement.textContent.trim()
        const nextTheadElement = element.parentElement ? element.parentElement.nodeName === 'H1' ? element.parentElement.nextElementSibling ? element.parentElement.nextElementSibling.nodeName === 'TABLE' ? element.parentElement.nextElementSibling.querySelector('thead') ? element.parentElement.nextElementSibling.querySelector('thead').querySelectorAll('tr') : null : null : null : null : null;
        nextTheadElement ? nextTheadElement.forEach(tr => {
            const eachTh = tr.querySelectorAll('th')
            const listText = []
            const pText = [];
            const strongText = [];
            const link = [];
            eachTh.forEach(th => {
                const h1Text = th.querySelector('h1') ? th.querySelector('h1').textContent.trim() : null;
                const pElement = th.querySelectorAll('p');
                pElement.forEach(p => {
                    const linkElement = p ? p.querySelectorAll('a') : null;
                    linkElement.forEach(a => {
                        const linkDetails = []
                        linkDetails.push({ "Text:": a.textContent.trim(), "Links": a.getAttribute('href') })
                        link.push(linkDetails);
                    })
                    // eliminating <strong> text from extracted <p> tag
                    const clonedP = p.cloneNode(true);
                    const strongTags = clonedP.querySelectorAll('strong')
                    strongTags.forEach(strong => {
                        strong.parentNode.removeChild(strong);
                    })
                    const textContent = clonedP.textContent.trim()
                    if (textContent !== "") {
                        pText.push(textContent)
                    }
                    // extracting on the <strong> tag text
                    const strongT = p.querySelector('strong') ? p.querySelector('strong').textContent.trim() : null;
                    if (strongT !== null) {
                        strongText.push(strongT);
                    }
                })
                const ulElement = th.querySelector('ul');
                const liElements = ulElement ? ulElement.querySelectorAll('li') : null;
                liElements ? liElements.forEach(li => {
                    listText.push(li.textContent.trim());
                }) : null;
                if (h1Text ? h1Text.toLowerCase().includes('version') : null) {
                    ausCurriculumTitle.value = h1Text
                    ausCurriculumListing.value.push(listText)
                    ausCurriculumParagraph.value.push(pText)
                    ausCurriculumHeadings.value.push(strongText)
                    ausCurriculumLinks.value.push(link)
                }
                if (h1Text ? h1Text.toLowerCase().includes('cross') : null) {
                    crossCurriculumTitle.value = h1Text
                    crossCurriculumListing.value.push(listText)
                    crossCurriculumParagraph.value.push(pText)
                    crossCurriculumHeadings.value.push(strongText)
                    crossCurriculumLinks.value.push(link)
                }
                if (h1Text ? (h1Text.toLowerCase().includes('general') || h1Text.toLowerCase().includes('capab')) : null) {
                    generalCapabilitiesTitle.value = h1Text
                    generalCapabilitiesListing.value.push(listText)
                    generalCapabilitiesParagraph.value.push(pText)
                    generalCapabilitiesHeadings.value.push(strongText)
                    generalCapabilitiesLinks.value.push(link)
                }
            })
        }) : null;
    }

    // extracting list within another listing - nested listing - "Curriculum Connections"
    if (element && element.parentElement) {
        const siblingss = element.parentElement ? element.parentElement.nodeName === 'H1' ? element.parentElement.nextElementSibling ? element.parentElement.nextElementSibling.nodeName === 'TABLE' ? element.parentElement.nextElementSibling.querySelector('thead') ? element.parentElement.nextElementSibling.querySelector('thead').querySelectorAll('tr') : null : null : null : null : null;
        if (siblingss !== null) {
            siblingss.forEach(tr => {
                const thEach = tr.querySelector('th')
                const sibling = thEach.nextElementSibling
                const firstTHText = tr.querySelector('th') ? tr.querySelector('th').textContent.trim() : null;
                if (sibling.nodeName === 'TH') {
                    const ulElements = sibling.querySelectorAll('ul');
                    ulElements.forEach(ulElement => {
                        const strong1Element = ulElement.previousElementSibling;
                        const strong2Element = ulElement.previousElementSibling ? ulElement.previousElementSibling.previousElementSibling : null;
                        const strong1 = strong1Element ? strong1Element.nodeName === 'ul' : null;
                        const strong2 = strong2Element ? strong2Element.querySelector('strong') : null;
                        let hierarchicalFormat;
                        // all paragraphs text (without <strong>) under the id element.
                        const paragraphTextOnly = [];
                        for (const child of sibling.children) {
                            if (child.nodeName === 'P' && !child.querySelector('strong')) {
                                paragraphTextOnly.push(child.textContent.trim());
                            }
                        }
                        // extracting <p><strong> that are 1 previous sibling to the each <ul>, but should not have two <p><strong> above the each <ul>
                        if (strong1Element && strong1Element.nodeName === 'P' && strong1Element.querySelector('strong') && (strong1 || !strong2)) {
                            const strongText = strong1Element.querySelector('strong').textContent.trim();
                            const pText = strong1Element ? strong1Element.textContent.trim() : null;
                            const nodeNameFound = strong1Element.previousElementSibling ? strong1Element.previousElementSibling.nodeName === 'P' : null;
                            const subListItems = Array.from(ulElement.children).map(listItem => {
                                let textContent = '';
                                // extract <li> text without nested <ul>
                                if (listItem.nodeName === 'LI' && listItem.querySelector('ul')) {
                                    listItem.childNodes.forEach(ulNode => {
                                        if (ulNode.nodeName !== 'UL') {
                                            textContent = ulNode.textContent.trim();
                                        }
                                    })
                                }
                                const subList = listItem.querySelector('ul');
                                if (subList) {
                                    const subListItems = Array.from(subList.querySelectorAll('li')).map(subListItem => {
                                        return {
                                            [subListItem.textContent.trim() ]: { "List Items:": [] }
                                        };
                                    });
                                    return {
                                        [ listItem.textContent.trim() ]: { "Layer 2 List Items:": subListItems }
                                    };
                                } else {
                                    return {
                                        [ listItem.textContent.trim() ]: { "List Items:": [] }
                                    };
                                }
                            });
                            planningPreparationParagraph.value = paragraphTextOnly;
                            hierarchicalFormat = {
                                [ strongText ]: { "List Items:": subListItems }
                            };
                            if (strongText === 'session 2') {
                                hierarchicalFormat[pText] = hierarchicalFormat[strongText];
                                delete hierarchicalFormat[strongText];
                            }
                            if(id === "_14z3n7fivubg" && firstTHText.toLowerCase().includes('version'))
                            {
                                ausCurriculumListingTree.value.push(hierarchicalFormat);
                            }
                            if(id === "_14z3n7fivubg" && firstTHText.toLowerCase().includes('cross'))
                            {
                                crossCurriculumListingTree.value.push(hierarchicalFormat);
                            }
                            if(id === "_14z3n7fivubg" && (firstTHText ? (firstTHText.toLowerCase().includes('general') || firstTHText.toLowerCase().includes('capab')) : null))
                            {
                                generalCapabilitiesListingTree.value.push(hierarchicalFormat);
                            }
                        }
                        // extracting <p><strong> that are 2 previous sibling to the each <ul>
                        if (strong2Element && strong2Element.nodeName === 'P' && strong2Element.querySelector('strong')) {
                            const strongText = strong2Element ? strong2Element.querySelector('strong').textContent.trim() : null;
                            const pText =  strong2Element ? strong2Element.nextElementSibling.textContent.trim() : null;
                            const subListItems = Array.from(ulElement.children).map(listItem => {
                                const subList = listItem.querySelector('ul');
                                if (subList) {
                                    const subListItems = Array.from(subList.querySelectorAll('li')).map(subListItem => {
                                        return {
                                            [subListItem.textContent.trim()]: {"List Items": []}
                                        };
                                    });
                                    return {
                                        [listItem.textContent.trim()]: {"List Items": subListItems}
                                    };
                                } else {
                                    return {
                                        [listItem.textContent.trim()]:{"List Items": []}
                                    };
                                }
                            });
                            const hierarchicalLayer2 = {
                                "List Items": subListItems
                            };
                            const hierarchicalLayer1 = {
                                [strongText]: { [pText]: hierarchicalLayer2}
                            };
                            if(id === "_14z3n7fivubg" && firstTHText.toLowerCase().includes('version'))
                            {
                                ausCurriculumListingTree.value.push(hierarchicalLayer1);
                            }
                            if(id === "_14z3n7fivubg" && firstTHText.toLowerCase().includes('cross'))
                            {
                                crossCurriculumListingTree.value.push(hierarchicalLayer1);
                            }
                            if(id === "_14z3n7fivubg" && (firstTHText ? (firstTHText.toLowerCase().includes('general') || firstTHText.toLowerCase().includes('capab')) : null))
                            {
                                generalCapabilitiesListingTree.value.push(hierarchicalLayer1);
                            }
                        }
                    });
                }
            })
        }
    }

}

//function to filter required content from JSON and display on the layout
const displayRequiredContent = () => {
    topicHeading_Display.value  = (data['Topic Heading']);
    topicCategory_Display.value = (data['Topic Category']);
    learningArea_Display.value = (data['Learning Area']);
    yearLevel_Display.value = (data['Year Level']);
    duration_Display.value = (data['Duration']);
    taskSummaryTitle_Display.value = (data['Task Summary']?.Title);
    taskSummaryParagraph_Display.value = (data['Task Summary']?.Paragraphs);
    sessionOverviewTitle_Display.value = (data['Component1']?.["Session Overview"]?.Title);
    digiTechTitle_Display.value = (data['Component1']?.["Digital Technologies"]?.Title);
    requiredResourcesTitle_Display.value = (data['Component1']?.["Required Resources"]?.Title);
    otherResourcesTitle_Display.value = (data['Component1']?.["Other Resources"]?.Title);
    planningPreparationTitle_Display.value = (data['Component1']?.["Planning And Preparation"]?.Title);
    taskSequenceText_Display.value = (data['Component2']?.Main_Heading);
    introductoryNumbering_Display.value = (data['Component2']?.["Introductory Activity"]?.Number)
    priorKnowledgeNumbering_Display.value = (data['Component2']?.["Prior Knowledge Check"]?.Number)
    activitiesNumbering_Display.value = (data['Component2']?.["Activities"]?.Number)
    checkUnderstandingNumbering_Display.value = (data['Component2']?.["Check Understanding"]?.Number)

    // Session Overview Paragraphs
    let paragraphsSO = "";
    if (data['Component1']?.["Session Overview"]?.Paragraphs) {
        paragraphsSO += "<ul>";
        data['Component1']?.["Session Overview"]?.Paragraphs.forEach((sentence, index) => {
            paragraphsSO += "<li>"+ sentence + "</li>";
            // Add <br> tags after each list item except for the last one
            if (index !== data['Component1']?.["Session Overview"]?.Paragraphs.length - 1) {
                paragraphsSO += "<br>";
            }
        });
        paragraphsSO += "</ul>";
        sessionOverviewParagraphs_Display.value = paragraphsSO.trim();
    } else {
        sessionOverviewParagraphs_Display.value = "Session Overview content not found.";
    }

    // Digital Technologies Paragraphs
    let listingDT = "";
    if (data['Component1']?.["Digital Technologies"]?.Listings) {
        listingDT += "<ul>";
        data['Component1']?.["Digital Technologies"]?.Listings.forEach((sentence, index) => {
            listingDT += "<li>"+ sentence + "</li>";
            // Add <br> tags after each list item except for the last one
            if (index !== data['Component1']?.["Digital Technologies"]?.Listings.length - 1) {
                listingDT += "<br>";
            }
        });
        listingDT += "</ul>";
        digiTechListings_Display.value = listingDT.trim();
    } else {
        digiTechListings_Display.value = "Digital Listing content not found.";
    }

    // Required Resources Paragraphs
    let paragraphsRR = "";
    if (data['Component1']?.["Required Resources"]?.Paragraphs) {
        paragraphsRR += "<ul>";
        data['Component1']?.["Required Resources"]?.Paragraphs.forEach((sentence, index) => {
            paragraphsRR += "<li>"+ sentence + "</li>";
            // Add <br> tags after each list item except for the last one
            if (index !== data['Component1']?.["Required Resources"]?.Paragraphs.length - 1) {
                paragraphsRR += "<br>";
            }
        });
        paragraphsRR += "</ul>";
        requiredResourcesParagraph_Display.value = paragraphsRR.trim();
    } else {
        requiredResourcesTitle_Display.value = "Required Resources content not found.";
    }

    // Other Resources Sublist Headings
    let sublistHeadingsOR = "";
    if (data['Component1']?.["Other Resources"]?.["Sub-List Headings"]) {
        sublistHeadingsOR += "<ul>";
        data['Component1']?.["Other Resources"]?.["Sub-List Headings"].forEach((sentence, index) => {
            sublistHeadingsOR += "<li>"+ sentence + "</li>";
            // Add <br> tags after each list item except for the last one
            if (index !== data['Component1']?.["Other Resources"]?.["Sub-List Headings"].length - 1) {
                sublistHeadingsOR += "<br>";
            }
        });
        sublistHeadingsOR += "</ul>";
        otherResourcesSubListHeadings_Display.value = sublistHeadingsOR.trim();
    } else {
        otherResourcesSubListHeadings_Display.value = "Other Resources content not found.";
    }

    // Other Resources nested listing - two level
    const listsWithSubLists = data['Component1']?.["Other Resources"]?.["Lists with Sub-Lists"];
    // Displaying each list with sub-lists
    if (listsWithSubLists && listsWithSubLists.length > 0) {
        for (let i = 0; i < listsWithSubLists.length; i++) {
            const list = listsWithSubLists[i];
            const listTitle = Object.keys(list)[0]; // Extracting the title of the list
            // Displaying the title of the list in bold
            otherResourcesListingSubListing_Display.value += `<strong>${listTitle}</strong><br>`;
            const subList = list[listTitle]; // Accessing the sub-lists
            // Displaying each item in the sub-list
            for (let j = 0; j < subList.length; j++) {
                const listItem = subList[j];
                otherResourcesListingSubListing_Display.value += `<div style="margin-left: ${20 * (1)}px;">`
                // Displaying the text of the item
                otherResourcesListingSubListing_Display.value += `- ${listItem.text}<br><br>`;
                otherResourcesListingSubListing_Display.value += `</div>`;
            }
        }
    }

    // Accessing the "Lists Tree" array - three level - *** Conditional ***
    const listsTree1 = data['Component1']?.["Planning And Preparation"]?.["Lists Tree"];
    // Define a recursive function to display each item
    const displayListTree = (listTree, indentLevel) => {
        for (const item of listTree) {
            const title = Object.keys(item)[0]; // Extracting the title of the item
            const content = item[title]; // Extracting the content of the item

            // Displaying the title of the item with appropriate indentation
            planningPreparationListingTree_Display.value += `<div style="margin-left: ${20 * indentLevel}px;"><strong>${title}</strong></div>`;

            // If the content has sub-items, recursively display them with increased indentation level
            if (content && Object.keys(content).length > 0) {
                if (content["List Items"] || content["List Items:"]) {
                    // Check if the content has "List Items" or "List Items:" keys
                    const listItems = content["List Items"] || content["List Items:"];
                    displayListTree(listItems, indentLevel + 1); // Recursively display the list items
                } else {
                    // If no "List Items" keys found, recursively display the content
                    displayListTree([content], indentLevel + 1);
                }
            }
        }
    };
    // Displaying each list with sub-lists
    if (listsTree1 && listsTree1.length > 0) {
        displayListTree(listsTree1, 0); // Starting the recursive display with initial indentation level 0
    }

    // Accessing the "Lists Tree" array - two level
    const listsTree2 = data['Component1']?.["Required Resources"]?.["Lists with Sub-Lists"];
    // Define a recursive function to display each item
    const displayListsSubLists1 = (listsSubLists, indentLevel) => {
        for (const list of listsSubLists) {
            const title = Object.keys(list)[0]; // Extracting the title of the list
            const items = list[title]; // Extracting the items of the list
            // Displaying the title of the list with appropriate indentation
            requiredResourcesListingSubListing_Display.value += `<br><div style="margin-left: ${20 * indentLevel}px;"><strong>${title}</strong></div>`;
            // If the items array is not empty, display each item
            if (items && items.length > 0) {
                for (const item of items) {
                    requiredResourcesListingSubListing_Display.value += `<div style="margin-left: ${20 * (indentLevel + 1)}px;">`;
                    requiredResourcesListingSubListing_Display.value += (!item.text.startsWith("-")) ? `- ${item.text} <br>` : `${item.text} <br>`;
                    if (item.link && Object.keys(item.link).length > 0) {
                        // requiredResourcesListingSubListing_Display.value += `&emsp;&emsp;Link: <a href="${item.link}">${item.link}</a><br>`;
                    }
                    requiredResourcesListingSubListing_Display.value += `</div>`;
                }
            }
        }
    };
    // Displaying each list with sub-lists
    if (listsTree2 && listsTree2.length > 0) {
        displayListsSubLists1(listsTree2, 0); // Starting the recursive display with initial indentation level 0
    }

    // Accessing the "Lists Tree" array - two level
    const listsTree3 = data['Component2']?.["Introductory Activity"]?.Listings;
    // console.log(listsTree3)
    const displayListsSubListsCombined = (listsSubLists, indentLevel) => {
        let hasTitle = false; // Flag to check if a title is found
        for (const list of listsSubLists) {
            if (typeof list[0] === 'object') {
                const title = Object.keys(list[0])[0]; // Extracting the title of the list
                introductoryListing_Display.value += `<br><div style="margin-left: ${20 * indentLevel}px;"><strong>${title}</strong></div>`;
                hasTitle = true;
            } else if (!hasTitle) {
                // If no title is found, display items in list form without a title
                introductoryListing_Display.value += `<br><div style="margin-left: ${20 * indentLevel}px;"><ul>`;
                hasTitle = true;
            }
            const items = list.slice(0); // Extracting all items of the list
            if (items && items.length > 0) {
                for (const item of items) {
                    if (typeof item === 'string') {
                        // If item is a string, display it normally
                        introductoryListing_Display.value += `<div style="margin-left: ${20 * (indentLevel + 1)}px;">`;
                        introductoryListing_Display.value += (!item.startsWith("-")) ? `- ${item} <br>` : `${item} <br>`;
                        // You can add link handling here if needed
                        introductoryListing_Display.value += `</div>`;
                    } else if (typeof item === 'object') {
                        // If item is an object, assume it's a question-answer pair and display accordingly
                        const question = Object.keys(item)[0]; // Extracting the question
                        const answers = item[question]; // Extracting the answers
                        introductoryListing_Display.value += `<div style="margin-left: ${20 * (indentLevel + 1)}px;">`;
                        introductoryListing_Display.value += `&nbsp;&nbsp;- <strong>${question}</strong> <br>`;
                        for (const answer of answers) {
                            introductoryListing_Display.value += `&nbsp;&nbsp;&nbsp;&nbsp; - ${answer} <br>`;
                        }
                        introductoryListing_Display.value += `</div>`;
                    }
                }
            }
            if (!hasTitle) {
                // Close the list tag if no title is found
                introductoryListing_Display.value += `</ul></div>`;
            }
        }
    };
    // Displaying each list with sub-lists
    if (listsTree3 && listsTree3.length > 0) {
        displayListsSubListsCombined(listsTree3, 0); // Starting the recursive display with initial indentation level 0
    }











}




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
            @click="displayRequiredContent"
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
                    v-if="topicHeading_Display"
                    v-html="topicHeading_Display"
                />
                <div v-else>
                    Topic Heading
                </div>
            </div>
            <div class="grid grid-cols-2 mt-2">
                <div class="mt-2 text-lg">
                    <div
                        v-if="topicCategory_Display"
                        v-html="topicCategory_Display"
                    />
                    <div v-else>
                        AR/VR Learning Tasks will come here.
                    </div>
                </div>
                <div class="flex flex-col gap-4 text-lg">
                    <div class="flex flex-row">
                        <div>Year Level: </div>
                        <div
                            v-if="yearLevel_Display"
                            v-html="yearLevel_Display"
                        />
                        <div v-else>
                            Year level will come here.
                        </div>
                    </div>
                    <div class="flex flex-row">
                        <div>Learning Area: </div><div
                            v-if="learningArea_Display"
                            v-html="learningArea_Display"
                        />
                        <div v-else>
                            Learning area will come here.
                        </div>
                    </div>
                    <div class="flex flex-row">
                        <div>Duration: </div><div
                            v-if="duration_Display"
                            v-html="duration_Display"
                        />
                        <div v-else>
                            Duration will come here.
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-10">
                <div
                    v-if="taskSummaryTitle_Display"
                    class="text-3xl"
                    v-html="taskSummaryTitle_Display"
                />
                <div v-else>
                    Task Summary Title will come here.
                </div>
                <div class="mt-4 text-xl">
                    <div
                        v-if="taskSummaryParagraph_Display"
                        v-html="taskSummaryParagraph_Display"
                    />
                    <div v-else>
                        Task Summary paragraph will come here.
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-2 mt-10">
                <div class="border-2 border-blue-600 p-4 rounded-2xl w-full">
                    <div class="text-3xl">
                        <div
                            v-if="sessionOverviewTitle_Display"
                            v-html="sessionOverviewTitle_Display"
                        />
                        <div v-else>
                            Session Overview title will come here.
                        </div>
                    </div>
                    <div class="mt-4 text-xl">
                        <div
                            v-if="sessionOverviewParagraphs_Display"
                            v-html="sessionOverviewParagraphs_Display"
                        />
                        <div v-else>
                            Session Overview paragraph will come here.
                        </div>
                    </div>
                </div>
                <div class="border-2 border-green-600 p-4 rounded-2xl w-full">
                    <div class="text-3xl">
                        <div
                            v-if="digiTechTitle_Display"
                            v-html="digiTechTitle_Display"
                        />
                        <div v-else>
                            Digital Technologies title will come here.
                        </div>
                    </div>
                    <div class="mt-4 text-xl">
                        <div
                            v-if="digiTechListings_Display"
                            v-html="digiTechListings_Display"
                        />
                        <div v-else>
                            Digital technologies paragraph will come here.
                        </div>
                    </div>
                </div>
                <div class="border-2 border-red-600 p-4 rounded-2xl w-full">
                    <div class="text-3xl">
                        <div
                            v-if="requiredResourcesTitle_Display"
                            v-html="requiredResourcesTitle_Display"
                        />
                        <div v-else>
                            Required resources Title will come here.
                        </div>
                    </div>
                    <div class="mt-4 text-xl">
                        <div
                            v-if="requiredResourcesParagraph_Display"
                            v-html="requiredResourcesParagraph_Display"
                        />
                        <div v-else>
                            Required resources paragraph will come here.
                        </div>
                        <div
                            v-if="requiredResourcesListingSubListing_Display"
                            v-html="requiredResourcesListingSubListing_Display"
                        />
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
                        <div
                            v-if="otherResourcesTitle_Display"
                            v-html="otherResourcesTitle_Display"
                        />
                        <div v-else>
                            Other resources title will come here.
                        </div>
                    </div>
                    <div class="mt-4 text-xl">
                        <div
                            v-if="otherResourcesListingSubListing_Display"
                            v-html="otherResourcesListingSubListing_Display"
                        />
                        <div v-else>
                            Other resources listing will come here.
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
                        <div
                            v-if="planningPreparationTitle_Display"
                            v-html="planningPreparationTitle_Display"
                        />
                        <div v-else>
                            Planning and Preparation title will come here.
                        </div>
                    </div>
                    <div class="mt-4 text-xl">
                        <div
                            v-if="planningPreparationListingTree_Display"
                            v-html="planningPreparationListingTree_Display"
                        />
                        <div v-else>
                            Planning and Preparation listing will come here.
                        </div>
                    </div>
                </div>
                <div class="mt-6 p-4">
                    <div class="text-3xl">
                        <div
                            v-if="taskSequenceText_Display"
                            v-html="taskSequenceText_Display"
                        />
                        <div v-else>
                            Task sequence title will come here.
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="border-2 border-gray-300 mt-4 p-4 rounded-2xl">
                            <div class="text-2xl">
                                <div
                                    v-if="introductoryNumbering_Display"
                                    v-html="introductoryNumbering_Display"
                                />
                                <div v-else>
                                    Task Sequence number will come here.
                                </div>
                            </div>
                            <div class="mt-2 text-xl">
                                <div
                                    v-if="introductoryListing_Display"
                                    v-html="introductoryListing_Display"
                                />
                                <div v-else>
                                    Task Sequence content will come here.
                                </div>
                            </div>
                        </div>
                        <div class="border-2 border-gray-300 p-4 rounded-2xl">
                            <div class="text-2xl">
                                <div
                                    v-if="priorKnowledgeNumbering_Display"
                                    v-html="priorKnowledgeNumbering_Display"
                                />
                                <div v-else>
                                    Task Sequence number will come here.
                                </div>
                            </div>
                            <div class="mt-2 text-xl">
                                Task No. 2 content will come here
                            </div>
                        </div>
                        <div class="border-2 border-gray-300 p-4 rounded-2xl">
                            <div class="text-2xl">
                                <div
                                    v-if="activitiesNumbering_Display"
                                    v-html="activitiesNumbering_Display"
                                />
                                <div v-else>
                                    Task Sequence number will come here.
                                </div>
                            </div>
                            <div class="mt-2 text-xl">
                                Task No. 3 content will come here
                            </div>
                        </div>
                        <div class="border-2 border-gray-300 p-4 rounded-2xl">
                            <div class="text-2xl">
                                <div
                                    v-if="checkUnderstandingNumbering_Display"
                                    v-html="checkUnderstandingNumbering_Display"
                                />
                                <div v-else>
                                    Task Sequence number will come here.
                                </div>
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
