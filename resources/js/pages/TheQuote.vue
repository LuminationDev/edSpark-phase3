<script setup>
import {computed, onMounted} from "vue";
import {useRouter} from "vue-router";

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import GeneratedQuoteDisplay from "@/js/components/catalogue/quote/GeneratedQuoteDisplay.vue";
import QuoteVendorAction from "@/js/components/quote/QuoteVendorAction.vue";
import QuoteVendorListing from "@/js/components/quote/QuoteVendorListing.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {guid} from "@/js/helpers/guidGenerator";
import {useQuoteStore} from "@/js/stores/useQuoteStore";

const props = defineProps({})

const emits = defineEmits([])
const router = useRouter()
const quoteStore = useQuoteStore()

onMounted(() => {
    quoteStore.initializeQuote()
    quoteStore.getUserGenQuote()
})
const onClearQuote = () => {
    quoteStore.clearQuote()
}

const handlePrintQuote = async () => {
    const elementId = 'quote-template-print'; // Replace with your element's ID
    const element = document.getElementById(elementId);

    if (!element) {
        console.error(`Element with ID ${elementId} not found.`);
        return;
    }

    // Clone the element to preserve the original state
    const clonedElement = element.cloneNode(true);

    // Function to fetch the image and convert it to base64
    const getImageBase64 = async (img) => {
        const imgUrl = img.src;
        console.log(imgUrl)
        const response = await fetch(imgUrl);
        const blob = await response.blob();
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result);
            reader.onerror = reject;
            reader.readAsDataURL(blob);
        });
    };

    // Get all images in the cloned element
    const images = clonedElement.getElementsByTagName('img');
    for (const img of images) {
        const base64Data = await getImageBase64(img);
        img.src = base64Data;
    }
    const getAllStyles = () => {
        return Array.from(document.styleSheets).map(styleSheet => {
            try {
                return Array.from(styleSheet.cssRules).map(rule => {
                    return rule.cssText.trim();
                }).join(' ');
            } catch (e) {
                // Handle the SecurityError for cross-origin stylesheets
                console.warn(`Could not access stylesheet: ${styleSheet.href}`);
                return '';
            }
        }).join(' ');
    }

    const allStyles = getAllStyles();

    const styleElement = document.createElement('style');
    styleElement.textContent = allStyles;
    clonedElement.appendChild(styleElement);

    // Serialize the cloned element to HTML
    const pageContent = clonedElement.innerHTML;
    const payload = {html: JSON.stringify({html: pageContent})};
    console.log(payload)
    axios.post('http://localhost:8000/api/quote/generate-pdf', payload, {responseType: 'blob'})
        .then(response => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'page.pdf';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        })
        .catch(error => console.error('Error:', error));
}


</script>


<template>
    <BaseLandingHero
        :title="LandingHeroText['quote']['title']"
        :title-paragraph="LandingHeroText['quote']['subtitle']"
        swoosh-color="teal"
    />
    <div
        class="mt-16 mx-10 quotePageOuterContainer"
    >
        <div
            v-for="(products, vendor) in quoteStore.getQuoteGroupedByVendor"
            :key="vendor + guid()"
            class="grid grid-cols-10 gap-4 mb-8"
        >
            <div class="col-span-8 grid listingColumn">
                <QuoteVendorListing
                    :quote-vendor="vendor"
                    :quote-items="products"
                />
            </div>
            <div class="actionColumn col-span-2 grid">
                <QuoteVendorAction
                    :quote-vendor="vendor"
                    :quote-items="products"
                />
            </div>
        </div>
        <div class="col-span-10 flex justify-between flex-row otherRow">
            <GenericButton
                :callback="() => router.push('/catalogue')"
                type="teal"
            >
                Back to catalogue
            </GenericButton>
            <GenericButton
                :callback="onClearQuote"
                type="teal"
            >
                Clear quote
            </GenericButton>
        </div>
    </div>
    <div
        class="flex flex-col generatedQuoteContainer mt-10 mx-10"
    >
        <div class="font-medium mb-4 text-main-darkTeal text-xl">
            Generated quote
        </div>
        <GeneratedQuoteDisplay />
    </div>
    <GenericButton
        :callback="handlePrintQuote"
        type="teal"
    >
        invoke print
    </GenericButton>
</template>
