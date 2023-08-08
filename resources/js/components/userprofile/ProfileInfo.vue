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
    <div class="bg-main-teal/80 flex flex-col profileInfoContainer pt-4 px-1 py-6 rounded-b-xl text-lg text-white md:!px-6">
        <div class="flex justify-center flex-col md:!flex-row">
            <div
                class="
                    flex
                    flex-row
                    profileInfoMenu
                    text-sm
                    w-full
                    md:!basis-1/4
                    md:!border-r
                    md:!border-slate-300
                    md:!flex-col
                    md:!pl-4
                    md:!text-lg
                    ">
                <div
                    v-for="(item,index) in userInfoMenu"
                    :key="index"
                    class="
                        decoration-4
                        decoration-white
                        profileInfoMenuItem
                        px-1
                        py-4
                        text-center
                        transition-all
                        underline-offset-8
                        hover:underline
                        w-full
                        hover:cursor-pointer
                        md:!px-4
                        md:!text-left
                        "
                    :class="{'font-bold underline' : item === currentActiveUserInfoMenu ,
                    }"
                    @click=" () => handleClickProfileInfoMenu(item)"
                >
                    {{ item }}
                </div>
            </div>
            <div class="basis-3/4 bg-white profileInfoContent">
                <ProfileInfoContent :content-type="currentActiveUserInfoMenu" />
            </div>
        </div>
    </div>
</template>
