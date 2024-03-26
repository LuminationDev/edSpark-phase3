

<template>
    <div class="ml-28 mt-72">
        <input
            type="file"
            accept=".pdf"
            @change="handleFileUpload"
        >
        <div
            v-if="pdfContent"
            v-html="pdfContent"
        />
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
