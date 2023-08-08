import {serverURL} from "@/js/constants/serverUrl";
import axios from "axios";
import {schoolContentArrParser} from "@/js/helpers/jsonHelpers";
import {guid} from "@/js/helpers/guidGenerator";

let instance;

const recommenderEdsparkSingletonFactory = (function(){
        class recommenderEdspark{
            constructor(userId, userRole, siteId ){
                this.userId = userId
                this.userRole = userRole
                this.userSiteId = siteId
                this.userYearLevels = []; // to be used in tag
                this.userInterests= [] ;
                this.softwares =[]
                this.schools = []
                this.userInfo = this._fetchUserInformationAsync(this.userId)
                this.uniqueInstanceId = guid()
            }

            /**
             * Get users information in database - can be useful for more advanced control
             * @param id
             * @returns {Promise<axios.AxiosResponse<any>>}
             * @private
             */
            _fetchUserInformationAsync(id){
                return axios.get(`${serverURL}/fetchUser/${id}`).then(res =>{
                    return res.data
                })
            }
            _fetchAllSchoolsAsync() {
                return axios.get(API_ENDPOINTS.SCHOOL.FETCH_ALL_SCHOOLS).then(res => {
                    return res.data
                    }
                )
            }
            _fetchAllSoftwareAsync(){
                return axios.get(API_ENDPOINTS.SOFTWARE_FETCH_SOFTWARE_POSTS).then(res => {
                        return res.data
                    }
                )
            }
            _fetchAllHardwareAsync(){
                return axios.get(`${serverURL}/fetchAllProducts`).then(res => {
                        return res.data
                    }
                )
            }
            _fetchAllAdviceAsync(){
                return axios.get(`${serverURL}/fetchAdvicePosts`).then(res => {
                        return res.data
                    }
                )
            }
            _fetchAllPartersAsync(){
                return axios.get(`${serverURL}/fetchAllPartners`).then(res => {
                        return res.data
                    }
                )
            }
            _fetchAllEventsAsync(){
                // return axios.get(API_ENDPOINTS.SCHOOL.FETCH_ALL_SCHOOLS).then(res => {
                //         return res.data
                //     }
                // )
            }

            _logRecommenderData(){
                console.log('user id ', this.userId)
                console.log('user role ', this.userRole)
                console.log('site id ', this.userSiteId )
                console.log('recommender unique id ', this.uniqueInstanceId )
            }

            getSchoolsByTechnology(){
                console.log('getschoolbyTech')
            }
            async getSoftwareByUser() {
                console.log('called gfetsoftware by user')
            }

            /**
             *  Get software from the same author
             * @param currentAuthor : Number
             * @returns {Promise<void>}
             */
            async getSoftwareByItem(currentAuthor) {
                const allSoftware = await this._fetchAllSoftwareAsync()

                // get same brand or publisher or type - TODO}
            }

            async getAdviceByAuthorId(authorId){
                return new Promise(async (res,rej) =>{
                    const allAdvice = await this._fetchAllAdviceAsync()
                    console.log(allAdvice)
                    res(allAdvice.filter(advice => advice.author.author_id === +authorId))
                })
            }

            /**
             * Get technology (Hardware and Software) that is allowed to see by partners
             * returns a promise
             * @returns {Promise<Object>}
             */
            async getTechByAuthorAsync(authorId){
                return new Promise(async(resolve, reject) => {
                    const allHardware = await this._fetchAllHardwareAsync()
                    const allSoftware = await this._fetchAllSoftwareAsync()
                    let result = {}
                    result['hardware'] = allHardware.filter(hardware => hardware['author']['author_id'] === +authorId )
                    //if length is zero, return all data
                    // if(result['hardware'].length === 0){
                    //     result['hardware'] = allHardware
                    // }
                    result['software'] = allSoftware.filter(software => software['author']['author_id'] === +authorId)
                    // if(result['software'].length === 0) {
                    //     result['software'] = allSoftware
                    // }
                    resolve(result)
                })
            }

            /**
             * Get all partners for partners page. if current user is partner, will exlude own from the search results
             * and have "my own" section -- future
             * @returns {Promise<void>}
             */
            async getAllPartnersForPartner() {
                return new Promise( async(resolve,reject) => {
                    const allPartners = await this._fetchAllPartersAsync()
                    let result = {}
                    console.log(this.userId)
                    console.log(allPartners)
                    this._logRecommenderData()
                    // result['partners'] = this.userRole === "Partner" ? allPartners.filter(partner => partner.user_id !== this.userId) : allPartners
                    result['partners'] = allPartners
                    resolve(result)
                })

            }
        }

        return{
            getInstance: function(userId, userRole, siteId){
                if(!instance){
                    console.log('creating new instance of recommender')
                    instance = new recommenderEdspark(userId, userRole, siteId)
                }
                return instance
            }
        }

    }

)

export default recommenderEdsparkSingletonFactory