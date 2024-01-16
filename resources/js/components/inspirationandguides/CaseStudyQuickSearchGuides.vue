<script setup>

import SearchBar from "@/js/components/browseschools/SearchBar.vue";

</script>

<template>
    <div class="flex flex-row search-filter-components">
        <SearchBar
            :placeholder="`Search`"
        />
        <div class="">
            <slot name="filterBar" />
        </div>
    </div>
</template>
