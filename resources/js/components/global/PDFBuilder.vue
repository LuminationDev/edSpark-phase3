<script setup>
import * as htmlToImage from 'html-to-image';
import {jsPDF} from 'jspdf';
import moment from 'moment';
import {ref} from 'vue';

import {useUserStore} from "@/js/stores/useUserStore";

import GenericButton from '../button/GenericButton.vue';
import bgImageTitle from './../../../../resources/assets/images/DMA-Report-bg-title.png';
import dfeLogo from './../../../../resources/assets/images/DMA-Report-dfe-logo.png';
import dmaLegend from './../../../../resources/assets/images/DMA-Report-legend.png';
import MuseoSans100 from './MuseoSans-100-normal.js';
import MuseoSans300 from './MuseoSans-300-normal.js';
import MuseoSans500 from './MuseoSans-500-normal.js';
import MuseoSans700 from './MuseoSans-700-normal.js';

const userStore = useUserStore()

let doc = null;

const initialiseDoc = () => {
    doc = new jsPDF({
        orientation: 'portrait',
        unit: 'cm',
        format: 'a4',
    });

    doc.page = 1; // counter


    //for html2pdf
    // doc.addFileToVFS('MuseoSans-100-normal.ttf', MuseoSans100);
    // doc.addFont('MuseoSans-100-normal.ttf', 'Museo Sans', 'normal');

    // doc.addFileToVFS('MuseoSans-700-normal.ttf', MuseoSans700);
    // doc.addFont('MuseoSans-700-normal.ttf', 'Museo Sans Bold', 'normal');

    // for everything else
    doc.addFileToVFS('MuseoSans-100-normal.ttf', MuseoSans100);
    doc.addFont('MuseoSans-100-normal.ttf', 'MuseoSans-100', 'normal');

    doc.addFileToVFS('MuseoSans-300-normal.ttf', MuseoSans300);
    doc.addFont('MuseoSans-300-normal.ttf', 'MuseoSans-300', 'normal');

    doc.addFileToVFS('MuseoSans-500-normal.ttf', MuseoSans500);
    doc.addFont('MuseoSans-500-normal.ttf', 'MuseoSans-500', 'normal');

    doc.addFileToVFS('MuseoSans-700-normal.ttf', MuseoSans700);
    doc.addFont('MuseoSans-700-normal.ttf', 'MuseoSans-700', 'normal');

}

const siteName = userStore.getUserSiteName;
const date = moment(new Date()).format('DD-MM-YYYY');

const actualWidth = 22.5;
const leftMargin = 1.5;
const rightMargin = 1.5;
const pageWidth = actualWidth - leftMargin - rightMargin;
const pageHeight = 29.7 - 1; //minus for margins


const props = defineProps({
    domains: {
        type: Array,
        required: true,
    },
    questionData: {
        type: Array,
        required: true,
    },
    reportData: {
        type: Array,
        required: true,
    },
    elementData: {
        type: Array,
        required: true,
    }
})


import {dmaService} from "@/js/service/dmaService";
// import { current } from 'tailwindcss/colors';
const actionData = ref(null);
const reflectionData = ref(null);

const domainColorsLabelBorders = {
    "teaching": '#223c91',
    "learning": '#339999',
    "leading": '#BC3242',
    "managing": '#FD9141'
};
const domainColorsLabelFills = {
    "teaching": '#6985B7',
    "learning": '#5eadb2',
    "leading": '#d8747f',
    "managing": '#f4b07e'
};

const domainColorsBorder = Object.values(domainColorsLabelBorders);//['#223c91', '#5eadb2', '#d8747f', '#f4b07e'];
const domainColorsFills = Object.values(domainColorsLabelFills);//['#223c91', '#5eadb2', '#d8747f', '#f4b07e'];

const domainColorsActionBg = ['#dce3f1', '#c7e1e3', '#f5e0e2', '#f6e6dd'];

let imagesLoaded = false;

