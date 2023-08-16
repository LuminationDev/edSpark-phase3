<script setup>
import { computed, ref } from 'vue'
import AdviceNumberedListRenderer from "@/js/components/advice/Renderer/AdviceNumberedListRenderer.vue";
import AdviceIconListRenderer from "@/js/components/advice/Renderer/AdviceIconListRenderer.vue";
import AdviceNumberedIconListRenderer from "@/js/components/advice/Renderer/AdviceNumberedIconListRenderer.vue";
import AdviceExtraResourcesRenderer from '@/js/components/advice/Renderer/AdviceExtraResourcesRenderer.vue';

const props  = defineProps({
    content:{
        type: Array,
        required: true
    }
});

const contentType = computed(() => {
    if (Object.keys(props.content.data).includes('extra_content')) {
        if (props.content.data.template.includes('Numbereditems')) {
            return 'numbereditems';
        } else if (props.content.data.template.includes('Iconitems')) {
            return 'iconitems';
        } else if (props.content.data.template.includes('Numberedicontems')) {
            return 'numberedicontems';
        }
    } else if (props.content.type === 'extra_resources') {
        return 'extraresources';
    }
});

/**
 * itemArray = Array<{
 *     content: string-html
 *     heading: string
 *     icon: string-icon-name
 * }>
 */
const itemArray = computed(()=> {
    if (Object.keys(props.content.data).includes('extra_content')) {
        if (props.content.data.template.includes('Numbereditems')) {
            return props.content.data.extra_content.numbereditems.item;
        } else if (props.content.data.template.includes('Iconitems')) {
            return props.content.data.extra_content.iconitems.item;
        } else if (props.content.data.template.includes('Numberedicontems')) {
            return props.content.data.extra_content.numberedicontems.item;
        }
    } else if (props.content.type === 'extra_resources') {
        return props.content.data.item;
    }
})




</script>

<template>
    <div class="extraContentRendererContainer flex flex-col my-2 py-2">
        <template v-if="contentType == 'numbereditems'">
            <AdviceNumberedListRenderer :item-array="itemArray" />
        </template>
        <template v-else-if="contentType == 'iconitems'">
            <AdviceIconListRenderer :item-array="itemArray" />
        </template>
        <template v-else-if="contentType == 'numberedicontems'">
            <AdviceNumberedIconListRenderer :item-array="itemArray" />
        </template>
        <template v-else-if="contentType === 'extraresources'">
            <AdviceExtraResourcesRenderer
                v-for="(item, index) in itemArray"
                :item-array="item"
            />
        </template>
        <template v-else>
            <div v-html="itemArray" />
        </template>
    </div>
</template>

