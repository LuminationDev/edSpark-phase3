<script setup>
import {computed,ref} from 'vue'

import GenericButton from "@/js/components/button/GenericButton.vue";
import CatalogueCartIcon from "@/js/components/svg/catalogue/CatalogueCartIcon.vue";

const props = defineProps({

})

const emits = defineEmits([])
</script>

<template>
    <GenericButton
        class="!text-base py-3"
        :callback="() =>{}"
        type="teal"
    >
        <CatalogueCartIcon /><span class="ml-4" />Add to quote
    </GenericButton>
</template>