const bg_ptitle = new Image();
const bg_p1 = new Image();
const legend = new Image();

const loadImages = () => {
    console.log("Loading images...");

    legend.onload = () => {
        bg_ptitle.onload = () => {
            bg_p1.onload = () => {
                imagesLoaded = true;
                console.log("Images ready!");
            }
        }
    }

    //order matters, set onload event first in case of caching
    bg_ptitle.src = bgImageTitle;
    bg_p1.src = dfeLogo; //bgImagePages;
    legend.src = dmaLegend;
}

loadImages();

const chunk = (arr, size) =>
    Array.from({length: Math.ceil(arr.length / size)}, (v, i) =>
        arr.slice(i * size, i * size + size)
    );


const footer = () => {
    doc.page++;
    doc.setTextColor('#339999');
    doc.setFontSize(12);
    doc.text('Page ' + doc.page, pageWidth, 28.5, 'right'); //print number bottom right
};

const newPage = () => {
    doc.addPage();
    footer();

    doc.addImage(bg_p1, 'png', 16.25, 0, 5, 5);

    doc.setFillColor('#339999');
    doc.rect(0, 0, pageWidth + leftMargin, 0.2, 'F');
    doc.rect(0, pageHeight + 0.5, pageWidth + leftMargin, 2, 'F');

    // page title
    doc.setTextColor('#339999');
    doc.setFont("MuseoSans-500");
    doc.setFontSize(24);
    doc.text("Your digital capability", leftMargin, 2, 'left');
    doc.setFontSize(60);
    doc.setFont("MuseoSans-700");
    doc.text("PROFILE", leftMargin, 4.1, 'left');


    setBodyText();
    pageUsage = 0;
    lineCount = 0;
    textOffset = 6; //reset to be under page header
    sectionOffset = textOffset;
}

const drawActionsBlock = (title, boxColor, bgColor, topY, thisText) => {
    const textHeight = (thisText.length * 0.48) + 1.2;

    doc.setFillColor(boxColor);
    doc.setLineWidth(0.1);
    doc.rect(leftMargin, topY, pageWidth - leftMargin, 1, 'FD'); //title box     

    doc.setFillColor(bgColor);
    doc.rect(leftMargin, topY + 0.8, pageWidth - leftMargin, textHeight, 'F'); //text box border 

    doc.setFillColor(boxColor);
    doc.rect(leftMargin, topY + 0.8, pageWidth - leftMargin, textHeight, 'S'); //text box border   


    doc.setTextColor('#FFFFFF');
    doc.setFont("MuseoSans-700");
    doc.setFontSize(16);
    doc.text(title, leftMargin + 0.5, topY + 0.6, 'left'); //title

    setBodyText();
    doc.text(thisText, leftMargin + 0.5, topY + 1.7, 'left'); //text

    return textHeight + 0.4;
}

const total_spacing = 0.9 + 1.2;
const drawSuggestionsBlock = (i, topY, introText, listItems, selected) => {

    let tmpOffset = 0;

    setBodyText();
    doc.setFont("MuseoSans-100");
    doc.text(introText, leftMargin + 0.1, topY + tmpOffset, 'left');


    tmpOffset = tmpOffset + (introText.length * 0.45); // allow for text block

    if (selected) {
        tmpOffset = tmpOffset + 0.9; //add paragraph space

        doc.setFont("MuseoSans-700");
        doc.text("Suggested strategies for further school development include:", leftMargin + 1, topY + tmpOffset, 'left');

        const topOffset = tmpOffset - 0.4;

        doc.setFont("MuseoSans-100");
        tmpOffset = tmpOffset + 1.1;


        //render the list
        for (const listItem of listItems) {
            doc.text('•  ', leftMargin + 1.2, topY + tmpOffset, 'left');
            doc.text(listItem, leftMargin + 1.6, topY + tmpOffset, 'left');
            tmpOffset = tmpOffset + (listItem.length * 0.45) + 0.4;
        }

        //element line
        doc.setDrawColor(domainColorsBorder[i]);
        doc.setLineWidth(0.15);
        doc.line(leftMargin + 0.5, topY + topOffset, leftMargin + 0.5, topY + tmpOffset - 0.5);
    }

    return tmpOffset;
}

