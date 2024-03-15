<script setup>
import { ref, onMounted } from 'vue';
import jsPDF from 'jsPDF';
// import autoTable from 'jspdf-autotable'
import html2canvas from 'html2canvas';
// import html2pdf from 'html2pdf.js'
// import download from 'downloadjs'
import GenericButton from '../button/GenericButton.vue';
import { useUserStore } from "@/js/stores/useUserStore";
import moment from 'moment';
// import LatoFontReg from './Lato-Regular-normal.js';
import MuseoSans100 from './MuseoSans-100-normal.js';
import MuseoSans300 from './MuseoSans-300-normal.js';
import MuseoSans500 from './MuseoSans-500-normal.js';
import MuseoSans700 from './MuseoSans-700-normal.js';

import bgImagePages from './../../../../resources/assets/images/DMA-Report-bg-1.png';
import bgImageTitle from './../../../../resources/assets/images/DMA-Report-bg-title.png';
import dmaLegend from './../../../../resources/assets/images/DMA-Report-legend.png';

import { dmaService } from "@/js/service/dmaService";

import * as htmlToImage from 'html-to-image';

const userStore = useUserStore()

const doc = new jsPDF({
    orientation: 'portrait',
    unit: 'cm',
    format: 'a4',
    // html2canvas: { dpi: 300, letterRendering: true, width: 1080, height: 1920 },
});

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

const siteName = userStore.getUserSiteName;
const date = moment(new Date()).format('DD-MM-YYYY');

const actualWidth = 22.5;
const leftMargin = 1.5;
const rightMargin = 1.5;
const pageWidth = actualWidth - leftMargin - rightMargin;
const middle = 21 / 2;


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

var imagesLoaded = false;

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
    bg_p1.src = bgImagePages;
    legend.src = dmaLegend;
}

loadImages();

const newPage = () => {
    doc.addPage();

    doc.addImage(bg_p1, 'png', 15, 24, 6, 6);

    // page title
    doc.setTextColor('#339999');
    doc.setFont("MuseoSans-500");
    doc.setFontSize(24);
    doc.text("Your digital capability", leftMargin, 2, 'left');
    doc.setFontSize(60);
    doc.setFont("MuseoSans-700");
    doc.text("PROFILE", leftMargin, 4.1, 'left');
}

