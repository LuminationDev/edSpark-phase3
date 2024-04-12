<script setup>
import mammoth from 'mammoth';
import {ref} from 'vue';

import { data } from './dataJson'

//all initial variables are here
const htmlContent = ref('');
const error = ref('');
const jsonContent = ref({});

//other global variables
const hardwaresItemsList = ref('')
const appsItemsList = ref('')
const teachingResourcesItemsList = ref('')
const vrVideosItemsList = ref('')

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
const displayRRHardwareListS1 = ref('')
const displayRRHardwareListS2 = ref('')
const displayORHardwareListS1 = ref('')
const displayORHardwareListS2 = ref('')
const displayAppsListS1 = ref('')
const displayAppsListS2 = ref('')
const displayTeachingResourcesListS1 = ref('')
const displayTeachingResourcesListS2 = ref('')
const displayVRVideosListS1 = ref('')
const displayVRVideosListS1Link = ref('')
const displayVRVideosListText = ref('')
const displayRequiredResourcesHeadings = ref([]) //in arrays form


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
        // Call this function to extract hardware items from HTML content
        hardwaresItemsList.value = extractHardwareItems(htmlContent.value);
        // using this for debugging hardware listing
        // console.log('Hardware Required Resources with Session1', hardwaresItemsList.value.hardwareItemsForRR_1);
        // console.log('Hardware Required Resources with Session2', hardwaresItemsList.value.hardwareItemsForRR_2);
        // console.log('Hardware Other Resources with Session1', hardwaresItemsList.value.hardwareItemsForOR_1);
        // console.log('Hardware Other Resources with Session2', hardwaresItemsList.value.hardwareItemsForOR_2);
        // Call this function to extract hardware items from HTML content
        appsItemsList.value = extractAppsItems(htmlContent.value);
        // using this for debugging Apps listing
        // console.log('Apps lisitng', appsItemsList.value.appsItemsForRR_1);
        // console.log('Apps lisitng', appsItemsList.value.appsItemsForRR_2);
        // Call this function to extract hardware items from HTML content
        teachingResourcesItemsList.value = extractTeachingResourcesItems(htmlContent.value);
        // using this for debugging Apps listing
        // console.log('Teaching Resources lisitng', teachingResourcesItemsList.value.teachingResourcesItemsForRR_1);
        // console.log('Teaching Resources lisitng', teachingResourcesItemsList.value.teachingResourcesItemsForRR_2);
        vrVideosItemsList.value = extractVRvideosItems(htmlContent.value)
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
        "Required Resources": {
            "HardwareS1": hardwaresItemsList.value.hardwareItemsForRR_1,
            "HardwareS2": hardwaresItemsList.value.hardwareItemsForRR_2,
            "AppsS1": appsItemsList.value.appsItemsForRR_1,
            "AppsS2": appsItemsList.value.appsItemsForRR_2,
            "TeachingResourcesS1": teachingResourcesItemsList.value.teachingResourcesItemsForRR_1,
            "TeachingResourcesS2": teachingResourcesItemsList.value.teachingResourcesItemsForRR_2,
            "VR_Videos": vrVideosItemsList.value.vrVideosItemsForRR_1,
            // You can add other required resources here if needed
            "Required Resources": requiredResourcesSections.value
        },
        "Other resources to try (optional)": {
            "HardwareS1": hardwaresItemsList.value.hardwareItemsForOR_1,
            "HardwareS2": hardwaresItemsList.value.hardwareItemsForOR_2,
            // You can add other required resources here if needed
            "Other resources to try (optional)": otherResourcesSections.value
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
    if (data["Required Resources"]?.['Required Resources'][0]?.strong) {
        data["Required Resources"]?.['Required Resources'][0].strong.forEach((sentence) => {
            // Remove colons from the sentence
            sentence = sentence.replace(/:/g, '');
            if (!sentence.includes("Session 1") && !sentence.includes("Session 2")) {
                summaryRR_Headings += sentence;
            }
            // summaryRR_Headings += sentence;
            if (summaryRR_Headings.trim().length > 0) { // Check if there is any content to add

                displayRequiredResourcesHeadings.value.push({
                    title: "Title Text will be here",
                    content: summaryRR_Headings.trim()
                });
                summaryRR_Headings = ""; // Reset summaryRR_Headings for the next item
            }
        });
    } else {
        displayRequiredResourcesHeadings.value.push({ title: "Title Text will be here", content: "Digital Technologies content not found." });
    }
    //get the content from the object's array that has lists of contents in RR Session1
    let summaryRR_HardwareS1 = "";
    if (data["Required Resources"]?.HardwareS1) {
        summaryRR_HardwareS1 += "<ul>";
        data["Required Resources"].HardwareS1.forEach((sentence, index) => {
            summaryRR_HardwareS1 += "<li>"+ sentence + "</li>";
            // Add <br> tags after each list item except for the last one
            if (index !== data['Required Resources'].HardwareS1.length - 1) {
                summaryRR_HardwareS1 += "";
            }
        });
        summaryRR_HardwareS1 += "</ul>";
        displayRRHardwareListS1.value = summaryRR_HardwareS1.trim();
    } else {
        displayRRHardwareListS1.value = "RR Hardware List Session 1 content not found.";
    }
    //console.log(displayRRHardwareListS1.value)
    //get the content from the object's array that has lists of contents in RR Session2
    let summaryRR_HardwareS2 = "";
    if (data["Required Resources"]?.HardwareS2) {
        summaryRR_HardwareS2 += "<ul>";
        data["Required Resources"].HardwareS2.forEach((sentence, index) => {
            summaryRR_HardwareS2 += "<li>"+ sentence + "</li>";
            // Add <br> tags after each list item except for the last one
            if (index !== data['Required Resources'].HardwareS2.length - 1) {
                summaryRR_HardwareS2 += "";
            }
        });
        summaryRR_HardwareS2 += "</ul>";
        displayRRHardwareListS2.value = summaryRR_HardwareS2.trim();

    } else {
        displayRRHardwareListS2.value = "RR Hardware List Session 2 content not found.";
    }
    //console.log(displayRRHardwareListS2.value)
    //get the content from the object's array that has lists of contents in OR Session1
    let summaryOR_HardwareS1 = "";
    if (data["Other resources to try (optional)"]?.HardwareS1) {
        summaryOR_HardwareS1 += "<ul>";
        data["Other resources to try (optional)"].HardwareS1.forEach((sentence, index) => {
            summaryOR_HardwareS1 += "<li>"+ sentence + "</li>";
            // Add <br> tags after each list item except for the last one
            if (index !== data['Other resources to try (optional)'].HardwareS1.length - 1) {
                summaryOR_HardwareS1 += "";
            }
        });
        summaryOR_HardwareS1 += "</ul>";
        displayORHardwareListS1.value = summaryOR_HardwareS1.trim();
    } else {
        displayORHardwareListS1.value = "OR Hardware List Session content 1 not found.";
    }
    //get the content from the object's array that has lists of contents in Apps Session1
    let summaryAppsS1 = "";
    if (data["Required Resources"]?.AppsS1) {
        summaryAppsS1 += "<ul>";
        data["Required Resources"].AppsS1.forEach((sentence, index) => {
            summaryAppsS1 += "<li>"+ sentence + "</li>";
            // Add <br> tags after each list item except for the last one
            if (index !== data['Required Resources'].AppsS1.length - 1) {
                summaryAppsS1 += "";
            }
        });
        summaryAppsS1 += "</ul>";
        displayAppsListS1.value = summaryAppsS1.trim();
    } else {
        displayAppsListS1.value = "Apps Session 1 content not found.";
    }
    //console.log(displayAppsListS1.value)
    //get the content from the object's array that has lists of contents in Apps Session2
    let summaryAppsS2 = "";
    if (data["Required Resources"]?.AppsS2) {
        summaryAppsS2 += "<ul>";
        data["Required Resources"].AppsS2.forEach((sentence, index) => {
            summaryAppsS2 += "<li>"+ sentence + "</li>";
            // Add <br> tags after each list item except for the last one
            if (index !== data['Required Resources'].AppsS2.length - 1) {
                summaryAppsS2 += "";
            }
        });
        summaryAppsS2 += "</ul>";
        displayAppsListS2.value = summaryAppsS2.trim();
    } else {
        displayAppsListS2.value = "Apps Session 2 content not found.";
    }
    //console.log(displayAppsListS2.value)
    //get the content from the object's array that has lists of contents in Teaching Resources Session1
    let summaryTeachingResourcesS1 = "";
    if (data["Required Resources"]?.TeachingResourcesS1) {
        summaryTeachingResourcesS1 += "<ul>";
        data["Required Resources"].TeachingResourcesS1.forEach((sentence, index) => {
            summaryTeachingResourcesS1 += "<li>"+ sentence + "</li>";
            // Add <br> tags after each list item except for the last one
            if (index !== data['Required Resources'].TeachingResourcesS1.length - 1) {
                summaryTeachingResourcesS1 += "";
            }
        });
        summaryTeachingResourcesS1 += "</ul>";
        displayTeachingResourcesListS1.value = summaryTeachingResourcesS1.trim();
    } else {
        displayTeachingResourcesListS1.value = "Teaching Resources Session 1 content not found.";
    }
    //get the content from the object's array that has lists of contents in Teaching Resources Session2
    let summaryTeachingResourcesS2 = "";
    if (data["Required Resources"]?.TeachingResourcesS2) {
        summaryTeachingResourcesS2 += "<ul>";
        data["Required Resources"].TeachingResourcesS2.forEach((sentence, index) => {
            summaryTeachingResourcesS2 += "<li>"+ sentence + "</li>";
            // Add <br> tags after each list item except for the last one
            if (index !== data['Required Resources'].TeachingResourcesS2.length - 1) {
                summaryTeachingResourcesS2 += "";
            }
        });
        summaryTeachingResourcesS2 += "</ul>";
        displayTeachingResourcesListS2.value = summaryTeachingResourcesS2.trim();
    } else {
        displayTeachingResourcesListS2.value = "Teaching Resources Session 2 content not found.";
    }
    //console.log(displayAppsListS1.value)
    //get the content from the object's array that has lists of contents in Teaching Resources Session2
    let summaryVRVideosS1 = "";
    let summaryVRVideosS1Link = "";
    if (data["Required Resources"]?.VR_Videos) {
        summaryVRVideosS1Link += "<ul>"
        summaryVRVideosS1 += "<ul>";
        data["Required Resources"].VR_Videos.forEach((video, index) => {
            summaryVRVideosS1 += "<li>"+ video.text + "</li>";
            summaryVRVideosS1Link += "<li>"+ video.href + "</li>";
            // Add <br> tags after each list item except for the last one
            if (index !== data['Required Resources'].VR_Videos.length - 1) {
                summaryVRVideosS1 += "";
                summaryVRVideosS1Link += "";
            }
        });
        summaryVRVideosS1Link += "</ul>"
        summaryVRVideosS1 += "</ul>";
        displayVRVideosListS1.value = summaryVRVideosS1.trim();
    } else {
        displayVRVideosListS1.value = "VR Videos Session 1 content not found.";
    }
    //coverting the string into the array format
    const displayVRVideosLinks = data['Required Resources']?.["VR_Videos"];
    if (displayVRVideosLinks) {
        const videoArray = displayVRVideosLinks.map(video => video.href);
        const textArray = displayVRVideosLinks.map(text => text.text);
        displayVRVideosListS1Link.value = videoArray; // Storing array directly
        displayVRVideosListText.value = textArray;
    } else {
        displayVRVideosListS1Link.value = []; // Empty array if links not found
        displayVRVideosListText.value = [];   // Empty array if texts not found
    }
    console.log(displayVRVideosListText.value)   //to check the texts are stored in array format
    console.log(displayVRVideosListS1Link.value) //to check the links are stored in array format
    console.log(displayVRVideosListS1.value)
    //get the content from the object's array
    displayHeading.value = data['Topic Heading'] || "Heading not found"
    displayCategory.value = data['Topic Category'] || "Category not found"
    displayTaskSummary.value = data['Task Summary'][0]?.paragraph || "Task Summary not found"
    displayRequiredResourcesParagraph.value = data['Required Resources']?.["Required Resources"][0]?.paragraph[0]

    //get the content for href from the object's array on the basis of name
    const requiredResourceLink4 = data['Required Resources']?.["Required Resources"][0]?.["Required Resources_link"]?.find(link => link.name === 'Required Resources_link4');
    const linkHref = requiredResourceLink4 ? requiredResourceLink4.href : "Link not found.";

    //assign the extracted content to the variables
    displayHref.value = linkHref;
    //console
    //console.log(displayRequiredResourcesHeadings)
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

// Function to extract content on the basis of "Hardware" keyword iteration.
const extractHardwareItems = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const trTags = doc.querySelectorAll('tr');
    const hardwareItemsForRR_1 = [];
    const hardwareItemsForRR_2 = [];
    const hardwareItemsForOR_1 = [];
    const hardwareItemsForOR_2 = [];
    let inSession1 = false; // Flag to track if currently in Session 1
    let inSession2 = false; // Flag to track if currently in Session 2
    for (let i = 0; i < trTags.length; i++) {
        const trTag = trTags[i];
        const h1Tags = trTag.querySelectorAll('h1');
        const strongTags = trTag.querySelectorAll('p strong');
        // Check if both 'h1' and 'p strong' belong to the same <tr> tag
        if (h1Tags.length > 0 && strongTags.length > 0) {
            const sessionText = h1Tags[0].textContent.trim(); // Text content of the h1 tag
            if (sessionText.includes('Required resources')) {
                let sessionFound = false; // Flag to track if session keywords are found
                for (let j = 0; j < strongTags.length; j++) {
                    const strongTag = strongTags[j];
                    const sessionText = strongTag.parentNode.textContent.trim(); // Text content of the parent node
                    if (sessionText.includes('Session 1:')) {
                        inSession1 = true; // Set flag for Session 1
                        inSession2 = false; // Reset flag for Session 2
                        sessionFound = true;
                    } else if (sessionText.includes('Session 2 (optional):')) {
                        inSession1 = false; // Reset flag for Session 1
                        inSession2 = true; // Set flag for Session 2
                        sessionFound = true;
                    }
                    if (!sessionFound) {
                        // If neither session keyword found, consider it as a default session
                        inSession1 = true;
                        inSession2 = false;
                    }
                    if (strongTag.textContent.trim() === 'Hardware:' || strongTag.textContent.trim() === 'Hardware') {
                        const hardwarePrefix = inSession1 ? '1' : '2'; // Determine the session prefix
                        const pTag = strongTag.parentNode; // Get parent <p> tag
                        const ulTag = pTag.nextElementSibling; // Get next sibling <ul> tag

                        if (ulTag && ulTag.tagName.toLowerCase() === 'ul') {
                            const liTags = ulTag.querySelectorAll('li');
                            liTags.forEach((liTag, index) => {
                                if (inSession1) {
                                    hardwareItemsForRR_1.push(liTag.textContent.trim());
                                } else if (inSession2) {
                                    hardwareItemsForRR_2.push(liTag.textContent.trim());
                                }
                            });
                        }
                    }
                }
            } else if (sessionText.includes('Other resources to try (optional)')) {
                for (let j = 0; j < strongTags.length; j++) {
                    const strongTag = strongTags[j];
                    const sessionText = strongTag.parentNode.textContent.trim(); // Text content of the parent node
                    if (sessionText.includes('Session 1:')) {
                        inSession1 = true; // Set flag for Session 1
                        inSession2 = false; // Reset flag for Session 2
                    } else if (sessionText.includes('Session 2 (optional):')) {
                        inSession1 = false; // Reset flag for Session 1
                        inSession2 = true; // Set flag for Session 2
                    }
                    if (strongTag.textContent.trim() === 'Hardware:' || strongTag.textContent.trim() === 'Hardware') {
                        const hardwarePrefix = inSession1 ? '1' : '2'; // Determine the session prefix
                        const pTag = strongTag.parentNode; // Get parent <p> tag
                        const ulTag = pTag.nextElementSibling; // Get next sibling <ul> tag

                        if (ulTag && ulTag.tagName.toLowerCase() === 'ul') {
                            const liTags = ulTag.querySelectorAll('li');
                            liTags.forEach((liTag, index) => {
                                if (inSession1) {
                                    hardwareItemsForOR_1.push(liTag.textContent.trim());
                                } else if (inSession2) {
                                    hardwareItemsForOR_2.push(liTag.textContent.trim());
                                }
                            });
                        }
                    }
                }
            }
        }
    }
    return {
        hardwareItemsForRR_1: hardwareItemsForRR_1,
        hardwareItemsForRR_2: hardwareItemsForRR_2,
        hardwareItemsForOR_1: hardwareItemsForOR_1,
        hardwareItemsForOR_2: hardwareItemsForOR_2
    };
};


