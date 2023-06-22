import {serverURL} from "@/js/constants/serverUrl";

const recommenderEdsparkSingletonFactory = (function(){
        class recommenderEdspark{
            constructor(userId, userRole, siteId ){
                this.userId = userId
                this.userRole = userRole
                this.userSiteId = siteId
                this.userYearLevels = [];
                this.userInterests= [] ;
                this.softwares =[]
                this.schools = []
                // user

            }
            _fetchAllSchools(){
                axios.get(`${serverURL}`)
            }
            _fetchAllSoftware(){}
            _fetchAllHardware(){}
            _fetchAllAdvice(){}
            _fetchAllParters(){}
            _fetchAllEvents(){}

            getSchoolsByTechnology(){
                console.log('getschoolbyTech')
            }
        }

    var instance;
        return{
            getInstance: function(userId, userRole, siteId){
                if(!instance){
                    instance = new recommenderEdspark(userId, userRole, siteId)
                }
                return instance
            }
        }

    }

)
