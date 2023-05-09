<script setup>

import NavBar from './components/global/NavBar.vue';
import Footer from './components/global/Footer.vue';
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";
import axios from "axios";
import {serverURL} from "@/js/constants/serverUrl";




const userStore = useUserStore()
const {userLikeList, userBookmarkList, currentUser} = storeToRefs(userStore)


const query = {
    user_id: currentUser.value.id,
    post_type: 'software'
}
axios.post(`${serverURL}/fetchAllLikesByType`, query).then(res => {
    let temp = []
    // get id Number only
    for(let x of res.data.data){
        temp.push(x.post_id)
    }
    userLikeList.value.software = temp

}).catch(err => {
    console.log(`there has been an error while fetching users like`)
    console.log(err.message)
})

axios.post(`${serverURL}/fetchAllBookmarksByType`, query).then(res => {
    let temp = []
    // get id Number only
    for(let x of res.data.data){
        temp.push(x.post_id)
    }
    userBookmarkList.value.software = temp

}).catch(err => {
    console.log(`there has been an error while fetching users like`)
    console.log(err.message)
})
</script>

<template>
    <NavBar />

    <div class="pageBodyContentContainer">
        <router-view />
    </div>
    <footer class="mt-auto">
        <Footer />
    </footer>
</template>
