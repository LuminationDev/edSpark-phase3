import {defineStore} from 'pinia'


export const useWindowStore = defineStore('window',{
    state: () => ({
        windowWidth: 0,
        isMobile: false,
        isTablet: false
    }),
    getters: {
        getWindowWidth() {
            return this.windowWidth
        },
        getIsMobile(){
            return this.isMobile
        },
        getIsTablet(){
            return this.isTablet
        }
    },
    actions:{
        updateIsMobile(){
            this.isMobile = this.windowWidth < 1024
            console.log('inside updateismobnile')

        },
        updateIsTablet(){
            this.isTablet = this.windowWidth >= 1024 && this.windowWidth < 1440
            console.log('inside updateistablet')
        }

    }

})
