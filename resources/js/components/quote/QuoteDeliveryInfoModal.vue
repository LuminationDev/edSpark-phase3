<script setup>
import {useVuelidate} from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import {storeToRefs} from "pinia";
import {computed, reactive, ref} from 'vue'

import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import {useQuoteStore} from "@/js/stores/useQuoteStore";

const props = defineProps({})

const emits = defineEmits(['confirm', 'cancel'])
const {quoteUserInfo} = storeToRefs(useQuoteStore())

const state = reactive({
    name: quoteUserInfo.value.name ,
    institution: quoteUserInfo.value.institution,
    address: quoteUserInfo.value.address,
})

const rules = {
    name: {required},
    institution: {required},
    address: {required}
}


const v$ = useVuelidate(rules, state)

const handleModalCancel = () => {
    emits('cancel')

}

const handleModalConfirm = async () => {
    console.log('confirmed')
    await v$.value.$validate()
    if (!v$.value.$error) {
        console.log(v$.value)
        quoteUserInfo.value['name'] = v$.value.name
        quoteUserInfo.value['institution'] = v$.value.institution
        quoteUserInfo.value['address'] = v$.value.address

        emits('confirm')
    } else {
    }

}

</script>

<template>
    <div
        class="absolute top-0 right-0 bottom-0 left-0 globalSearchScreenContainer grid place-items-center h-screen w-screen z-50"
    >
        <div
            class="bg-main-darkTeal/80 fixed top-0 left-0 grayoverlay h-[1000vh] w-full z-40"
            @click="handleModalCancel"
        />
        <div
            class="
                -translate-x-1/2
                -translate-y-1/2
                bg-white
                border-[1px]
                border-slate-300
                fixed
                top-1/2
                left-1/2
                p-6
                rounded-xl
                shadow
                w-[50vh]
                z-50
                "
        >
            <div class="mb-2 text-lg text-main-darkTeal">
                Delivery Info
            </div>
            <div class="mb-2 text-base">
                Before proceeding with the quote, please ensure this information is correct
            </div>
            <div class="border-[1px] border-slate-300 flex flex-col innerContainer mb-4 p-4 rounded-xl shadow">
                <TextInput
                    v-model="v$.name.$model"
                    class=""
                    field-id="deliveryName"
                    :v$="v$.name"
                    placeholder="Name"
                >
                    <template #label>
                        Full name
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.institution.$model"
                    field-id="deliveryInstitution"
                    :v$="v$.institution"
                    placeholder="Institution"
                >
                    <template #label>
                        Institution name
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.address.$model"
                    field-id="deliveryName"
                    :v$="v$.address"
                    placeholder="Address"
                >
                    <template #label>
                        Full address (include suburb and postcode)
                    </template>
                </TextInput>
            </div>
            <div class="flex justify-around flex-row">
                <GenericButton
                    class="bg-yellow-100 px-4 py-2 rounded"
                    :callback="handleModalConfirm"
                >
                    Confirm
                </GenericButton>
                <GenericButton
                    class="bg-yellow-100 px-4 py-2 rounded"
                    :callback="handleModalCancel"
                >
                    Cancel
                </GenericButton>
            </div>
        </div>
    </div>
</template>
