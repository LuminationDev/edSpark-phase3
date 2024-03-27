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
        <button
            v-if="htmlContent"
            @click="downloadHtml"
        >
            Download HTML
        </button>
        <button
            v-if="htmlContent"
            @click="downloadJson"
        >
            Download JSON
        </button>
        <button
            v-if="Object.keys(criteriaSections).length > 0"
            @click="downloadCriteriaJson"
        >
            Download Criteria JSON
        </button>
    </div>
</template>

<script setup>
import mammoth from 'mammoth';
import { ref } from 'vue';

const htmlContent = ref('');
const error = ref('');
const criteriaSections = ref([]);

const handleFileUpload = async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    try {
        const html = await convertToHtml(file);
        htmlContent.value = html;
        error.value = ''; // Clear any previous errors
        extractCriteriaSections(html);
    } catch (error) {
        console.error('Error processing file:', error);
        htmlContent.value = ''; // Clear content in case of error
        error.value = 'Error processing file: ' + error.message;
    }
};

const convertToHtml = async (file) => {
    const reader = new FileReader();
    const buffer = await new Promise((resolve, reject) => {
        reader.onload = () => resolve(reader.result);
        reader.onerror = reject;
        reader.readAsArrayBuffer(file);
    });

    const result = await mammoth.convertToHtml({ arrayBuffer: buffer });
    return result.value;
};

const downloadHtml = () => {
    const blob = new Blob([htmlContent.value], { type: 'text/html' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'document.html';
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
};

const downloadJson = () => {
    const text = stripHtml(htmlContent.value);
    const jsonContent = JSON.stringify({ content: text }, null, 2);
    const blob = new Blob([jsonContent], { type: 'application/json' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'document.json';
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
};

const downloadCriteriaJson = () => {
    const jsonContent = JSON.stringify({ "Success criteria": criteriaSections.value }, null, 2);
    const blob = new Blob([jsonContent], { type: 'application/json' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'criteria.json';
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
};

const stripHtml = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || '';
};

const extractCriteriaSections = (html) => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    const elements = doc.querySelectorAll('*');
    let criteriaFound = false;
    let criteriaSection = '';

    for (let i = 0; i < elements.length; i++) {
        const element = elements[i];
        if (element.textContent.trim().toLowerCase() === 'success criteria') {
            criteriaFound = true;
            criteriaSection = '';
        } else if (criteriaFound) {
            if (element.tagName === 'H1' || element.tagName === 'H2' || element.tagName === 'H3' || element.tagName === 'H4' || element.tagName === 'H5' || element.tagName === 'H6') {
                criteriaFound = false;
                if (criteriaSection !== '') {
                    criteriaSections.value.push({ content: criteriaSection.trim() });
                }
                criteriaSection = '';
            } else {
                criteriaSection += element.outerHTML;
            }
        }
    }

    if (criteriaSection !== '') {
        criteriaSections.value.push({ content: criteriaSection.trim() });
    }
};
</script>

<style scoped>
.error {
  color: red;
}
</style>
