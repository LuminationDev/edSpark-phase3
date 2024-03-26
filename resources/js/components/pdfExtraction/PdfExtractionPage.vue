<script setup>
import pdfParse from 'pdf-parse';
import { ref } from 'vue';

const pdfContent = ref(null);
const jsonContent = ref(null);

const handleFileUpload = async event => {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = async () => {
        const buffer = reader.result;
        const text = await parsePdf(buffer);
        pdfContent.value = text;
    };
    reader.readAsArrayBuffer(file);
};

const parsePdf = async (buffer) => {
    const pdf = await pdfParse(buffer, { max: 0xfffff, pagerender: null, encoding: 'UTF-8' }); // Adjust encoding as needed
    return pdf.text;
};

const convertToJson = () => {
    jsonContent.value = JSON.stringify({ content: pdfContent.value });
};
</script>

<template>
    <div>
        <input
            type="file"
            accept=".pdf"
            @change="handleFileUpload"
        >
        <div v-if="pdfContent">
            {{ pdfContent }}
        </div>
        <button @click="convertToJson">
            Convert to JSON
        </button>
        <div v-if="jsonContent">
            {{ jsonContent }}
        </div>
    </div>
</template>

<style scoped>

</style>
