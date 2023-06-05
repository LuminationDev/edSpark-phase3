<script setup>
import {ref, computed} from 'vue'
import {useRoute} from "vue-router";
import HardwareCard from "@/js/components/hardware/HardwareCard.vue";
import SearchBar from "@/js/components/browseschools/SearchBar.vue";
import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";

const props = defineProps({
    resourceList:{
        type: Array,
        required: true
    },
    searchType: {
        type: String,
        required: true
    }


})

const filterTerm = ref('')
const route = useRoute()

const filteredData = computed(() => {
    return props.resourceList.filter(data => {
        if (filterTerm.value.length < 1) return true
        console.log(data)
        if (data.post_title.toLowerCase().includes(filterTerm.value)) return true
    })
})

const handleSearchTerm = (term) => {
    filterTerm.value = term.toLowerCase()
}
</script>

<template>
    <div
        class="browse-schools-container mt-16 flex flex-col justify-center items-center"
    >
        <h3 class="font-semibold text-2xl">
            Browse all {{ searchType }}
        </h3>
        <SearchBar
            :placeholder="`Type in ${searchType} name`"
            @emit-search-term="handleSearchTerm"
        />
        <div class="flex flex-row w-full filterBarSearch">
            <slot name="filterBar" />
        </div>
        <div
            v-if="resourceList"
            class="resourceResult pt-10 flex flex-row flex-wrap justify-around gap-2 flex-1 w-full px-20"
        >
            <template
                v-for="(data, index) in filteredData"
                :key="index"
            >
                <template
                    v-if="searchType === 'advice'"
                >
                    <AdviceCard
                        :advice-content="data"
                        :number-per-row="2"
                        :show-icon="true"
                    />
                </template>
                <template v-else-if="searchType === 'software'">
                    <SoftwareCard
                        :software="data"
                        :number-per-row="2"
                    />
                </template>
                <template v-else-if="searchType == 'hardware'">
                    <HardwareCard
                        :hardware-content="data"
                        :number-per-row="4"
                    />
                </template>
            </template>
            <div
                v-if="filteredData.length <= 0"
                class="text-xl font-semibold"
            >
                No search result
            </div>
        </div>
    </div>
</template>
