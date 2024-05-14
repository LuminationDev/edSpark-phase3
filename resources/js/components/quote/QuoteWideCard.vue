<script setup lang="ts">
import {computed, ref} from 'vue'

import CatalogueCardDescGenerator from "@/js/components/catalogue/CatalogueCardDescGenerator.vue";
import ImageWithFallback from "@/js/components/global/ImageWithFallback.vue";
import {catalogueService} from "@/js/service/catalogueService";
import {CatalogueItemType} from "@/js/types/catalogueTypes";

const props = defineProps({
    itemData: {
        type: {} as () => CatalogueItemType,
        required: true
    }
})

const emits = defineEmits([])


const catCoverImageUrl = computed(() => {
    return catalogueService.getCatalogueCoverImage(props.itemData.cover_image);
})

const catCardShortSpec = computed(() => {
    return catalogueService.getCatalogueShortSpecObj(props.itemData)
})


const priceExtGst = computed(() => {
    return catalogueService.getExcGstPrice(props.itemData.price_inc_gst)
})

const priceIncGst = computed(() => {
    if (props.itemData.price_inc_gst) {
        return (+props.itemData.price_inc_gst).toFixed(2)
    }
})
</script>

<template>
    <div class="border-[1px] border-slate-300 flex flex-row h-44 mb-4 px-4 py-4 rounded shadow">
        <div class="basis-1/4 catalogueItemImage flex justify-center items-center h-full">
            <ImageWithFallback
                image-alt="item"
                :image-url="catCoverImageUrl"
                image-type="catalogue"
                class="border-[1px] border-slate-300 catalogueCoverImage h-36 object-contain p-2 rounded-xl w-36"
            />
        </div>
        <div class="basis-3/4 catalogueInformation place-items-center">
            <div class="grid grid-cols-4 w-full">
                <div class="col-span-3 grid nameAndSpec">
                    <span class="font-medium text-xl">{{ props.itemData.name }}</span>
                    <span class="mb-4"><CatalogueCardDescGenerator
                        :card-desc-obj="catCardShortSpec"
                    /></span>
                    <div class="removeButton text-red-600">
                        Remove
                    </div>
                </div>
                <div class="col-span-1 grid ml-auto mr-4 priceAndQuantity text-right">
                    <span class="priceExcGst text-xl">{{ `\$${priceExtGst} exc. GST` }} </span>
                    <span class="priceIncGst text-lg text-slate-600">{{ `\$${priceIncGst} inc. GST` }} </span>
                    <div class="border-[1px] flex justify-self-end flex-row gap-4 itemQuantity rounded-xl w-fit">
                        <div class="border-r-[1px] border-slate-300 px-2">
                            -
                        </div>
                        <div> {{ props.itemData.quantity }}</div>
                        <div class="border-l-[1px] border-slate-300 px-2">
                            +
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
