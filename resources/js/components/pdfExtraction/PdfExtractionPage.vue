<template>
    <div>
        <input
            ref="pdfInput"
            type="file"
            accept=".pdf"
            @change="handlePdfUpload"
        >
        <div
            v-if="convertedHtml"
            v-html="convertedHtml"
        />
    </div>
</template>

<script setup>
import { PDFDocument, rgb,StandardFonts } from 'pdf-lib'
import { ref } from 'vue';

const pdfInput = ref(null);
const convertedHtml = ref(null);

const handlePdfUpload = async () => {
    const file = pdfInput.value.files[0];
    if (!file) return;

    const reader = new FileReader();

    reader.onload = async (event) => {
        const buffer = event.target.result;

        try {
            const pdfDoc = await PDFDocument.load(new Uint8Array(buffer));
            let parsedText = '';

            for (let i = 0; i < pdfDoc.getPageCount(); i++) {
                const page = await pdfDoc.getPage(i + 1); // Pages are 1-based
                const textContent = await page.getTextContent();
                parsedText += textContent.items.map(item => item.str).join(' ');
            }

            convertedHtml.value = parsedText;
        } catch (error) {
            console.error('Error parsing PDF:', error);
        }
    };

    reader.readAsArrayBuffer(file);
};
</script>

<style scoped>
/* Add basic styling for the uploaded content if needed */
</style>