// Function to extract content on the basis of "Apps" keyword iteration.
const extractAppsItems = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const trTags = doc.querySelectorAll('tr');
    const appsItemsForRR_1 = [];
    const appsItemsForRR_2 = [];
    let inSession1 = false; // Flag to track if currently in Session 1
    let inSession2 = false; // Flag to track if currently in Session 2
    for (let i = 0; i < trTags.length; i++) {
        const trTag = trTags[i];
        const h1Tags = trTag.querySelectorAll('h1');
        const strongTags = trTag.querySelectorAll('p strong');
        // Check if both 'h1' and 'p strong' belong to the same <tr> tag
        if (h1Tags.length > 0 && strongTags.length > 0) {
            const sessionText = h1Tags[0].textContent.trim(); // Text content of the h1 tag
            if (sessionText.includes('Required resources')) {
                let sessionFound = false; // Flag to track if session keywords are found
                for (let j = 0; j < strongTags.length; j++) {
                    const strongTag = strongTags[j];
                    const sessionText = strongTag.parentNode.textContent.trim(); // Text content of the parent node
                    if (sessionText.includes('Session 1:')) {
                        inSession1 = true; // Set flag for Session 1
                        inSession2 = false; // Reset flag for Session 2
                        sessionFound = true;
                    } else if (sessionText.includes('Session 2 (optional):')) {
                        inSession1 = false; // Reset flag for Session 1
                        inSession2 = true; // Set flag for Session 2
                        sessionFound = true;
                    }
                    if (!sessionFound) {
                        // If neither session keyword found, consider it as a default session
                        inSession1 = true;
                        inSession2 = false;
                    }
                    if (strongTag.textContent.trim() === 'Apps:' || strongTag.textContent.trim() === 'App:') {
                        const appsPrefix = inSession1 ? '1' : '2'; // Determine the session prefix
                        const pTag = strongTag.parentNode; // Get parent <p> tag
                        const ulTag = pTag.nextElementSibling; // Get next sibling <ul> tag

                        if (ulTag && ulTag.tagName.toLowerCase() === 'ul') {
                            const liTags = ulTag.querySelectorAll('li');
                            liTags.forEach((liTag, index) => {
                                if (inSession1) {
                                    appsItemsForRR_1.push(' ● ' + " " + liTag.textContent.trim());
                                } else if (inSession2) {
                                    appsItemsForRR_2.push(' ● ' + " " + liTag.textContent.trim());
                                }
                            });
                        }
                    }
                }
            }
        }
    }
    return {
        appsItemsForRR_1: appsItemsForRR_1,
        appsItemsForRR_2: appsItemsForRR_2
    };
};

