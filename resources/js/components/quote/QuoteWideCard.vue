<script setup lang="ts">
import {useDebounceFn, watchDebounced} from '@vueuse/core'
import {cloneDeep} from "lodash";
import {storeToRefs} from "pinia";
import {computed, ref, watch} from 'vue'
import {toast} from "vue3-toastify";

import CatalogueCardDescGenerator from "@/js/components/catalogue/CatalogueCardDescGenerator.vue";
import ImageWithFallback from "@/js/components/global/ImageWithFallback.vue";
import {catalogueService} from "@/js/service/catalogueService";
import {quoteService} from "@/js/service/quoteService";
import {useQuoteStore} from "@/js/stores/useQuoteStore";
import {CatalogueItemType} from "@/js/types/catalogueTypes";

const props = defineProps({
    itemData: {
        type: {} as () => CatalogueItemType,
        required: true
    },
    displayOnly: {
        type: Boolean,
        required: false,
        default: false
    }
})

const emits = defineEmits([])

const itemQuantity = ref(props.itemData.quantity)

const quoteStore = useQuoteStore()
const {quote} = storeToRefs(quoteStore)

const catCoverImageUrl = computed(() => {
    return catalogueService.getCatalogueCoverImage(props.itemData.cover_image);
})

const catCardShortSpec = computed(() => {
    return catalogueService.getCatalogueShortSpecObj(props.itemData)
})


const priceExtGst = computed(() => {
    return catalogueService.getExcGstPrice(+props.itemData.price_inc_gst)
})

const priceIncGst = computed(() => {
    if (props.itemData.price_inc_gst) {
        return (+props.itemData.price_inc_gst).toFixed(2)
    }
})
const onClickIncrement = () => {
    itemQuantity.value++

}
const onClickDecrement = () => {
    if (itemQuantity.value > 0) {
        itemQuantity.value--
    }
}

const onClickRemove = async () => {
    const oldQuote = cloneDeep(quoteStore.getQuote)
    quoteStore.removeFromQuote(props.itemData.unique_reference)
    try {
        await quoteService.deleteItemInCart(props.itemData.unique_reference)
    } catch (err) {
        quote.value = oldQuote
        console.error('Failed to delete item, reverting to previous value', err.message)
        toast.error('Failed to delete item. Please try again')

    }
}

watchDebounced(itemQuantity, async () => {
    await quoteStore.changeQuantity(props.itemData, itemQuantity.value)
}, {debounce: 600, maxWait: 2000})

const itemQuantitySubtotal = computed(() => {
    return (+priceExtGst.value * itemQuantity.value).toFixed(2)
})

</script>

<template>
    <div class="border-[1px] border-slate-300 flex flex-row h-44 mb-4 px-4 py-4 rounded-xl shadow">
        <div class="basis-1/5 catalogueItemImage flex justify-center items-center h-full">
            <ImageWithFallback
                image-alt="item"
                :image-url="catCoverImageUrl"
                image-type="catalogue"
                class="border-[1px] border-slate-300 catalogueCoverImage h-36 object-contain p-2 rounded-xl w-36"
            />
        </div>
        <div class="basis-4/5 catalogueInformation place-items-center">
            <div class="grid grid-cols-4 h-full w-full">
                <div class="col-span-3 grid nameAndSpec">
                    <span class="font-medium text-xl">
                        {{ props.itemData.brand + " - " + props.itemData.name }}</span>
                    <span class="mb-4 text-gray-600">
                        {{ `Vendor: ${props.itemData.vendor}` }}
                        <CatalogueCardDescGenerator
                            :card-desc-obj="catCardShortSpec"
                        />
                    </span>
                    <div
                        v-if="!displayOnly"
                        class="cursor-pointer removeButton text-red-600"
                    >
                        <button
                            class="px-4 py-2 rounded-xl hover:!bg-red-600 hover:!text-white"
                            @click="onClickRemove"
                        >
                            Remove
                        </button>
                    </div>
                </div>
                <div class="col-span-1 grid h-full ml-auto mr-4 priceAndQuantity text-right">
                    <span class="priceExcGst text-xl">{{ `\$${priceExtGst} exc. GST` }} </span>
                    <span class="priceIncGst text-lg text-slate-600">{{ `\$${itemQuantitySubtotal}` }} </span>
                    <div
                        v-if="!displayOnly"
                        class="border-[1px] flex justify-self-end flex-row gap-4 itemQuantity mt-auto rounded-xl w-fit"
                    >
                        <div
                            class="border-r-[1px] border-slate-300 cursor-pointer px-2"
                            @click="onClickDecrement"
                        >
                            -
                        </div>
                        <div> {{ itemQuantity }}</div>
                        <div
                            class="border-l-[1px] border-slate-300 cursor-pointer px-2"
                            @click="onClickIncrement"
                        >
                            +
                        </div>
                    </div>
                    <div
                        v-else
                        class="flex justify-end flex-row text-right"
                    >
                        <span class="text-lg">{{
                            `Qty. ${itemQuantity} ` + ((+itemQuantity || +itemQuantity > 1) ? 'unit' : 'units')
                        }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
