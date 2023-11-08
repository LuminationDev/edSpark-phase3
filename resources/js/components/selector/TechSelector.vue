<script setup>
import GenericSelector from "@/js/components/selector/GenericSelector.vue";
import TechSelectorItem from "@/js/components/selector/TechSelectorItem.vue";
import {schoolPartnerTech,schoolTech} from "@/js/constants/schoolTech";

const props = defineProps({
    existingTechUsed :{
        type: Array,
        required: false
    },
    colorTheme:{
        type: String,
        required: false
    }
})

const emits = defineEmits(['sendSchoolTech'])
const handleDataFromChildren = (techUsedList) =>{
    console.log(techUsedList)
    emits('sendSchoolTech', techUsedList)
}

</script>
<template>
    <div class="flex flex-col schoolTechContainer gap-10">
        <GenericSelector
            title="Department Provided Technologies"
            :list-data="schoolTech"
            :existing-data="existingTechUsed"
            :color-theme="colorTheme"
            @send-data-to-parent="handleDataFromChildren"
        >
            <template
                v-for="(item,index) in schoolTech"
                :key="index"
                #[`selectorItem_${index}`]
            >
                <TechSelectorItem :item="item" />
            </template>
        </GenericSelector>
        <GenericSelector
            title="Partners Provided Technologies"
            :list-data="schoolPartnerTech"
            :existing-data="existingTechUsed"
            :color-theme="colorTheme"
            @send-data-to-parent="handleDataFromChildren"
        >
            <template
                v-for="(item,index) in schoolPartnerTech"
                :key="index"
                #[`selectorItem_${index}`]
            >
                <TechSelectorItem :item="item" />
            </template>
        </GenericSelector>
    </div>
</template>