const extractTeachingResourcesItems = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const trTags = doc.querySelectorAll('tr');
    const teachingResourcesItemsForRR_1 = [];
    const teachingResourcesItemsForRR_2 = [];
    let inSession1 = false; // Flag to track if currently in Session 1
    let inSession2 = false; // Flag to track if currently in Session 2
    for (let i = 0; i < trTags.length; i++) {
        const trTag = trTags[i];
        const h1Tags = trTag.querySelectorAll('h1');
        const strongTags = trTag.querySelectorAll('p strong');
        // Check if both 'h1' and 'p strong' belong to the same <tr> tag
        if (h1Tags.length > 0 && strongTags.length > 0) {
            const sessionText = h1Tags[0].textContent.trim(); // Text content of the h1 tag
            if (sessionText.includes('Required resources')) {
                let sessionFound = false; // Flag to track if session keywords are found
                for (let j = 0; j < strongTags.length; j++) {
                    const strongTag = strongTags[j];
                    const sessionText = strongTag.parentNode.textContent.trim(); // Text content of the parent node
                    if (sessionText.includes('Session 1:')) {
                        inSession1 = true; // Set flag for Session 1
                        inSession2 = false; // Reset flag for Session 2
                        sessionFound = true;
                    } else if (sessionText.includes('Session 2 (optional):')) {
                        inSession1 = false; // Reset flag for Session 1
                        inSession2 = true; // Set flag for Session 2
                        sessionFound = true;
                    }
                    if (!sessionFound) {
                        // If neither session keyword found, consider it as a default session
                        inSession1 = true;
                        inSession2 = false;
                    }
                    if (strongTag.textContent.trim() === 'Teaching resources' || strongTag.textContent.trim() === 'Teaching resources') {
                        const appsPrefix = inSession1 ? '1' : '2'; // Determine the session prefix
                        const pTag = strongTag.parentNode; // Get parent <p> tag
                        const ulTag = pTag.nextElementSibling; // Get next sibling <ul> tag

                        if (ulTag && ulTag.tagName.toLowerCase() === 'ul') {
                            const liTags = ulTag.querySelectorAll('li');
                            liTags.forEach((liTag, index) => {
                                if (inSession1) {
                                    teachingResourcesItemsForRR_1.push(liTag.textContent.trim());
                                } else if (inSession2) {
                                    teachingResourcesItemsForRR_2.push(liTag.textContent.trim());
                                }
                            });
                        }
                    }
                }
            }
        }
    }
    return {
        teachingResourcesItemsForRR_1: teachingResourcesItemsForRR_1,
        teachingResourcesItemsForRR_2: teachingResourcesItemsForRR_2
    };
};

