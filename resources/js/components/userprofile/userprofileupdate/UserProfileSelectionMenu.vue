<script setup lang="ts" xmlns:src="http://www.w3.org/1999/xhtml">

import UserSelector from "@/js/components/userprofile/userprofileupdate/UserSelector.vue";
import {userSubjects} from "@/js/components/userprofile/userprofileupdate/userListing";
import {onMounted, ref} from "vue";


const itemFromChild = ref()
const selectedValueFromChild = ref()
const props = defineProps({
    displayUsernameInput:{
        type: String,
        required: true
    }
})

const handleItemClick = (items) => {

    itemFromChild.value = items
    console.log('Subject name selected inside the parent:  ' + itemFromChild.value)
};
const handleSelectedValue = (valuess) => {

    selectedValueFromChild.value = valuess
    console.log('Subject value selected inside the parent:  ' + selectedValueFromChild.value)
};

const toggleSelect=  ref(false)

</script>

<template>
    <div
        class="
            UserProfileSelectionMenuContainer
            bg-white
            h-full
            w-full
            rounded-2xl
            gap-12 flex flex-col"
    >
        <div class="text-2xl m-10 "><div>Display Name</div>
            <input class="mt-4 rounded-2xl border-1" v-model="props.displayUsernameInput">
        </div>
        <div class="text-2xl m-10"><div>Year Level</div>
            <div class="gap-10 flex">
                <div class="gap-2 flex mt-2"><p class="text-lg">Y1</p><input type="checkbox" class="mt-1 w-6 h-6"></div>
                <div class="gap-2 flex mt-2"><p class="text-lg">Y2</p><input type="checkbox" class="mt-1 w-6 h-6"></div>
                <div class="gap-2 flex mt-2"><p class="text-lg">Y3</p><input type="checkbox" class="mt-1 w-6 h-6"></div>
                <div class="gap-2 flex mt-2"><p class="text-lg">Y4</p><input type="checkbox" class="mt-1 w-6 h-6"></div>
                <div class="gap-2 flex mt-2"><p class="text-lg">Y5</p><input type="checkbox" class="mt-1 w-6 h-6"></div>
                <div class="gap-2 flex mt-2"><p class="text-lg">Y6</p><input type="checkbox" class="mt-1 w-6 h-6"></div>
                <div class="gap-2 flex mt-2"><p class="text-lg">Y7</p><input type="checkbox" class="mt-1 w-6 h-6"></div>
                <div class="gap-2 flex mt-2"><p class="text-lg">Y8</p><input type="checkbox" class="mt-1 w-6 h-6"></div>
                <div class="gap-2 flex mt-2"><p class="text-lg">Y9</p><input type="checkbox" class="mt-1 w-6 h-6"></div>
                <div class="gap-2 flex mt-2"><p class="text-lg">Y10</p><input type="checkbox" class="mt-1 w-6 h-6"></div>
                <div class="gap-2 flex mt-2"><p class="text-lg">Y11</p><input type="checkbox" class="mt-1 w-6 h-6"></div>
                <div class="gap-2 flex mt-2"><p class="text-lg">Y12</p><input type="checkbox" class="mt-1 w-6 h-6"></div>
            </div>
        </div>
        <div class="text-2xl m-10"><div>Interests</div>
            <div class="gap-10 flex">
                <div class="gap-2 flex mt-2"><input type="checkbox" class="mt-2 w-20 h-20 rounded-lg"></div>
                <div class="gap-2 flex mt-2"><input type="checkbox" class="mt-2 w-20 h-20 rounded-lg"></div>
                <div class="gap-2 flex mt-2"><input type="checkbox" class="mt-2 w-20 h-20 rounded-lg"></div>
                <div class="gap-2 flex mt-2"><input type="checkbox" class="mt-2 w-20 h-20 rounded-lg"></div>
                <div class="gap-2 flex mt-2"><input type="checkbox" class="mt-2 w-20 h-20 rounded-lg"></div>
                <div class="gap-2 flex mt-2"><input type="checkbox" class="mt-2 w-20 h-20 rounded-lg"></div>
                <div class="gap-2 flex mt-2"><input type="checkbox" class="mt-2 w-20 h-20 rounded-lg"></div>
            </div>
        </div>














        <div class="text-2xl m-10"><div>Subjects</div>
            <div class="flex flex-row">
                <UserSelector class="" :list-data="userSubjects" @store-item-name="handleItemClick" v-model="toggleSelect" @send-selected-values="handleSelectedValue">
                </UserSelector>
            </div>
        </div>





























        <div class="text-2xl m-10"><div>Extra Items</div>
            <div class="gap-10 flex">
                <div class="gap-2 flex mt-2"><label><input type="checkbox" class="checkbox"><span class="label mt-2 w-20 h-20 rounded-lg"><img
                    src="https://cdn-icons-png.flaticon.com/512/2815/2815428.png" class="bg-transparent align-center w-12 h-12 mt-4 m-auto"></span></label></div>
                <div class="gap-2 flex mt-2"><label><input type="checkbox" class="checkbox"><span class="label mt-2 w-20 h-20 rounded-lg"><img
                    src="" class="bg-transparent align-center w-12 h-12 mt-4 m-auto"></span></label></div>
                <div class="gap-2 flex mt-2"><label><input type="checkbox" class="checkbox"><span class="label mt-2 w-20 h-20 rounded-lg"><img
                    src="" class="bg-transparent align-center w-12 h-12 mt-2 m-auto"></span></label></div>
                <div class="gap-2 flex mt-2"><label><input type="checkbox" class="checkbox"><span class="label mt-2 w-20 h-20 rounded-lg"><img
                    src="" class="bg-transparent align-center w-12 h-12 mt-2 m-auto"></span></label></div>
                <div class="gap-2 flex mt-2"><label><input type="checkbox" class="checkbox"><span class="label mt-2 w-20 h-20 rounded-lg"><img
                    src="" class="bg-transparent align-center w-12 h-12 mt-2 m-auto"></span></label></div>
                <div class="gap-2 flex mt-2"><label><input type="checkbox" class="checkbox"><span class="label mt-2 w-20 h-20 rounded-lg"><img
                    src="" class="bg-transparent align-center w-12 h-12 mt-2 m-auto"></span></label></div>
                <div class="gap-2 flex mt-2"><label><input type="checkbox" class="checkbox"><span class="label mt-2 w-20 h-20 rounded-lg"><img
                    src="" class="bg-transparent align-center w-12 h-12 mt-2 m-auto"></span></label></div>
            </div>
        </div>

        <div class="text-2xl m-10"><div>Biography</div>
            <input class="mt-4 h-44 rounded-2xl">

        </div>

    </div>
</template>

<style scoped>
.checkbox {
    display: none;
}
.label {
    border: 1px solid #0d9aa6;
    display: inline-block;
    background: white;
    opacity: 1;
    /* background: url("unchecked.png") no-repeat left center; */
    /* padding-left: 15px; */
}
input[type=checkbox]:checked + .label {
    background: #55c5c9;

    opacity: 1;
    /* background-image: url("checked.png"); */
}
</style>
