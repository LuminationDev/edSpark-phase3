<script setup>
import {ref, computed} from 'vue'
import {useRoute} from "vue-router";
import VPagination from "@hennge/vue3-pagination";
import "@hennge/vue3-pagination/dist/vue3-pagination.css";


import HardwareCard from "@/js/components/hardware/HardwareCard.vue";
import SearchBar from "@/js/components/browseschools/SearchBar.vue";
import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import {findNestedKeyValue} from "@/js/helpers/objectHelpers";
import SchoolCard from "@/js/components/schools/SchoolCard.vue";
import PartnerCard from "@/js/components/partners/PartnerCard.vue";
import EventsCard from "@/js/components/events/EventsCard.vue";
import {guid} from "@/js/helpers/guidGenerator";
const props = defineProps({
    resourceList:{
        type: Array,
        required: true
    },
    searchType: {
        type: String,
        required: true
    },
    liveFilterObject:{
        type: Object,
        required: true
    }
})

const filterTerm = ref('')
const route = useRoute()

const filteredTermData = computed(() => {
    if(!props.resourceList) return []
    let resourceWithKeys = props.resourceList.map(resource => {
        resource['key'] = guid()
        return resource
    })
    return resourceWithKeys.filter(data => {
        if (filterTerm.value.length < 1) return true
        if(data.post_title){
            if (data.post_title.toLowerCase().includes(filterTerm.value)) return true
        } else if(data.product_name){
            // just to accomodate product_name in hardware
            if (data.product_name.toLowerCase().includes(filterTerm.value)) return true
        } else if(data.name){ // for partners
            if(data.name.toLowerCase().includes(filterTerm.value)) return true
        } else if(data.event_title) { // very straight forward for event
            if(data.event_title.toLowerCase().includes(filterTerm.value)) return true

        }
        return false
    })
})

const handleSearchTerm = (term) => {
    filterTerm.value = term.toLowerCase()
}

// original implementation of the filtering. keeping this code here - Erick
// Function to recursively check for matches in nested objects
function checkNested(obj, key, value) {
    if (obj && typeof obj === 'object') {
        for (let k in obj) {
            if (obj.hasOwnProperty(k) && k !== 'extra_content') {
                if (k === key) {
                    // if that compared brandName/category is inside value (an array) return true
                    for(let eachValue of value){
                        if(obj[k] === eachValue){
                            return true;
                        }
                    }
                } else if (checkNested(obj[k], key, value)) {
                    return true;
                }
            }
        }
    }
    return false;

}
// Function to filter the products based on the filterBy object
function filterProducts(products, filterBy) {
    const filterValues = Object.values(filterBy)
    let totalValuesCount = 0
    for(let eachValue of filterValues){
        totalValuesCount += eachValue.length
    }
    if (totalValuesCount === 0) {
        console.log('returned al products bcs no filterin hehe')
        return products; // Return all products if filterBy object is empty
    }
    return products.filter(product => {
        let filterResult = {}
        for (let key in filterBy) {
            // using findNestedKeyValue helper function
            let productValue = findNestedKeyValue(product, key).flat(1)
            let filterValues = filterBy[key];
            if(filterValues.length === 0){
                filterResult[key] = true
            } else{
                // Handle if there are more than 1 result inside productValue. should just check
                // if atleast one of the result match the filter and return true
                if(productValue.length === 1 ){
                    filterResult[key] = filterValues.includes(productValue[0])
                }else {
                    let result = false
                    productValue.forEach(item => {
                        if(filterValues.includes(item)){
                            result = true
                        }
                    })
                    filterResult[key] = result
                }
            }

            // below is original version-- above is refactored with findNestedKeyValue
            // keeping this code might come in handy as we add more filtering - Erick

            // if (filterBy.hasOwnProperty(key)) {
            //     let filterValues = filterBy[key];
            //     if(filterValues.length === 0){
            //         filterResult[key] = true
            //     } else{
            //         filterResult[key] = checkNested(product, key, filterValues)
            //     }
            // }
        }
        for(let eachResult of Object.values(filterResult)){
            if(!eachResult) return false
        }
        return true

    });
}


const filteredData = computed(()=>{
    if(Object.values(props.liveFilterObject).length === 0) return filteredTermData.value
    return filterProducts(filteredTermData.value, props.liveFilterObject)
})

// pagination code below
const page = ref(1)
const numberOfItemsPerPage = 9

const handleChangePageNumber = (newPageNumber) => {
    console.log('handleChangePage number is called')
    page.value= newPageNumber
}

const numberOfAvailablePages = computed(() =>{
    return Math.ceil(filteredData.value.length / numberOfItemsPerPage)
})

