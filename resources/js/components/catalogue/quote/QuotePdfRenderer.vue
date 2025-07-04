<script setup>
import {storeToRefs} from "pinia";
import {computed, onMounted, ref} from 'vue'

import ImageWithFallback from "@/js/components/global/ImageWithFallback.vue";
import {imageURL} from "@/js/constants/serverUrl";
import {formatDateToDateMonth} from "@/js/helpers/dateHelper";
import {catalogueService} from "@/js/service/catalogueService";
import {useQuoteStore} from "@/js/stores/useQuoteStore";
import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({
    quote: {
        type: Object,
        required: true
    },
})
const userStore = useUserStore()
const {quoteVendorInfo, quoteUserInfo} = storeToRefs(useQuoteStore())

onMounted(() => {
    quoteUserInfo.value.name = userStore.getUserFullName ? userStore.getUserFullName : ""
    quoteUserInfo.value.institution = userStore.getUserSiteName ? userStore.getUserSiteName : ""

})
const getQuoteDisplayVendor = computed(() => {
    return props.quote?.quote_content[Object.keys(props.quote.quote_content)[0]]?.vendor
})

const vendorData = computed(() => {
    return quoteVendorInfo.value[getQuoteDisplayVendor.value]
})
const numberOfPage = ref(1)


const contentArrayForPrinting = computed(() => {
    const quoteContent = props.quote?.quote_content;
    const chunkSize = 9;

    if (!quoteContent) {
        return []
    }

    if (quoteContent.length <= chunkSize) {
        return [quoteContent];
    }

    // eslint-disable-next-line vue/no-side-effects-in-computed-properties
    numberOfPage.value = Math.ceil(quoteContent.length / chunkSize);
    const result = [];

    for (let i = 0; i < numberOfPage.value; i++) {
        const start = i * chunkSize;
        const end = start + chunkSize;
        const currentSection = quoteContent.slice(start, end);
        result.push(currentSection);
    }
    console.log(result)
    return result;
});


</script>

