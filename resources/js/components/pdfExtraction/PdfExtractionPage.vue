<template>
    <div>
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
    </div>
</template>

<script setup>
import mammoth from 'mammoth';
import { ref } from 'vue';

const htmlContent = ref('');
const error = ref('');

const handleFileUpload = async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    try {
        const html = await convertToHtml(file);
        htmlContent.value = html;
        error.value = ''; // Clear any previous errors
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
    console.log(result.value)
    return result.value;
};
</script>

<style scoped>
.error {
  color: red;
}
</style>
