<script setup>
import axios from 'axios'
import {serverURL} from "@/js/constants/serverUrl";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import {axiosFetcher} from "@/js/helpers/fetcher";
import useSWRV from "swrv";
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";

const {data: softwareList , error} = useSWRV(`${serverURL}/fetchSoftwarePosts`, axiosFetcher)

/**
 * not needed once the fetch is done on the dashbaord. maybe have a seperate update or check here for each type
 * @type {{user_id: number, post_type: string}}
 */
const query = {
    user_id: 2,
    post_type: 'software'
}

const userStore = useUserStore()
const {userLikeList, userBookmarkList} = storeToRefs(userStore)

axios.post(`${serverURL}/fetchAllLikesByType`, query).then(res => {
    let temp = []
    // get id Number only
    for(let x of res.data){
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
    for(let x of res.data){
        temp.push(x.post_id)
    }
    userBookmarkList.value.software = temp

}).catch(err => {
    console.log(`there has been an error while fetching users like`)
    console.log(err.message)
})

</script>
<template>
    <template v-if="softwareList">
        <div class="flex flex-row flex-wrap w-full justify-between pt-10 gap-6 px-20">
            <SoftwareCard
                v-for="(software,index) in softwareList"
                :key="index"
                :software="software"
                :number-per-row="4"
            />
        </div>
    </template>
    <template v-else>
        Loading
    </template>
</template>
