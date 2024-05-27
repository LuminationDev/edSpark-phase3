<script setup>
import {computed, ref} from 'vue'

import GenericButton from "@/js/components/button/GenericButton.vue";
import {catalogueService} from "@/js/service/catalogueService";
import {useQuoteStore} from "@/js/stores/useQuoteStore";

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
const quoteStore = useQuoteStore()

const onClickGenerate = () => {
    // quoteStore.calculateSubtotalPerVendor(props.quoteVendor)
    quoteStore.checkoutVendor(props.quoteVendor).then(res =>{
        console.log('Success generating quoote')
        toast.success("Quote generated successfully.")
        quoteStore.initializeQuote()
    }).catch(err =>{
        if(err.status === '410'){ console.log(' user do not have any quote')}
        else{
            console.log(err.message)
        }

    })
}
const subtotalIncGst = computed(() => {
    return quoteStore.calculateSubtotalPerVendor(props.quoteVendor)
})

const subtotalExcGst = computed(() => {
    return catalogueService.getExcGstPrice(subtotalIncGst.value)
})


</script>

<template>
    <div class="flex flex-col mr-4 quoteVendorActionContainer">
        <div class="mb-2 text-base text-main-darkTeal">
            Generate quote
        </div>
        <div class="border-[1px] border-slate-300 grid grid-cols-2 innerContainer px-6 py-4 rounded-xl shadow">
            <div class="my-4">
                Total (ex. GST)
            </div>
            <div class="my-4">
                {{ "$ " + subtotalExcGst }}
            </div>
            <hr class="col-span-2">
            <div class="my-4 text-slate-600">
                Total (inc. GST)
            </div>
            <div class="my-4 text-slate-600">
                {{ "$ " + subtotalIncGst }}
            </div>
            <div class="col-span-2 flex flex-col quoteAction">
                <GenericButton
                    :callback="onClickGenerate"
                    class="mb-4"
                >
                    Generate quote
                </GenericButton>
                <GenericButton
                    :callback="() =>{}"
                    class="!text-black hover:!text-white bg-white hove mb-4 hover:!bg-red-700"
                >
                    Cancel Quote
                </GenericButton>
                <div class="font-thin genQuote information text-center">
                    Quotes are generated per vendor and school are responsible for raising PO and contacting the vendor
                    with the PO
                </div>
            </div>
        </div>
    </div>
</template>
