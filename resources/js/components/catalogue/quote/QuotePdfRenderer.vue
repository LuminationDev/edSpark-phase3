<script setup>
import {computed,ref} from 'vue'

import {catalogueService} from "../../../service/catalogueService";

const props = defineProps({
    quote:{
        type: Array,
        required:true
    }
})
console.log(props.quote)
</script>

<template>
    <div
        id="quote-template-print"
        class="QuotePDFDisplay flex flex-col mt-10 w-full"
    >
        <div class="bg-main-teal h-36 header text-white w-full">
            EdSpark Quote
        </div>
        <div class="flex flex-col mt-10 quote-content">
            <div class="flex flex-row info-row">
                <div class="basis-1/2 flex flex-col left-column vendor-info">
                    vendor info quote number
                </div>
                <div class="basis-1/2 flex flex-col right-column user-info">
                    user info
                </div>
            </div>
            <div class="grid quote-table">
                <div class="bg-gray-300 font-medium grid grid-cols-11 gap-2 p-2 quote-table-header-row">
                    <div class="col-span-2">
                        Items
                    </div>
                    <div class="col-span-2">
                        Descriptions
                    </div>
                    <div class="col-span-2">
                        Notes
                    </div>
                    <div class="col-span-1">
                        Quantity
                    </div>
                    <div class="col-span-1">
                        Product Number
                    </div>
                    <div class="col-span-1">
                        Price Expiry
                    </div>
                    <div class="col-span-1">
                        Cost (inc. GST)
                    </div>
                    <div class="col-span-1">
                        Cost (ex. GST)
                    </div>
                </div>
                <div class="grid grid-cols-11 gap-2 p-2 quote-table-body">
                    <template
                        v-for="(item,index) in quote.quote_content"
                        :key="index"
                    >
                        <div class="col-span-2 item-name">
                            {{ item.name }}
                        </div>
                        <div class="col-span-2 item-desc">
                            This item description
                        </div>
                        <div class="col-span-2 notes">
                            Test quote do not honor.
                        </div>
                        <div class="col-span-1 quantity">
                            {{ item.quantity }}
                        </div>
                        <div class="col-span-1 product-number">
                            {{ item.product_number }}
                        </div>
                        <div class="col-span-1 price-expiry">
                            {{ item.price_expiry }}
                        </div>
                        <div class="col-span-1 cost-inc-gst">
                            {{ item.price_inc_gst }}
                        </div>
                        <div class="col-span-1 cost-ex-gst">
                            {{ catalogueService.getExcGstPrice(item.price_inc_gst) }}
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