const capitalizeFirstLetter = (string) => {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

const generatePDF = () => {
    console.log("Generating! " + imagesLoaded);

    doc.addImage(bg_ptitle, 'png', 0, 0, 21, 29.7);
    doc.addImage(legend, 'png', 0.8, 26.1, 2.4, 3.1);

    const elementForImage = document.getElementsByClassName('radial-chart')[0];
    elementForImage.style = "stroke: #e5e5e5; stroke-width: 0.7px;";
    // console.log(elementForImage);

    htmlToImage.toPng(elementForImage, { skipFonts: true, quality: 1.0 }).then(function (dataUrl) {
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
            // doc.setLineWidth(0.05);
            // doc.line(leftMargin, 5, pageWidth, 5);

            doc.setFont("MuseoSans-300");
            doc.setFontSize(18);
            doc.text(siteName, leftMargin, 6, "left");
            doc.setFontSize(12);
            doc.text(date, leftMargin, 6.7, "left");


            //intro text
            doc.setFont("MuseoSans-100");
            doc.setFontSize(12);

            var introTextY = 8;
            doc.text(doc.splitTextToSize("Congratulations on completing the Digital Capability Leadership Reflection Tool.", 17), leftMargin, introTextY);
            doc.text(doc.splitTextToSize("Your school’s personalised Digital Capability Profile has been generated based on your reflections across the 13 elements within the 4 domains of the Digital Capability Framework.", 17), leftMargin, introTextY + 1);
            doc.text(doc.splitTextToSize("The school’s current digital capability in each element has been positioned on a continuum ranging from emerging to developing, achieving or excelling.", 17), leftMargin, introTextY + 3);
            doc.text(doc.splitTextToSize("To continue on your digital improvement journey, tailored recommendations have been compiled for your identified focus areas.", 17), leftMargin, introTextY + 4.5);


            // radial chart
            var imageOffset = 15; //top of image
            var leftOffset = 0.5;
            doc.addImage(img, "png", leftOffset + 3, imageOffset + 0.2, 12, 12);

            doc.setFont("MuseoSans-300");
            doc.setFontSize(10);

            //teaching domain labels
            doc.text("Technical skills", leftOffset + 12, imageOffset, 'center');
            doc.text("Pedagogy", leftOffset + 14.2, imageOffset + 1.35, 'center');
            doc.text("Assessment", leftOffset + 16.1, imageOffset + 4, 'center');
            doc.text("Curriculum", leftOffset + 16.2, imageOffset + 6.8, 'center');
            doc.text("Professional\nlearning", leftOffset + 15.3, imageOffset + 9.8, 'center');

            //learning domain labels
            doc.text("Digital\ntechnologies", leftOffset + 12.5, imageOffset + 12.2, 'center');
            doc.text("Digital\nliteracy", leftOffset + 9, imageOffset + 12.8, 'center');
            doc.text("Environment", leftOffset + 5.1, imageOffset + 12, 'center');

            //leading domain labels
            doc.text("Culture", leftOffset + 3, imageOffset + 9.8, 'center');
            doc.text("Connections", leftOffset + 1.6, imageOffset + 6.8, 'center');

            //managing domain labels
            doc.text("Practices", leftOffset + 2.45, imageOffset + 4, 'center');
            doc.text("Resources", leftOffset + 4, imageOffset + 1.35, 'center');
            doc.text("Administration", leftOffset + 6.5, imageOffset, 'center');



            //REPORT PAGES

            console.log("Element data", props.elementData);

            const domainColors = ['#223c91', '#5eadb2', '#d8747f', '#f4b07e'];

            for (var i = 0; i < props.elementData.length; i++) {
                console.log(i, props.elementData[i]);
                newPage();

                // domain title
                doc.setFillColor(domainColors[i]);
                doc.rect(0, 5.3, pageWidth + leftMargin, 1.5, 'F');
                doc.setTextColor('#FFFFFF');
                doc.setFont("MuseoSans-700");
                doc.setFontSize(24);

                var domainTitle = capitalizeFirstLetter(props.elementData[i].domain_label);
                doc.text(domainTitle, leftMargin, 6.3, 'left');
                doc.setFont("MuseoSans-300");

                var titleOffset = doc.getTextWidth(domainTitle);
                doc.text("domain", leftMargin + titleOffset + 0.4, 6.3, 'left');

                var sectionOffset = 8;
                var elementCount = props.elementData[i].elements.element.length;
                for (var j = 0; j < elementCount; j++) {
                    var elementTitle = capitalizeFirstLetter(props.elementData[i].elements.element[j].element_print);

                    //element line
                    doc.setDrawColor(domainColors[i]);
                    doc.setLineWidth(0.3);
                    doc.line(leftMargin, sectionOffset - 0.6, leftMargin, sectionOffset + 0.1);

                    //element title
                    doc.setTextColor(domainColors[i]);
                    doc.setFont("MuseoSans-700");
                    doc.setFontSize(20);
                    doc.text(elementTitle, leftMargin + 0.5, sectionOffset, 'left');


                    //body text
                    doc.setTextColor('#000000');
                    doc.setFont("MuseoSans-100");
                    doc.setFontSize(12);


                    ///SEE https://codepen.io/cat_developer/pen/mdxGYvM

                    var tmpHTML = document.createElement('div');
                    tmpHTML.innerHTML = props.elementData[i].elements.element[j].element_description;

                    var introP = tmpHTML.getElementsByTagName('p');
                    var textOffset = sectionOffset + 1;
                    for (const para of introP) {
                        doc.text(para.outerText, leftMargin, textOffset, 'left');
                        // printDemoText(para.outerText);
                        textOffset = textOffset + 0.8;
                    }

                    var introP = tmpHTML.getElementsByTagName('li');
                    for (const listItem of introP) {
                        doc.text('•  ', leftMargin + 1, textOffset, 'left');
                        var thisText = doc.splitTextToSize(listItem.outerText, 16);
                        doc.text(thisText, leftMargin + 1.4, textOffset, 'left');
                        textOffset = textOffset + (thisText.length * 0.4) + 0.4;
                    }

                    if (j < elementCount - 1) {
                        newPage();
                        sectionOffset = 6;
                    }

                }

            }

            doc.save("DMA-Report_" + siteName.replace(" ", "-") + "_" + date + ".pdf"); //download as PDF

            console.log("DONE!");
        };

    }
    )

};







</script>


<template>
    <div class="flex flex-row justify-end max-w-[800px] m-auto">
        <GenericButton :callback="generatePDF" button-id="pdfDownload" class="
                text-white
                hover:!brightness-[1.1]
                font-medium
                px-12
                py-2
                text-lg
                mb-10
                ">
            Download report
        </GenericButton>
    </div>
</template>