<template>
    <div
        v-for="(quoteItems,idx) in contentArrayForPrinting"
        id="quote-template-print"
        :key="idx"
        class="QuotePDFDisplay flex flex-col mt-10 p-4 w-full"
    >
        <div class="bg-main-teal flex items-end flex-row h-36 header p-6 text-2xl text-white w-full">
            <ImageWithFallback
                class="h-20 w-20"
                image-alt=""
                :image-url="`${imageURL}/uploads/image/edsparkLogo.png`"
                image-type="logo"
            />
            <img
                class="h-20 ml-4"
                src="@/assets/images/footer/dfe.svg"
                alt="logo"
            >
        </div>
        <div class="flex flex-col mt-6 px-8 quote-content">
            <div class="flex flex-row info-row">
                <div class="basis-1/2 flex flex-col left-column vendor-info">
                    <span>SA Department for Education</span>
                    <span class="mb-1 text-3xl">Hardware quote</span>
                    <span class="text-main-darkTeal text-xl">Ref no: {{ props.quote.quote_ref }}</span>
                    <span class="mb-8 text-main-darkTeal">{{ formatDateToDateMonth(props.quote.created_at) }}</span>
                    <div class="flex flex-col vendor-info">
                        <div class="font-semibold text-2xl text-main-darkTeal vendor-header">
                            Vendor:
                        </div>
                        <template v-if="vendorData && Object.keys(vendorData).length > 0">
                            <div
                                v-for="(item, index) in Object.entries(vendorData)"
                                :key="index"
                                class="flex flex-row last-of-type:mb-8 mb-1"
                            >
                                <div class="flex flex-1">
                                    {{ `${item[0]}:` }}
                                </div>
                                <div class="flex flex-2">
                                    {{ `${item[1]}` }}
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="basis-1/2 flex flex-col right-column user-info">
                    <div
                        class="
                            border-2
                            border-slate-300
                            flex
                            justify-center
                            items-center
                            flex-col
                            mb-4
                            p-4
                            rounded-xl
                            text-center
                            "
                    >
                        <span>If price has expired, contact the vendor for current pricing.</span>
                        <span>This quote has not been submitted to the vendor.</span>
                        <span>Please forward this quote along with a matching Purchase Order to the vendor listed.</span>
                        <span>

                            Standard <a
                                href="https://myintranet.learnlink.sa.edu.au/operations-and-management/procurement-and-travel/procurement"
                                target="_blank"
                            >
                                procurement policies and guidelines
                            </a> are applicable for all purchases

                        </span>
                    </div>
                    <div class="font-semibold text-2xl text-main-darkTeal vendor-header">
                        Deliver to:
                    </div>
                    <div
                        class="flex flex-col last-of-type:mb-8 mb-2"
                    >
                        <div class="mb-1">
                            {{ `${quoteUserInfo.name ? quoteUserInfo.name : ''}` }}
                        </div>
                        <div class="mb-1">
                            {{ `${quoteUserInfo.institution ? quoteUserInfo.institution : ''}` }}
                        </div>
                        <div class="mb-1">
                            {{ `${quoteUserInfo.address ? quoteUserInfo.address : ''}` }}
                        </div>
                        <div class="mb-1">
                            {{ `${quoteUserInfo.notes ? quoteUserInfo.notes : ''}` }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col quote-table">
                <span class="font-medium text-2xl text-main-darkTeal">Quotation:</span>
                <div class="bg-gray-300 flex font-semibold p-2 quote-table-header-row">
                    <div class="flex-2">
                        Items
                    </div>
                    <div class="flex-2">
                        Descriptions
                    </div>
                    <div class="flex-2">
                        Notes
                    </div>
                    <div class="flex-1">
                        Quantity
                    </div>
                    <div class="flex-1">
                        Product No.
                    </div>
                    <div class="flex-1">
                        Price Expiry
                    </div>
                    <div class="flex-1">
                        Cost (inc. GST)
                    </div>
                    <div class="flex-1">
                        Cost (ex. GST)
                    </div>
                </div>
                <div class="flex flex-col p-2 quote-table-body">
                    <template
                        v-for="(item, index) in quoteItems"
                        :key="index"
                    >
                        <div class="flex">
                            <div class="flex-2 flex-wrap item-name">
                                {{ item.name }}
                            </div>
                            <div class="flex-2 item-desc" />
                            <div class="flex-2 notes">
                                {{ item.notes }}
                            </div>
                            <div class="flex-1 quantity">
                                {{ item.quantity }}
                            </div>
                            <div class="flex-1 product-number">
                                {{ item.product_number }}
                            </div>
                            <div class="flex-1 price-expiry">
                                {{ item.price_expiry }}
                            </div>
                            <div class="cost-inc-gst flex-1">
                                {{ `$` + (+item.price_inc_gst * +item.quantity).toFixed(2) }}
                            </div>
                            <div class="cost-ex-gst flex-1">
                                {{
                                    `$` + (catalogueService.getExcGstPrice(+item.price_inc_gst) * +item.quantity).toFixed(2)
                                }}
                            </div>
                        </div>
                    </template>
                    <div
                        v-if="idx + 1 === contentArrayForPrinting.length"
                        class="flex total-row"
                    >
                        <div class="flex-2" />
                        <div class="flex-2" />
                        <div class="flex-2" />
                        <div class="flex-1" />
                        <div class="flex-1" />
                        <div class="flex-1" />
                        <div class="flex-1">
                            <div class="flex justify-end items-end font-semibold text-lg">
                                {{ `Total: $${props.quote.total_price_ex_gst}` }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="= border-main-darkTeal border-t-[1px] flex justify-end items-end quote-footer"
            >
                {{ `Page ${idx + 1} of ${contentArrayForPrinting.length}` }}
            </div>
        </div>
    </div>
</template>
<style>
.flex-1 {
    flex: 1;
}

.flex-2 {
    flex: 2;
}

#quote-template-print {
    aspect-ratio: 1.41 / 1;
}

</style>