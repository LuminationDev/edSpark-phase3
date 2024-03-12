<script setup>
    import { ref, onMounted } from 'vue';
    import jsPDF from 'jsPDF';
    // import autoTable from 'jspdf-autotable'
    // import html2canvas from 'html2canvas';
    // import html2pdf from 'html2pdf.js'
    // import download from 'downloadjs'
    import GenericButton from '../button/GenericButton.vue';
    import {useUserStore} from "@/js/stores/useUserStore";
    import moment from 'moment';
    // import LatoFontReg from './Lato-Regular-normal.js';
    import MuseoSans100 from './MuseoSans-100-normal.js';
    import MuseoSans300 from './MuseoSans-300-normal.js';
    import MuseoSans500 from './MuseoSans-500-normal.js';
    import MuseoSans700 from './MuseoSans-700-normal.js';
    
    import * as htmlToImage from 'html-to-image';

    const userStore = useUserStore()

    const doc = new jsPDF({
        orientation: 'portrait',
        unit: 'cm', 
        format: 'a4'
    });
      
        
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
    const middle = 21/2;

  

    const generatePDF = () => {
        console.log("Generating!");

        var elementForImage = document.getElementsByClassName('radial-chart')[0];
        elementForImage.style = "stroke: #e5e5e5; stroke-width: 0.7px;";
        console.log(elementForImage);
    
        htmlToImage.toPng(elementForImage, {skipFonts: true, quality: 1.0 })
        

        .then(function (dataUrl) {
            const img = new Image();
            img.src = dataUrl;

        img.onload = () => {
        // await for the image to be fully loaded

        
        doc.setFont("MuseoSans-500");
        doc.setFontSize(24);
        doc.text("YOUR DIGITAL CAPABILITY", middle, 2, 'center');
        doc.setFontSize(60);
        doc.setFont("MuseoSans-700");
        doc.text("PROFILE", middle, 4.1, 'center');
        
        doc.setLineWidth(0.05);
        doc.line(leftMargin, 5, pageWidth, 5);

        doc.setFont("MuseoSans-100");
        doc.setFontSize(18);

        doc.text(siteName, leftMargin, 6, "left");
        doc.text(date, pageWidth, 6, "right");

        
        doc.setFontSize(12);
        
        doc.text(doc.splitTextToSize("Congratulations on completing the Digital Capability Leadership Reflection Tool.", 18), leftMargin, 7.4);
        doc.text(doc.splitTextToSize("Your school’s personalised Digital Capability Profile has been generated based on your reflections across the 13 elements within the 4 domains of the Digital Capability Framework.", 18), leftMargin, 8.2);
        doc.text(doc.splitTextToSize("The school’s current digital capability in each element has been positioned on a continuum ranging from emerging to developing, achieving or excelling.", 18), leftMargin, 9.5);
        doc.text(doc.splitTextToSize("To continue on your digital improvement journey, tailored recommendations have been compiled for your identified focus areas.", 18), leftMargin, 10.9);
        
        var imageOffset = 14; //top of image
        doc.addImage(img, "png", 4.5, imageOffset+0.2, 12, 12);

        doc.setFont("MuseoSans-300");
        doc.setFontSize(10);
        
        //teaching domain labels
        doc.text("Technical skills", 13.5, imageOffset, 'center');
        doc.text("Pedagogy", 15.7, imageOffset + 1.35, 'center');
        doc.text("Assessment", 17.6, imageOffset + 4, 'center');
        doc.text("Curriculum", 17.8, imageOffset + 6.8, 'center');
        doc.text("Professional\nlearning", 16.8, imageOffset + 9.8, 'center');

        //learning domain labels
        doc.text("Digital\ntechnologies", 14, imageOffset + 12.2, 'center');
        doc.text("Digital\nliteracy", 10.5, imageOffset + 12.8, 'center');
        doc.text("Environment", 6.6, imageOffset + 12, 'center');

        //leading domain labels
        doc.text("Culture", 4.5, imageOffset + 9.8, 'center');
        doc.text("Connections", 3.1, imageOffset + 6.8, 'center');

        //managing domain labels
        doc.text("Practices", 3.95, imageOffset + 4, 'center');
        doc.text("Resources", 5.5, imageOffset + 1.35, 'center');
        doc.text("Administration", 8, imageOffset, 'center');


        //   const columns = ["Data"];
        //   const rows = [];
        //   data.map((item) =>
        //     rows.push([
        //       item.cartItems.map(
        //         (item) => `${item.name}: ${item.color} = ${item.quantity}`
        //       )
        //     ])
        //   );

        //   doc.autoTable(columns, rows);

        doc.save("DMA-Report_"+siteName.replace(" ","-")+"_"+date+".pdf"); //download as PDF

        console.log("DONE!");
        };
    }
)};

</script>


<template>
   <div class="flex flex-row justify-end max-w-[800px] m-auto">
        <GenericButton
            :callback="generatePDF"
            button-id="pdfDownload"
            class="
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