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
    <div class="PartnerFormContainer flex justify-around flex-row mt-10 px-8  gap-8 lg:!px-10">
        <div class="basis-1/2 bg-white border-[1px] drop-shadow-lg flex justify-center partnerFormContaner rounded-lg">
            <form class="p-4">
                <h2 class="font-semibold py-2 text-2xl text-center">
                    Join edSpark today
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
        <div class="Description basis-1/2 flex flex-col p-4 partnerForm px-8">
            <h2 class="font-extrabold py-2 text-2xl text-center">
                edSpark for Partners
            </h2>

            <p class="text-justify">
                Welcome to edSpark, a Digital Hub is where education and technology meet. This hub serves as a
                collaborative space where you can connect, share knowledge, and contribute to the growth and development
                of education professionals, including educators and principals.
            </p>
            <p>
                At edSpark, we believe in the power of collaboration and the exchange of ideas. This platform provides a
                centralized space for partners to showcase their expertise, products, and services, enabling educators
                and principals to access a wealth of valuable resources and stay updated on the latest trends and
                advancements in the education field. Through this hub, you can share your expertise, educational events,
                workshops, and other relevant information with our community.
            </p>
            <h3 class="font-bold mt-4">
                Selection criteria for partners:
            </h3>
            <ul class="list-disc pl-6">
                <li>Alignment with Educational Goals</li>
                <li>Security and Data Privacy</li>
                <li>User Experience and Accessibility</li>
                <li>Reliability and Stability</li>
                <li>Integration and Interoperability</li>
                <li>Scalability and Flexibility</li>
                <li>Cost-effectiveness and Value</li>
            </ul>
            <h3 class="font-bold mt-4">
                How to join edSpark?
            </h3>
            <ul class="list-disc pl-6">
                <li>Submit a pdf addressing the criteria mentioned above through the form on this page.</li>
            </ul>
        </div>
    </div>
</template>
