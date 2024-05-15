<script setup>
import {computed,ref} from 'vue'

import GenericButton from "@/js/components/button/GenericButton.vue";

const props = defineProps({
    quoteVendor: {
        type: String,
        required: true
    },
    quoteItems: {
        type: Array,
        required: true
    }
})

const emits = defineEmits([])
</script>

<template>
    <div class="flex flex-col mr-4 quoteVendorActionContainer">
        <div class="mb-2 text-base text-main-darkTeal">
            Generate quote
        </div>
        <div class="border-[1px] border-slate-300 grid grid-cols-2 innerContainer px-6 py-4 rounded-xl shadow">
            <div>Subtotal</div>
            <div>$100</div>
            <div>inc gst</div>
            <div>1200</div>
            <hr class="col-span-2">
            <div>Total</div>
            <div class="mb-4">
                99000
            </div>
            <div class="col-span-2 flex flex-col quoteAction">
                <GenericButton
                    :callback="() =>{}"
                >
                    Generate quote
                </GenericButton>
                <GenericButton
                    :callback="() =>{}"
                    class="!text-black bg-white mb-4"
                >
                    Cancel Quote
                </GenericButton>
                <div class="font-thin genQuote information text-center">
                    Quotes are generated per vendor and school are responsible for raising PO and contacting the vendor with the PO
                </div>
            </div>
        </div>
    </div>
</template>
