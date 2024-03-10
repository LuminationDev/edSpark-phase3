<script setup>
    import { ref, onMounted } from 'vue';
    import jsPDF from 'jsPDF';
    import autoTable from 'jspdf-autotable'
    import html2canvas from 'html2canvas';
    import html2pdf from 'html2pdf.js'
    import download from 'downloadjs'
    import GenericButton from '../button/GenericButton.vue';
    import {useUserStore} from "@/js/stores/useUserStore";
    import moment from 'moment';
    // import LatoFontReg from './Lato-Regular-normal.js';
    import MuseoSans100 from './MuseoSans-100-normal.js';
    import MuseoSans500 from './MuseoSans-500-normal.js';
    
    import * as htmlToImage from 'html-to-image';
    import { toPng, toJpeg, toBlob, toPixelData, toSvg } from 'html-to-image';

    const userStore = useUserStore()

    const doc = new jsPDF({
        orientation: 'portrait',
        unit: 'cm', 
        format: 'a4'
    });
      
        
    doc.addFileToVFS('MuseoSans-100-normal.ttf', MuseoSans100);
    doc.addFont('MuseoSans-100-normal.ttf', 'MuseoSans-100', 'normal');    
    
    doc.addFileToVFS('MuseoSans-500-normal.ttf', MuseoSans500);
    doc.addFont('MuseoSans-500-normal.ttf', 'MuseoSans-500', 'normal');   

    const siteName = userStore.getUserSiteName;
    const date = moment(new Date()).format('DD-MM-YYYY');

  

    const generatePDF = () => {
        console.log("Generating!");

        
        htmlToImage.toPng(document.getElementsByClassName('DMADomainContainer')[0].getElementsByTagName('div')[0], {skipFonts: true})
        // htmlToImage.toPng(document.getElementsByClassName('DMADomainContainer')[0], {skipFonts: true})
        // htmlToImage.toPng(document.getElementsByClassName('radial-chart')[0], {skipFonts: true})
        //DMADomainContainer
        .then(function (dataUrl) {
            const img = new Image();
            img.src = dataUrl;

        img.onload = () => {
        // await for the image to be fully loaded

        
        doc.setFont("MuseoSans-500");
        doc.setFontSize(24);
        doc.text("Digital Maturity Assessment", 1, 1.5);
        doc.setLineWidth(0.05);
        
        doc.setFont("MuseoSans-100");
        doc.setFontSize(20);
        doc.text(siteName+" | "+date, 1, 2.6);

        doc.line(1, 3.6, 20, 3.6);

        doc.addImage(img, "png", 1, 5, 19, 24);

        doc.setFontSize(16);


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
   <div>
        <section slot="pdf-content" class="mb-4">
            <!-- PDF Content Here -->
            <slot />
        </section>

        <GenericButton
            :callback="generatePDF"
            button-id="pdfDownload"
            class="
                text-white
                hover:bg-slate-50
                bg-secondary-blueberry
                font-medium
                px-12
                py-2
                text-lg
                ">
            Download
        </GenericButton>
   </div>
</template>./MuseoSans100.js