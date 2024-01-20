<script setup lang="ts">
import useVuelidate from "@vuelidate/core";
import {email, maxLength, required, url} from "@vuelidate/validators";
import {reactive} from "vue";

import TinyMceRichTextInput from "@/js/components/bases/frontendform/TinyMceEditor/TinyMceRichTextInput.vue";
import TextInput from "@/js/components/feedbackform/TextInput.vue";


const state = reactive({
    name: '',
    email: '',
    organisation: '',
    urlissue: '',
    content: ''
})

const rules = {
    name: {required},
    email: {
        required, email,
        maxLength: maxLength(255)
    },
    organisation: {required},
    urlissue: {required, url, maxLength: maxLength(2083)},
    content: {required}
}

const v$ = useVuelidate(rules, state)
console.log(v$)

const handleTinyRichContent = (data) => {
    console.log('base form received ' + data)
    v$.value.content.$model = data
}

</script>

<template>
    <div
        id="BaseFormParent"
        class="BaseFormContainer border-[1px] flex flex-col mt-2 mx-5 p-8 rounded-2xl text-black md:!mx-10"
    >
        <div class="Introduction formHeader my-4">
            <div class="flex justify-between flex-row">
                <div class="font-semibold text-[36px]">
                    Feedback form
                </div>
                <div class="flex flex-col smallAutoSaveHeaderSection">
                    <slot name="formHeader" />
                </div>
            </div>
            <TextInput
                v-model="v$.name.$model"
                :v$="v$.name"
                class="my-2"
                placeholder="Enter your name"
                :with-no-left-margin="true"
            >
                <template #label>
                    Contact name
                </template>
            </TextInput>
            <div class="ContainerTemp my-2 richContent">
                <TextInput
                    v-model="v$.email.$model"
                    :v$="v$.email"
                    class="my-2"
                    placeholder="Enter your email address"
                    :with-no-left-margin="true"
                >
                    <template #label>
                        Contact email address
                    </template>
                </TextInput>
            </div>
            <div class="ContainerTemp my-2 richContent">
                <TextInput
                    v-model="v$.organisation.$model"
                    :v$="v$.organisation"
                    class="my-2"
                    placeholder="Enter the name of the school or organisation you work for"
                    :with-no-left-margin="true"
                >
                    <template #label>
                        Organisation Name
                    </template>
                </TextInput>
            </div>
            <div class="ContainerTemp my-2 richContent">
                <TextInput
                    v-model="v$.urlissue.$model"
                    :v$="v$.urlissue"
                    class="my-2 placeholder:text-red-700"
                    placeholder="https://edspark.sa.edu/software"
                    :with-no-left-margin="true"
                >
                    <template #label>
                        URL of page with issue
                    </template>
                </TextInput>
            </div>
            <div class="ContainerTemp my-2 richContent">
                <label> Describe your issue</label>
                <TinyMceRichTextInput
                    :src-content="v$.content.$model"
                    :v$="v$.content"
                    @emit-tiny-rich-content="handleTinyRichContent"
                />
            </div>
        </div>
    </div>
</template>
<style>

</style>