const setBodyText = () => {
    doc.setTextColor('#000000');
    doc.setFont("MuseoSans-100");
    doc.setFontSize(12);
}


let selectedPlans = ref([]);
const isSelected = (string) => {
    if (selectedPlans.value.includes(string)) {
        return true;
    }
    return false;
}

const getDomainForElement = (thisElement) => {
    for (const subItem of props.elementData) {
        for (const subElement of subItem.elements.element) {
            if (subElement.element_print == thisElement) {
                return subItem.domain_label;
            }
        }
    }
    return "";
}

const capitalizeFirstLetter = (string) => {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

var pageUsage = 0;
var lineCount = 0;
var textOffset = 0;
var sectionOffset = 0;

const generatePDF = async () => {
    console.log("Generating! " + imagesLoaded);

    initialiseDoc();

    actionData.value = await dmaService.getActionPlans();
    selectedPlans = ref([]); //reset from previous selections

    for (const domain of props.domains) {
        const actionPlans = actionData.value.action_plan[domain.domain];
        //do action plans exist?
        if (actionPlans.length > 0) {
            for (const plan of actionPlans) {
                // which ones are selected?
                if (plan.selected) {
                    selectedPlans.value.push(plan.element);
                }
            }
        }
    }

    console.log("SELECTED", selectedPlans);

    reflectionData.value = await dmaService.getReflection();

    doc.addImage(bg_ptitle, 'png', 0, 0, 21, 29.7);
    doc.addImage(legend, 'png', 0.65, 26.1, 2.4, 3.1);

    const origElement = document.getElementsByClassName('radial-chart')[0];

    const bgElement = origElement.cloneNode(true);
    const elementForImage = origElement.cloneNode(true);

    // elementForImage.setAttribute("style", "position: absolute; width: 100%;"); //so it overlaps the other
    bgElement.setAttribute("style", "position: absolute; width: 100%;");

    // var thisDiv = document.getElementById("holderDiv");
    // if(thisDiv){
    //     thisDiv.remove();
    // }

    console.log(document.getElementById("holderDiv"));

    const holderDiv = document.createElement('div');
    document.getElementsByTagName("body")[0].appendChild(holderDiv);
    holderDiv.setAttribute("id", "holderDiv");
    holderDiv.setAttribute("style", "position: relative;");
    holderDiv.appendChild(bgElement);
    holderDiv.appendChild(elementForImage);

    elementForImage.getElementsByTagName('g')[0].setAttribute('mask', '');
    bgElement.getElementsByTagName('g')[0].setAttribute('mask', '');

    const highlights = elementForImage.getElementsByTagName('path');
    const bgPath = bgElement.getElementsByTagName('path');

    let emptyCount = 0;
    let elementCellCount = 0;

    //put basic borders in place
    for (const thisCellbg of bgPath) {
        thisCellbg.setAttribute("style", "fill: white !important; stroke: #E5E5E5 !important;  stroke-width: 0.5px");
    }


    //TODO when selected is available, loop around and apply domain colours 
    for (const thisCell of highlights) {

        elementCellCount++;
        const elementLabel = thisCell.getAttribute("data-label");
        const domainLabel = getDomainForElement(elementLabel);

        if (isSelected(elementLabel)) {
            thisCell.setAttribute("style", "");
            if (thisCell.classList.contains('empty') && emptyCount == 0) {
                emptyCount++;
                thisCell.setAttribute("style", "fill: white !important; stroke: " + domainColorsLabelBorders[domainLabel] + " !important; stroke-width: 0.5px;");

            } else if (!thisCell.classList.contains('empty')) {
                thisCell.setAttribute("style", "fill: " + domainColorsLabelBorders[domainLabel] + " !important; stroke: " + domainColorsLabelBorders[domainLabel] + " !important; stroke-width: 0.5px;"); //fill with 10% of domain colour 
            } else {
                thisCell.setAttribute("style", "fill: transparent !important;");
            }
        } else {
            if (!thisCell.classList.contains('empty')) {
                thisCell.setAttribute("style", "fill: " + domainColorsLabelFills[domainLabel] + "; stroke: " + domainColorsLabelBorders[domainLabel] + " !important;  stroke-width: 0.5px;");
            } else {
                thisCell.setAttribute("style", "fill: transparent !important;");
            }
        }
        if (elementCellCount == 4) {
            elementCellCount = 0; //reset
            emptyCount = 0;
        }
    }

    console.log("PLANS", selectedPlans);


    htmlToImage.toPng(holderDiv, {skipFonts: true, quality: 1.0}).then(function (dataUrl) {
        const img = new Image();
        img.src = dataUrl;

        img.onload = () => {
            // wait for all images to be fully loaded

            //TITLE PAGE
            doc.setTextColor('#339999');
            doc.setFont("MuseoSans-500");
            doc.setFontSize(24);
            doc.text("Your digital capability", leftMargin, 2, 'left');
            doc.setFontSize(60);
            doc.setFont("MuseoSans-700");
            doc.text("PROFILE", leftMargin, 4.1, 'left');

            doc.setTextColor('#000000');

            doc.setFont("MuseoSans-300");
            doc.setFontSize(18);
            doc.text(siteName, leftMargin, 6, "left");
            doc.setFontSize(12);
            doc.text(date, leftMargin, 6.7, "left");


            //intro text
            setBodyText();

            const introTextY = 8;
            doc.text(doc.splitTextToSize("Congratulations on completing the Digital Capability Leadership Reflection Tool.", 17), leftMargin, introTextY);
            doc.text(doc.splitTextToSize("Your school’s personalised Digital Capability Profile has been generated based on your reflections across the 13 elements within the 4 domains of the Digital Capability Framework.", 17), leftMargin, introTextY + 0.8);
            doc.text(doc.splitTextToSize("The school’s current digital capability in each element has been positioned on a continuum ranging from emerging to developing, achieving or excelling.", 17), leftMargin, introTextY + 2.6);
            doc.text(doc.splitTextToSize("To continue on your digital improvement journey, tailored recommendations have been compiled for your identified focus areas.", 17), leftMargin, introTextY + 3.9);


            // radial chart
            const imageOffset = 14.5; //top of image
            const leftOffset = 0.5;
            doc.addImage(img, "png", leftOffset + 3, imageOffset + 0.2, 12, 12);

            doc.setFont("MuseoSans-700");
            doc.setFontSize(8.5);

            // Save the current canvas state
            // doc.saveGraphicsState();

            doc.setTextColor(domainColorsBorder[0]);
            doc.text("Technical skills", leftOffset + 10.52, imageOffset - 0.4, {align: 'center', angle: -13});
            doc.text("Pedagogy", leftOffset + 13.6, imageOffset + 0.9, {align: 'center', angle: -42});
            doc.text("Assessment", leftOffset + 15.6, imageOffset + 3.12, {align: 'center', angle: -72});
            doc.text("Curriculum", leftOffset + 16.5, imageOffset + 5.95, {align: 'center', angle: -96});
            doc.text("Professional learning", leftOffset + 15.34, imageOffset + 11.2, {align: 'center', angle: 58});
            // doc.text("Professional learning", leftOffset + 13.7, imageOffset + 11, {align: 'left', angle: 58});


            //learning domain labels
            doc.setTextColor(domainColorsBorder[1]);
            doc.text("Digital technologies", leftOffset + 12.4, imageOffset + 12.9, {align: 'center', angle: 27});
            doc.text("Digital literacy", leftOffset + 9.11, imageOffset + 13.1, {align: 'center', angle: 0});
            doc.text("Environment", leftOffset + 5.8, imageOffset + 11.8, {align: 'center', angle: -27.5});


            //leading domain labels
            doc.setTextColor(domainColorsBorder[2]);
            doc.text("Culture", leftOffset + 3.55, imageOffset + 9.65, {align: 'center', angle: -58});
            doc.text("Connections", leftOffset + 3.5, imageOffset + 7.8, {align: 'center', angle: 96});

            //managing domain labels
            doc.setTextColor(domainColorsBorder[3]);
            doc.text("Practices", leftOffset + 3.3, imageOffset + 4.5, {align: 'center', angle: 72});
            doc.text("Resources", leftOffset + 4.8, imageOffset + 1.7, {align: 'center', angle: 40});
            doc.text("Administration", leftOffset + 7.5, imageOffset + 0.1, {align: 'center', angle: 13});

            //teaching domain labels
            // doc.setTextColor(domainColorsBorder[0]);
            // doc.text("Technical skills", leftOffset + 12, imageOffset, 'center');
            // doc.text("Pedagogy", leftOffset + 14.2, imageOffset + 1.35, 'center');
            // doc.text("Assessment", leftOffset + 16.1, imageOffset + 4, 'center');
            // doc.text("Curriculum", leftOffset + 16.2, imageOffset + 6.8, 'center');
            // doc.text("Professional\nlearning", leftOffset + 15.3, imageOffset + 9.8, 'center');

            // //learning domain labels
            // doc.setTextColor(domainColorsBorder[1]);
            // doc.text("Digital\ntechnologies", leftOffset + 12.5, imageOffset + 12.2, 'center');
            // doc.text("Digital\nliteracy", leftOffset + 9, imageOffset + 12.8, 'center');
            // doc.text("Environment", leftOffset + 5.1, imageOffset + 12, 'center');

            // //leading domain labels
            // doc.setTextColor(domainColorsBorder[2]);
            // doc.text("Culture", leftOffset + 3, imageOffset + 9.8, 'center');
            // doc.text("Connections", leftOffset + 1.6, imageOffset + 6.8, 'center');

            // //managing domain labels
            // doc.setTextColor(domainColorsBorder[3]);
            // doc.text("Practices", leftOffset + 2.45, imageOffset + 4, 'center');
            // doc.text("Resources", leftOffset + 4, imageOffset + 1.35, 'center');
            // doc.text("Administration", leftOffset + 6.5, imageOffset, 'center');

            // Restore the canvas state
            // doc.restoreGraphicsState();


            //REPORT PAGES
            for (let i = 0; i < props.elementData.length; i++) {
                newPage();

                // domain title
                doc.setFillColor(domainColorsBorder[i]);
                doc.rect(0, 5.3, pageWidth + leftMargin, 1.5, 'F');
                doc.setTextColor('#FFFFFF');
                doc.setFont("MuseoSans-700");
                doc.setFontSize(24);

                const domainTitle = capitalizeFirstLetter(props.elementData[i].domain_label);
                doc.text(domainTitle, leftMargin, 6.3, 'left');
                doc.setFont("MuseoSans-300");

                const titleOffset = doc.getTextWidth(domainTitle);
                doc.text("domain", leftMargin + titleOffset + 0.4, 6.3, 'left');

                // loop through elements
                sectionOffset = 8;
                const elementCount = props.elementData[i].elements.element.length;
                for (let j = 0; j < elementCount; j++) {
                    const elementTitle = capitalizeFirstLetter(props.elementData[i].elements.element[j].element_print);
                    const elementLabel = props.reportData[i]?.elements[j]?.label ? props.reportData[i]?.elements[j]?.label : '';

                    //element line
                    doc.setDrawColor(domainColorsBorder[i]);
                    doc.setLineWidth(0.3);
                    doc.line(leftMargin, sectionOffset - 0.6, leftMargin, sectionOffset + 0.1);

                    //element title
                    doc.setTextColor(domainColorsBorder[i]);
                    doc.setFont("MuseoSans-700");
                    doc.setFontSize(20);
                    doc.text(elementTitle, leftMargin + 0.5, sectionOffset, 'left');


                    //body text
                    setBodyText();


                    ///SEE https://codepen.io/cat_developer/pen/mdxGYvM

                    const tmpHTML = document.createElement('div');
                    tmpHTML.innerHTML = props.elementData[i].elements.element[j].element_description;

                    var introP = tmpHTML.getElementsByTagName('p');
                    textOffset = sectionOffset + 1;
                    for (const para of introP) {
                        var thisText = doc.splitTextToSize(para.outerText, 16);
                        doc.text(thisText, leftMargin, textOffset, 'left');
                        textOffset = textOffset + (thisText.length * 0.4) + 0.4;

                    }

                    var introP = tmpHTML.getElementsByTagName('li');
                    for (const listItem of introP) {
                        doc.text('•  ', leftMargin + 1, textOffset, 'left');
                        var thisText = doc.splitTextToSize(listItem.outerText, 16);
                        doc.text(thisText, leftMargin + 1.4, textOffset, 'left');
                        textOffset = textOffset + (thisText.length * 0.4) + 0.4;
                    }

                    // element achievement level
                    textOffset = textOffset + 1;
                    doc.setTextColor(domainColorsBorder[i]);
                    doc.setFont("MuseoSans-300");
                    doc.setFontSize(20);
                    doc.text("Phase of capability: ", leftMargin, textOffset, 'left');
                    doc.setFont("MuseoSans-700");
                    doc.text(elementLabel, leftMargin + 6.4, textOffset, 'left');

                    // textOffset = textOffset + 0.4; // space after heading

                    //get element
                    var element = props.reportData[i].elements[j];

                    //get action plan data
                    const actionPlan = actionData.value.action_plan[props.elementData[i].domain_label];
                    const currentPlan = actionPlan?.find(e => e.element === element.element);
                    // console.log("PLAN", currentPlan);

                    //get indicator data
                    const indicatorsForSection = element.indicators.length;
                    let indicatorCount = 0;
                    const inset = 0;

                    // loop through indicators

                    for (const indicator of element.indicators) {
                        pageUsage = textOffset; //account for element descriptors before this
                        lineCount = 0;

                        indicatorCount++;
                        setBodyText();
                        //description of indicator
                        tmpHTML.innerHTML = indicator.description;
                        const introText = doc.splitTextToSize(tmpHTML.outerText, 16);


                        tmpHTML.innerHTML = indicator.advice;
                        var introP = tmpHTML.getElementsByTagName('li');
                        const listItems = [];
                        for (const listItem of introP) {
                            var thisText = doc.splitTextToSize(listItem.outerText, 16);
                            listItems.push(thisText);
                            lineCount = lineCount + thisText.length + 1 + 1; //1 for heading, 1 for space
                        }


                        //check if we need a new page to fit this
                        var predictedHeight = pageUsage + (lineCount * 0.45) + total_spacing + 1; //35

                        // console.log(predictedHeight+" vs "+pageHeight, indicator);

                        // doc.setLineWidth(0.1);
                        // doc.setDrawColor('#FF00FF');
                        // doc.line(leftMargin, 0.1, leftMargin, pageHeight);

                        // doc.setDrawColor('#00FFFF');
                        // doc.line(leftMargin + inset, 0.1, leftMargin + inset, pageUsage);

                        // doc.setDrawColor('#FF0000'); //red
                        // doc.line(leftMargin + 0.5 + inset, 0.1, leftMargin + 0.5 + inset, predictedHeight);
                        // inset = inset + 0.3;

                        textOffset = textOffset + 1; //space between last item and this one

                        //new page needed - run out of space
                        if (isSelected && predictedHeight > pageHeight) {
                            newPage();
                        }

                        //draw the suggestions block
                        var isSelected = currentPlan && currentPlan.selected; //currentPlan.selected;
                        textOffset = textOffset + drawSuggestionsBlock(i, textOffset, introText, listItems, isSelected);

                    }


                    // var isSelected = true; //currentPlan.selected;
                    if (currentPlan && currentPlan.selected) {
                        if (!currentPlan.action || currentPlan.action.trim().length == 0) {
                            currentPlan.action = "No action plan entered yet"
                        }
                        var thisText = doc.splitTextToSize(currentPlan.action, 17);

                        //do we need to start a new page?
                        var box_spacing = 1.5 + 0.7 + 0.4;
                        predictedHeight = textOffset + (thisText.length * 0.48) + 1 + box_spacing;

                        textOffset = textOffset + 1; //gap from element previous

                        if (predictedHeight > pageHeight) {
                            newPage();
                        }


                        if (thisText.length > 39) {
                            var counter = 0;
                            var chunks = chunk(thisText, 39);
                            for (const thisChunk of chunks) {
                                counter++;
                                //if it starts with an empty cell, remove that cell
                                if (thisChunk[0] == '') {
                                    thisChunk.splice(0, 1);
                                }
                                textOffset = textOffset + drawActionsBlock("Action plan for " + elementTitle.toLowerCase(), domainColorsBorder[i], '#FFFFFF', textOffset, thisChunk);

                                if (counter < chunks.length) {
                                    newPage();
                                }
                            }

                        } else {
                            textOffset = textOffset + drawActionsBlock("Action plan for " + elementTitle.toLowerCase(), domainColorsBorder[i], '#FFFFFF', textOffset, thisText);
                        }

                    }

                    // new section
                    if (j < elementCount - 1) {
                        newPage();
                    }

                }

                //draw the reflection block
                const reflection = reflectionData.value.reflection[props.elementData[i].domain_label];
                // console.log("REFLECTION", reflection);
                const currentReflection = reflection[0];

                if (currentReflection && currentReflection.reflection && currentReflection.reflection.trim().length > 0) {
                    var thisText = doc.splitTextToSize(currentReflection.reflection, 17);

                    //do we need to start a new page?
                    var box_spacing = 1.5 + 0.7 + 0.4;
                    predictedHeight = textOffset + (thisText.length * 0.48) + 1 + box_spacing;

                    textOffset = textOffset + 1; //gap from element previous

                    if (predictedHeight > pageHeight) {
                        newPage();
                    }


                    if (thisText.length > 39) {
                        var counter = 0;
                        var chunks = chunk(thisText, 39);
                        for (const thisChunk of chunks) {
                            counter++;
                            //if it starts with an empty cell, remove that cell
                            if (thisChunk[0] == '') {
                                thisChunk.splice(0, 1);
                            }
                            textOffset = textOffset + drawActionsBlock("Reflection for " + domainTitle.toLowerCase() + " domain", domainColorsBorder[i], domainColorsActionBg[i], textOffset, thisChunk);

                            if (counter < chunks.length) {
                                newPage();
                            }
                        }

                    } else {
                        textOffset = textOffset + drawActionsBlock("Reflection for " + domainTitle.toLowerCase() + " domain", domainColorsBorder[i], domainColorsActionBg[i], textOffset, thisText);
                    }

                }
            }

            doc.save("DMA-Report_" + siteName.replace(" ", "-") + "_" + date + ".pdf"); //download as PDF


            const holder = document.getElementById("holderDiv");
            console.log(holder);
            if (holder) {
                holder.remove();
            }

            console.log("DONE!");
        };

    }
    )

};


</script>


<template>
    <div class="flex justify-end flex-row m-auto max-w-[800px]">
        <GenericButton
            :callback="generatePDF"
            button-id="pdfDownload"
            class="font-medium mb-10 px-12 py-2 text-lg text-white hover:!brightness-[1.1]"
        >
            Download report
        </GenericButton>
    </div>
</template>