<script setup>
import GenericButton from "@/js/components/button/GenericButton.vue";
import {useRouter} from "vue-router";


const router = useRouter()
const handleClickBackTosSchoolButton = () =>{
    router.push({name: 'schools'})
}
</script>


<template>
    <div class="text-center flex flex-col justify-center items-center  font-bold text-md text-black w-1/3">
        School not available Sorry!
        <GenericButton
            :callback="handleClickBackTosSchoolButton"
            type="school"
        >
            <div class="font-bold py-2 px-2 text-md">
                Back to School page
            </div>
        </GenericButton>
    </div>
</template>

