<script setup lang="ts">
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import {reactive} from "vue";

import DropdownInput from "@/js/components/bases/DropdownInput.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import TextInputFloating from "@/js/components/bases/TextInputFloating.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import DashboardHero from "@/js/components/dashboard/DashboardHero.vue";
import FileIcon from "@/js/components/svg/FileIcon.vue";

const state = reactive({
    orgName: '',
    repName: '',
    repEmail: '',
    busType: ''
})


const rules = {
    orgName: {required},
    repName: {required},
    repEmail: {required},
    busType: {required}
}

const v$: any = useVuelidate(rules, state)


const partnerBusinessTypes = [
    {
        id: 0,
        name: 'software'
    }, {
        id: 1,
        name: 'hardware'
    }, {
        id: 2,
        name: 'content'
    }, {
        id: 3,
        name: 'service'
    }
]
</script>

<template>
    <DashboardHero class="-mt-extraHuge" />
    <div class="PartnerFormContainer flex justify-around flex-row mt-10 px-8  gap-8 lg:!px-10">
        <div class="basis-1/2 bg-white border-[1px] drop-shadow-lg flex partnerFormContaner rounded-lg">
            <form class="p-8">
                <h2 class="font-semibold py-2 text-2xl text-center">
                    Join edSpark today!
                </h2>
                <TextInput
                    v-model="v$.orgName.$model"
                    field-id="organisationName"
                    :v$="v$"
                    placeholder=""
                >
                    <template #label>
                        Organisation Name <span class="text-red-500">*</span>
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.repName.$model"
                    field-id="representativeName"
                    :v$="v$"
                    placeholder=""
                >
                    <template #label>
                        Representative Name <span class="text-red-500">*</span>
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.repEmail.$model"
                    field-id="representativeEmail"
                    :v$="v$"
                    placeholder=""
                >
                    <template #label>
                        Representative Email <span class="text-red-500">*</span>
                    </template>
                </TextInput>
                <DropdownInput
                    v-model="v$.busType.$model"
                    field-id="partnerBusinessType"
                    :v$="v$"
                    placeholder=""
                    :data="partnerBusinessTypes"
                    name="business type"
                >
                    <template #label>
                        What best describe your business? <span
                            class="text-red-500"
                        >*</span>
                    </template>
                </DropdownInput>
                <hr>
                <div class="flex justify-center flex-col pt-4 w-full">
                    <div class="flex flex-row labelRow">
                        Completed edSpark sign up form <span
                            class="text-red-500"
                        >*</span>
                    </div>
                    <label
                        for="file-input"
                        class="sr-only"
                    >Choose file</label>
                    <input
                        id="file-input"
                        type="file"
                        name="file-input"
                        class="
                            block
                            border
                            dark:border-gray-700
                            file:border-0
                            focus:border-blue-500
                            border-gray-300
                            rounded
                            shadow-sm
                            text-sm
                            dark:file:text-gray-400
                            dark:text-gray-400
                            w-full
                            dark:bg-slate-900
                            dark:file:bg-gray-700
                            dark:focus:outline-none
                            dark:focus:ring-1
                            dark:focus:ring-gray-600
                            disabled:opacity-50
                            disabled:pointer-events-none
                            file:bg-gray-100
                            file:bg-gray-50
                            file:me-4
                            file:px-4
                            file:py-3
                            focus:ring-blue-500
                            focus:z-10
                            ">

                    <GenericButton
                        class="grow-0 mt-4"
                        :callback="() => {}"
                    >
                        Submit
                    </GenericButton>
                </div>
            </form>
        </div>
        <div class="Description basis-1/2 flex flex-col p-8 partnerForm">
            <h2 class="font-extrabold py-2 text-2xl text-center">
                edSpark for Partners (Content TBD)
            </h2>
            <h3 class="font-bold">
                What is edSpark? (sample content)
            </h3>
            <p>edSpark is a platform for schools, SA Department for Education and industry partners to showcase their technology and encourage the use of technology for all educators in South Australia</p>
            <h3 class="font-bold mt-4">
                Selection criteria for partners:
            </h3>
            <ul class="list-disc pl-6">
                <li> Has current active operation in South Australia</li>
                <li> Works directly with at least 1 school and Department for Education</li>
                <li> Agrees to edSpark terms and conditions</li>
            </ul>
            <h3 class="font-bold mt-4">
                How to join edSpark?
            </h3>
            <ul class="list-disc pl-6">
                <li>Download edSpark application form below</li>
                <li>Upload the completed document to the form on this page</li>
                <li>We will contact you via email to advise the outcome of your application - keep an eye out!</li>
            </ul>
            <GenericButton
                class="grow-0 mt-4"
                :callback="() => {}"
            >
                <FileIcon />  Download Form
            </GenericButton>
        </div>
    </div>
</template>
