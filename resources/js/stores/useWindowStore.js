import {defineStore} from 'pinia'


export const useWindowStore = defineStore('window',{
    state: () => ({
        windowWidth: 0,
        isMobile: false,
        isTablet: false,
        showMobileNavbar: false
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
        },
        getMobileNavbar(){
            return this.showMobileNavbar
        },
        getNumberOfCardLoading(){
            return this.isMobile ? 1 : this.isTablet ? 2 : 3
        }
    },
    actions:{
        updateIsMobile(){
            this.isMobile = this.windowWidth < 1024

        },
        updateIsTablet(){
            this.isTablet = this.windowWidth >= 1024 && this.windowWidth < 1440
        }

    }

})
