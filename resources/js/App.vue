<script setup>

import NavBar from './components/global/NavBar.vue';
import Footer from './components/global/Footer.vue';
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";
import axios from "axios";
import {serverURL} from "@/js/constants/serverUrl";
import {useRouter} from 'vue-router';
import oktaAuth from "@/js/constants/oktaAuth";
import {onBeforeMount, onMounted, reactive, ref} from "vue";
import recommenderEdsparkSingletonFactory from "@/js/recommender/recommenderEdspark";
import {useSessionStorage, useStorage} from "@vueuse/core";
import {isObjectEmpty} from "@/js/helpers/objectHelpers";


const router = useRouter();
// const userStore = useUserStore()
// const {userLikeList, userBookmarkList, currentUser} = storeToRefs(userStore)
//
//
// const query = {
//     // user_id: currentUser.value.id,
//     user_id: 20,
//     post_type: 'software'
// }
// try {
//     axios.post(`${serverURL}/fetchAllLikesByType`, query).then(res => {
//         let temp = []
//         // get id Number only
//         for (let x of res.data.data) {
//             temp.push(x.post_id)
//         }
//         userLikeList.value.software = temp
//
//     }).catch(err => {
//         console.log('Failed while sending post request')
//     })
// } catch (e) {
//     console.log('failed to retreive likes')
// }
//
// try {
//     axios.post(`${serverURL}/fetchAllBookmarksByType`, query).then(res => {
//         let temp = []
//         // get id Number only
//         for (let x of res.data.data) {
//             temp.push(x.post_id)
//         }
//         userBookmarkList.value.software = temp
//
//     }).catch(err => {
//         console.log(`there has been an error while fetching users bookmark`)
//     })
//
// } catch (e) {
//     console.log('failed to retrive bookmarks')
// }
let recommender
const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)


onMounted(() => {
    if(isObjectEmpty(currentUser.value)){
        currentUser.value = useStorage('currentUser',{}, localStorage).value
        console.log('setCurrentUser in store to currentUSerFrom session')
    }
    if(currentUser.value?.id){
        recommender = recommenderEdsparkSingletonFactory().getInstance(currentUser.value.id,'Partner', 100)
    }

})
</script>

<template>
    <NavBar
        :key="router.currentRoute.value"
    />

    <div class="pageBodyContentContainer">
        <router-view />
    </div>
    <footer class="mt-auto">
        <Footer />
    </footer>
</template>
