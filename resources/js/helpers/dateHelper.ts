export const formatDateToTimeOnly = (date) =>{
    return new Date(Date.parse(date)).toLocaleString('en-AU',{ hour: 'numeric', minute: 'numeric', hour12: true } )
}
export const formatDateToDayTime = (date) =>{
    const newDate = new Date(date).toLocaleString('en-AU',{ weekday: 'short',day: '2-digit', month:'short' ,hour: 'numeric', minute: 'numeric', hour12: true } )
    if(newDate.includes('Invalid')){
        return ""
    } else{
        return newDate
    }
}
