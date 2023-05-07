<script setup>

import {ref} from "vue";
import {storeToRefs} from "pinia";

import {useUserStore} from "../../stores/useUserStore";
import ProfileInfoContent from './ProfileInfoContent.vue'
import Edit from  '../svg/Edit.vue'
import Save from  '../svg/Save.vue'

const userStore = useUserStore()
const { currentUser } = storeToRefs(userStore)


/**
 * Flags
 */
const editBio = ref(false)
const editYearLevels = ref(false)
//**

// const updatedName = ref(currentUser.value['full_name'])
const currentActiveUserInfoMenu = ref('Biography')


const theYearLevels = ref([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13])
const userInfoMenu = ref(['Biography', 'Year levels', 'Subjects','Interests'])

const replacementMeta = [
    'biography',
    'yearLevels',
    'subjects',
    'interests'
]

const biography = ref(null)
const interests = ref(null)
const subjects = ref(null)
const yearLevels = ref([])


const handleSaveChange = () => {
    console.log('handle save change called hehe')
}

const handleClickProfileInfoMenu = (menuName) => {
    // console.log(menuName)
    currentActiveUserInfoMenu.value = menuName
}

</script>
<template>
    <div class="profileInfoContainer flex flex-col pt-4 px-6 py-6 bg-blue-200">
        <div class="flex flex-row">
            <div class="profileInfoMenu flex flex-col pl-4 basis-1/4 col-span-1 border-r border-slate-300 pr-12">
                <div
                    v-for="(item,index) in userInfoMenu"
                    :key="index"
                    class="profileInfoMenuItem py-2 hover:cursor-pointer w-full text-left px-4 py-2 hover:underline decoration-[#1C5CA9] decoration-4 underline-offset-8 transition-all"
                    :class="{'font-semibold' : item === currentActiveUserInfoMenu ,
                             'underline' : item === currentActiveUserInfoMenu
                    }"
                    @click=" () => handleClickProfileInfoMenu(item)"
                >
                    {{ item }}
                </div>
            </div>
            <div class="profileInfoContent basis-3/4 bg-green-100">
                <ProfileInfoContent :content-type="currentActiveUserInfoMenu" />
            </div>
        </div>
    </div>
</template>
