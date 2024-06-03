<script setup>
import {useVuelidate} from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import {storeToRefs} from "pinia";
import {computed, onMounted, reactive, ref} from 'vue'
import {toast} from "vue3-toastify";

import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import QuoteDeliveryInfoModal from "@/js/components/quote/QuoteDeliveryInfoModal.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import {catalogueService} from "@/js/service/catalogueService";
import {quoteService} from "@/js/service/quoteService";
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

const quoteStore = useQuoteStore()
const {quoteVendorInfo} = storeToRefs(quoteStore)
const showDeliveryModal = ref(false)
const loadingVendorInfo = ref(false)


onMounted(async () => {
    loadingVendorInfo.value = true
    quoteVendorInfo.value[props.quoteVendor] = await quoteService.getVendorData(props.quoteVendor)
    loadingVendorInfo.value = false

})

const onClickGenerate = async () => {
    // make an await that wait for a promise
    // the promise will be resoluved once we clicked confirm or cancel on the modal
    // before await make the ref showDeliveryModal = true and once resolved make it false
    try {
        await showModal();
        quoteStore.checkoutVendor(props.quoteVendor).then(res => {
            toast.success("Quote generated successfully.")
            quoteStore.initializeQuote()
        }).catch(err => {
            if (err.status === '410') {
                console.log(' user do not have any quote')
            } else {
                console.log(err.message)
            }

        })
    } catch (err) {
        console.log("Cancelled")
    }

}
let modalConfirmFunction
let modalCancelFunction

const showModal = () => {
    return new Promise((resolve, reject) => {
        showDeliveryModal.value = true
        // Attach event listeners
        const confirmListener = () => {
            resolve(true)
            cleanup()
        }
        const cancelListener = () => {
            reject(false)
            cleanup()
        }

        const cleanup = () => {
            showDeliveryModal.value = false
        }
        modalConfirmFunction = confirmListener
        modalCancelFunction = cancelListener
    })
}

const handleRecConfirm = () => {
    if (modalConfirmFunction) {
        modalConfirmFunction()

    }

}
const handleRecCancel = () => {
    if (modalCancelFunction) {
        modalCancelFunction()
    }
}


const subtotalIncGst = computed(() => {
    return quoteStore.calculateSubtotalPerVendor(props.quoteVendor)
})

const subtotalExcGst = computed(() => {
    return catalogueService.getExcGstPrice(subtotalIncGst.value)
})

const onClickClearVendorCart = async () => {
    await quoteStore.clearQuoteByVendor(props.quoteVendor)
}
</script>

<template>
    <div
        v-if="showDeliveryModal"
        class="relative"
    >
        <QuoteDeliveryInfoModal
            @confirm="handleRecConfirm"
            @cancel="handleRecCancel"
        />
    </div>
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
                    :callback="onClickClearVendorCart"
                    class="!text-black hover:!text-white bg-white hove mb-4 hover:!bg-red-700"
                >
                    Cancel
                </GenericButton>
                <div class="font-thin genQuote information text-center">
                    Quotes are generated per vendor and school are responsible for raising PO and contacting the vendor
                    with the PO
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col mr-4 quoteVendorActionContainer">
        <div class="mb-2 text-base text-main-darkTeal">
            Vendor Information
        </div>
        <div
            v-if="!loadingVendorInfo && quoteVendorInfo[props.quoteVendor]"
            class="border-[1px] border-slate-300 grids innerContainer px-4 py-2 rounded-xl shadow"
        >
            <div class="flex flex-col">
                <div
                    v-for="(data,index) in Object.entries(quoteVendorInfo[props.quoteVendor])"
                    :key="index"
                    class="flex flex-col mb-2 text-center"
                >
                    <span class="font-medium text-main-darkTeal">
                        {{ data[0] }}
                    </span>
                    <span class="font-thin">{{ data[1] }}</span>
                </div>
            </div>
        </div>
        <div v-else>
            <Loader
                loader-message="Loading vendor information"
                loader-type="small"
                loader-message-class="text-lg"
            />
        </div>
    </div>
</template>