const extractVRvideosItems = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const trTags = doc.querySelectorAll('tr');
    const vrVideosItemsForRR_1 = [];
    let inSession1 = false; // Flag to track if currently in Session 1
    let inSession2 = false; // Flag to track if currently in Session 2
    for (let i = 0; i < trTags.length; i++) {
        const trTag = trTags[i];
        const h1Tags = trTag.querySelectorAll('h1');
        const strongTags = trTag.querySelectorAll('p strong');
        // Check if both 'h1' and 'p strong' belong to the same <tr> tag
        if (h1Tags.length > 0 && strongTags.length > 0) {
            const sessionText = h1Tags[0].textContent.trim(); // Text content of the h1 tag
            if (sessionText.includes('Required resources')) {
                let sessionFound = false; // Flag to track if session keywords are found
                for (let j = 0; j < strongTags.length; j++) {
                    const strongTag = strongTags[j];
                    const sessionText = strongTag.parentNode.textContent.trim(); // Text content of the parent node
                    if (sessionText.includes('Session 1:')) {
                        inSession1 = true; // Set flag for Session 1
                        inSession2 = false; // Reset flag for Session 2
                        sessionFound = true;
                    } else if (sessionText.includes('Session 2 (optional):')) {
                        inSession1 = false; // Reset flag for Session 1
                        inSession2 = true; // Set flag for Session 2
                        sessionFound = true;
                    }
                    if (!sessionFound) {
                        // If neither session keyword found, consider it as a default session
                        inSession1 = true;
                        inSession2 = false;
                    }
                    if (strongTag.textContent.trim() === 'VR videos:' || strongTag.textContent.trim() === 'Videos:') {
                        const appsPrefix = inSession1 ? '1' : '2'; // Determine the session prefix
                        const pTag = strongTag.parentNode; // Get parent <p> tag
                        const ulTag = pTag.nextElementSibling; // Get next sibling <ul> tag

                        if (ulTag && ulTag.tagName.toLowerCase() === 'ul') {
                            const liTags = ulTag.querySelectorAll('li');
                            liTags.forEach((liTag, index) => {
                                const aTag = liTag.querySelector('a'); // Get the <a> tag within <li>
                                if (aTag) {
                                    const href = aTag.getAttribute('href'); // Get the href attribute value
                                    const text = aTag.textContent.trim(); // Get the text content
                                    if (inSession1) {
                                        vrVideosItemsForRR_1.push({ text, href }); // Push an object with text and href
                                    } else if (inSession2) {
                                        vrVideosItemsForRR_1.push({ text, href }); // Push an object with text and href
                                    }
                                }
                            });
                        }
                    }

                }
            }
        }
    }
    return {
        vrVideosItemsForRR_1: vrVideosItemsForRR_1,
    };
};

//raw/test functions are here
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
            v-if="displayedJsonContent"
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
                        v-if="Object.keys(sessionOverviewSections).length > 0"
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
