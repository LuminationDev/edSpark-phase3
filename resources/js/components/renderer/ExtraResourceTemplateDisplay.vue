<script setup>
import {computed, ref} from 'vue';

import DateListRenderer from "@/js/components/renderer/DateListRenderer.vue";
import ExtraResourceRenderer from "@/js/components/renderer/ExtraResourceRenderer.vue";
import IconListRenderer from "@/js/components/renderer/IconListRenderer.vue";
import NumberedListRenderer from "@/js/components/renderer/NumberedListRenderer.vue";
import {findNestedKeyValue} from "@/js/helpers/objectHelpers";

const props = defineProps({
    content: {
        type: Array,
        required: true
    }
});

const renderMap = computed(() => {
    const mapping = {
        "App\\Filament\\PageTemplates\\IconList": IconListRenderer,
        "App\\Filament\\PageTemplates\\ExtraResource": ExtraResourceRenderer,
        "App\\Filament\\PageTemplates\\NumberedList": NumberedListRenderer,
        "App\\Filament\\PageTemplates\\DateList": DateListRenderer,
    };

    return props.content.map(item => {
        const component = mapping[item.data.template];
        return {
            component,
            data: findNestedKeyValue(item.data, 'item')[0],
            title: findNestedKeyValue(item.data, 'title')[0] || ''
        };
    });
});
</script>

<template>
    <template
        v-for="(item, index) in renderMap"
        :key="index"
    >
        <component
            :is="item.component"
            v-if="item.component"
            :item-array="item.data"
            :item-title="item.title"
        />
    </template>
</template>