const paginatedFilteredData = computed(() =>{
    if(page.value === numberOfAvailablePages.value){
        //show the rest without hard limit
        return filteredData.value.slice((page.value - 1) * numberOfItemsPerPage)
    }else{

        return filteredData.value.slice((page.value - 1)  * numberOfItemsPerPage, page.value * numberOfItemsPerPage)
    }
})
// schools,

const formattedSearchTitle = computed(() =>{
    if(['school','event','partner'].includes(props.searchType)) return props.searchType + 's'
    else return props.searchType
})

</script>

<template>
    <div
        class="browse-schools-container flex items-center flex-col mt-16"
    >
        <h3 class="font-semibold text-2xl">
            Browse all {{ formattedSearchTitle }}
        </h3>
        <SearchBar
            :placeholder="`Type in ${searchType} name`"
            @emit-search-term="handleSearchTerm"
        />
        <div class="filterBarSearch flex justify-center items-center flex-col w-full md:!flex-row">
            <slot name="filterBar" />
        </div>
        <div
            v-if="resourceList" 
            class="grid grid-cols-1 gap-6 place-items-center pt-10 resourceResult md:!grid-cols-2 xl:!grid-cols-3"
        >
            <template
                v-for="(data) in paginatedFilteredData"
                :key="data['key']"
            >
                <template
                    v-if="searchType === 'advice'"
                >
                    <div
                        :key="data.id"
                        class="group h-[470px] max-w-[300px] my-4 transition-all w-full  hover:shadow-2xl lg:!max-w-[400px]"
                    >
                        <AdviceCard
                            :key="data.post_id"
                            :advice-content="data"
                            :number-per-row="2"
                            :show-icon="true"
                        />
                    </div>
                </template>
                <template v-else-if="searchType === 'software'">
                    <div
                        :key="data.id"
                        class="group h-[470px] max-w-[300px] my-4 transition-all w-full hover:shadow-2xl lg:!max-w-[400px]"
                    >
                        <SoftwareCard
                            :key="data.post_id"
                            :software="data"
                            :number-per-row="2"
                        />
                    </div>
                </template>
                <template v-else-if="searchType === 'hardware'">
                    <div
                        :key="data.id"
                        class="group h-[470px] max-w-[300px] my-4 transition-all w-full hover:shadow-2xl lg:!max-w-[400px]"
                    >
                        <HardwareCard
                            :key="data.id"
                            :hardware-content="data"
                            :number-per-row="4"
                        />
                    </div>
                </template>
                <template v-else-if="searchType === 'school'">
                    <div
                        :key="data.id"
                        class="group h-[470px] max-w-[300px] my-4 transition-all w-full hover:shadow-2xl lg:!max-w-[400px]]"
                    >
                        <SchoolCard
                            class="mx-auto"
                            :school-data="data"
                        />
                    </div>
                </template>
                <template v-else-if="searchType === 'partner'">
                    <div
                        :key="data.id"
                        class="
                            border-black
                            group
                            h-[470px]
                            max-w-[300px]
                            my-4
                            transition-all
                            w-full
                            hover:shadow-2xl
                            lg:!max-w-[400px]
                            "
                    >
                        <PartnerCard
                            :partner-content="data"
                        />
                    </div>
                </template>
                <template v-else-if="searchType === 'event'">
                    <div
                        :key="data.id"
                        class="
                            border-black
                            group
                            h-[470px]
                            max-w-[300px]
                            my-4
                            transition-all
                            w-full
                            hover:shadow-2xl
                            lg:!max-w-[400px]
                            "
                    >
                        <EventsCard
                            :event-content="data"
                            :show-icon="true"
                        />
                    </div>
                </template>
            </template>

            <div
                v-if="filteredData.length <= 0"
                class="font-semibold text-xl"
            >
                No search result
            </div>
        </div>
    </div>
    <div class="BaseSearchPaginationContainer flex justify-center mt-12 text-lg">
        <v-pagination
            v-model="page"
            :range-size="1"
            :pages="numberOfAvailablePages"
            active-color="#DCEDFF"
            @update:model-value="handleChangePageNumber"
        />
    </div>
</template>
<style lang="scss">
.BaseSearchPaginationContainer {

    .Pagination{
        font-size: large;

        .PaginationControl{

            .Control{
                height: 35px;
                width: 35px;
            }
        }
        li {

            button {
                font-size: 24px;
                margin-left: 16px;
                margin-right: 16px;

            }
            .Page,
            .Page-active {
                border: 1px transparent solid;
                padding: 16px;
            }
            .Page:hover{
                border: 1px #339999 solid;

            }


        }
    }
}
</style>

