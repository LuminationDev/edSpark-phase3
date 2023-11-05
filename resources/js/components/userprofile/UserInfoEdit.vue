<script setup lang="ts">

import {storeToRefs} from "pinia";
import {onBeforeMount, reactive} from "vue";

import TextInput from "@/js/components/bases/TextInput.vue";
import {useUserStore} from "@/js/stores/useUserStore";

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const state = reactive({
    fullName: '',
    biography: '',
    yearLevels: [],
    subjects: [],
    interests: [],
})

onBeforeMount(() => {
    state.fullName = currentUser.value.full_name
})

const formFieldData = {
    years: [
        {value: 1, displayText: 'one'},
        {value: 2, displayText: 'two'},
        {value: 3, displayText: 'three'},
        {value: 4, displayText: 'four'},
        {value: 5, displayText: 'five'},
        {value: 6, displayText: 'six'},
        {value: 7, displayText: 'seven'},
        {value: 8, displayText: 'eight'},
        {value: 9, displayText: 'nine'},
        {value: 10, displayText: 'ten'},
        {value: 11, displayText: 'eleven'},
        {value: 12, displayText: 'twelve'},
    ],
    allSubjects: [
        {value: 'English', displayText: 'English'},
        {value: 'Mathematics', displayText: 'Mathematics'},
        {value: 'Science', displayText: 'Science'},
        {value: 'HASS', displayText: 'Humanity and Social Sciences'},
        {value: 'The Arts', displayText: 'The Arts'},
        {value: 'Technologies', displayText: 'Technologies'},
        {value: 'Health and PE', displayText: 'Health and Physical Education'},
        {value: 'Languages', displayText: 'Languages'},
        {value: 'Work Studies', displayText: 'Work studies'}
    ],
    digitalTechnologies: [
        {value: 'Drones', displayText: 'Drones'},
        {value: 'VR', displayText: 'Virtual Reality (VR)'},
        {value: 'AR', displayText: 'Augmented Reality (AR)'},
        {value: 'Robotics', displayText: 'Robotics'},
        {value: 'AV systems', displayText: 'Audio Visual Systems'},
        {value: 'IoT', displayText: 'Internet of Things'}
    ],
}


</script>
<template>
    <div class="border-[1px] flex flex-col rounded-xl userInfoEditForm">
        <TextInput
            ref="titleInputRef"
            v-model="state.fullName"
            :v$="{}"
            field-id="titleInput"
            class="my-2"
            placeholder=""
        >
            <template #label>
                Full Name
            </template>
        </TextInput>

        <div class="flex flex-wrap gap-6">
            <label
                v-for="(year,index) in formFieldData.years"
                :key="index"
                class="flex gap-2"
                :for="year.value"
            >
                <input
                    :id="year.value"
                    v-model="state.yearLevels"
                    type="checkbox"
                    :value="year.value"
                >
                {{ year.displayText }}
            </label>
        </div>

        <pre>{{ currentUser }}</pre>
    </div>
</template>
<style>

</style>