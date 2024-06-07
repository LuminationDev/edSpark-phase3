<script setup>
import {useVuelidate} from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import {debounce} from "lodash";
import {storeToRefs} from "pinia";
import {onMounted, onUnmounted, reactive} from 'vue'

import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import {useQuoteStore} from "@/js/stores/useQuoteStore";


const emits = defineEmits(['confirm', 'cancel'])
const {quoteUserInfo, quote} = storeToRefs(useQuoteStore())
const additionalNotes = defineModel()

const state = reactive({
    name: quoteUserInfo.value.name,
    institution: quoteUserInfo.value.institution,
    address: quoteUserInfo.value.address,
    notes: quoteUserInfo.value.notes,
})

const rules = {
    name: {required},
    institution: {required},
    address: {required},
    notes: {}
}


const v$ = useVuelidate(rules, state)

const handleModalCancel = () => {
    emits('cancel')

}

const handleModalConfirm = async () => {
    await v$.value.$validate()
    console.log(v$.value.$error)
    if (!v$.value.$error) {
        quoteUserInfo.value['name'] = v$.value.name.$model
        quoteUserInfo.value['institution'] = v$.value.institution.$model
        quoteUserInfo.value['address'] = v$.value.address.$model
        quoteUserInfo.value['notes'] = v$.value.notes.$model

        // maps the notes to the item on confirmation
        const noteEntries = Object.entries(additionalNotes)
        const quoteWithNotes = [...quote.value]
        for (const [key, value] in noteEntries) {
            quoteWithNotes.forEach(quoteItem => {
                if (quoteItem.unique_reference === key) {
                    quoteItem.notes = value;
                }
            });
        }
        console.log(quoteWithNotes)
        console.log(quote.value)
        quote.value = quoteWithNotes;
        emits('confirm')
    } else {
    }
}

onMounted(() => {
    const scrollBarWidth = window.innerWidth - document.documentElement.clientWidth
    document.body.style.paddingRight = `${scrollBarWidth}px`;
    document.body.style.overflowY = 'hidden';
})

onUnmounted(() => {
    document.body.style.overflowY = 'auto';
    document.body.style.paddingRight = '0px';
})

// const handleInputNotes = (note, uniqueRef) => {
//     additionalNotes[uniqueRef] = note
//     console.log(additionalNotes)
// }
//
// const debouncedHandleInputNotes = debounce(handleInputNotes, 500)

</script>

<template>
    <div
        class="absolute top-0 right-0 bottom-0 left-0 globalSearchScreenContainer grid place-items-center h-screen w-screen z-50"
    >
        <div
            class="bg-main-darkTeal/80 fixed top-0 left-0 grayoverlay h-[1000vh] w-full z-40"
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
                max-h-[50vh]
                overflow-y-scroll
                p-6
                rounded-xl
                shadow
                w-[50vw]
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
                        Delivery Contact
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.institution.$model"
                    field-id="deliveryInstitution"
                    :v$="v$.institution"
                    placeholder="Institution"
                >
                    <template #label>
                        Site Name
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.address.$model"
                    field-id="deliveryName"
                    :v$="v$.address"
                    placeholder="Address"
                >
                    <template #label>
                        Site Address
                    </template>
                </TextInput>
            </div>
            <div class="mb-2 text-lg text-main-darkTeal">
                Notes
            </div>
            <div class="mb-2 text-base">
                Add notes to the items in the quote
            </div>

            <div class="border-[1px] border-slate-300 flex flex-col innerContainer mb-4 p-4 rounded-xl shadow">
                <TextInput
                    v-model="v$.notes.$model"
                    field-id="deliveryNotes"
                    :v$="v$.notes"
                    placeholder="Add your notes"
                >
                    <template #label>
                        Delivery Notes
                    </template>
                </TextInput>
                <div
                    v-for="(item,index) in quote"
                    :key="index"
                >
                    <TextInput
                        v-model="additionalNotes[item.unique_reference]"
                        :field-id="'deliveryNotes' + item.name"
                        :v$="{}"
                    >
                        <template #label>
                            {{ item.name }}
                        </template>
                    </TextInput>
                </div>
            </div>
            <div class="flex justify-around flex-row">
                <GenericButton
                    class="bg-main-darkTeal px-4 py-2 rounded"
                    :callback="handleModalConfirm"
                >
                    Confirm
                </GenericButton>
                <GenericButton
                    class="bg-main-darkTeal px-4 py-2 rounded"
                    :callback="handleModalCancel"
                >
                    Cancel
                </GenericButton>
            </div>
        </div>
    </div>
</template>
