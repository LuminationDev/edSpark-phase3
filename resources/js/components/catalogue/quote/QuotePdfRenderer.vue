<script setup>
import {computed, ref} from 'vue'

import {catalogueService} from "../../../service/catalogueService";

const props = defineProps({
    quote: {
        type: Array,
        required: true
    }
})
console.log(props.quote)

const vendorData = {
    vendor_name: 'CompNow',
    address: '9 Commercial Street, Marleston SA 5033',
    abn: '48592886118',
    order_email: 'sits@compnow.com.au',
    phone: '0888133800',
    contact: 'Ben Stratton - SA Education K-12 Sales Manager',
    direct_phone: '0477730029',
    email: 'sits@compnow.com.au',
}
</script>

<template>
    <div
        id="quote-template-print"
        class="QuotePDFDisplay flex flex-col h-[900px] mt-10 p-4 w-full"
    >
        <div class="bg-main-teal h-36 header p-6 text-2xl text-white w-full">
            EdSpark Quote
        </div>
        <div class="flex flex-col h-full mt-10 px-4 quote-content">
            <div class="flex flex-row info-row">
                <div class="basis-1/2 flex flex-col left-column vendor-info">
                    <span>SA Department for Education</span>
                    <span class="mb-1 text-3xl">Hardware quote</span>
                    <span class="text-main-darkTeal text-xl">Ref no. 123456789</span>
                    <span class="mb-8 text-main-darkTeal">28 May 2024</span>
                    <div class="flex flex-col vendor-info">
                        <div class="font-semibold text-2xl text-main-darkTeal vendor-header">
                            Vendor:
                        </div>
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
                            John Citizen
                        </div>
                        <div class="mb-1">
                            1 Test Street
                        </div>
                        <div class="mb-1">
                            Adelaide
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
                        Product Number
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
                        v-for="(item, index) in quote.quote_content"
                        :key="index"
                    >
                        <div class="flex">
                            <div class="flex-2 item-name">
                                {{ item.name }}
                            </div>
                            <div class="flex-2 item-desc">
                                This item description
                            </div>
                            <div class="flex-2 notes">
                                Test quote do not honor.
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
                                {{ item.price_inc_gst }}
                            </div>
                            <div class="cost-ex-gst flex-1">
                                {{ catalogueService.getExcGstPrice(item.price_inc_gst) }}
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <div
                class="= border-main-darkTeal border-t-2 flex justify-end items-end mt-auto quote-footer"
            >
                Page 1 of 1
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
</style>