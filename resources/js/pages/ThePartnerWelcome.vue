<script setup lang="ts">
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import {reactive} from "vue";

import DropdownInput from "@/js/components/bases/DropdownInput.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import TextInputFloating from "@/js/components/bases/TextInputFloating.vue";
import DashboardHero from "@/js/components/dashboard/DashboardHero.vue";

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
    <div class="PartnerFormContainer flex justify-around flex-row mt-10 px-8 lg:!px-20">
        <div class="bg-white border-[1px] border-gray-300 drop-shadow flex partnerFormContaner rounded-lg">
            <form class="p-8">
                <h2 class="font-semibold py-2 text-2xl text-center">
                    Join edSpark today!
                </h2>
                <TextInput
                    v-model="v$.orgName.$model"
                    field-id="organisationName"
                    v$="v$"
                    placeholder=""
                >
                    <template #label>
                        Organisation Name <span class="text-red-500">*</span>
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.repName.$model"
                    field-id="representativeName"
                    v$="v$"
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
            </form>
        </div>
        <div class="Description partnerForm">
            <p>edSpark for Partners</p>
        </div>
    </div>
</template>
